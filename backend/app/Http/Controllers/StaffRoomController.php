<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\RoomAssignment;
use App\Models\Student;
use Carbon\Carbon;

class StaffRoomController extends Controller
{
    /**
     * Get all rooms with occupancy stats
     */
    public function index()
    {
        $rooms = Room::withCount(['assignments as current_occupants' => function($q) {
            $q->whereNull('end_date')->orWhere('end_date', '>=', Carbon::now());
        }])->get();

        return response()->json($rooms);
    }

    /**
     * Get a specific room and its current occupants
     */
    public function show($id)
    {
        $room = Room::findOrFail($id);
        
        $occupants = RoomAssignment::with('student.user')
            ->where('room_id', $id)
            ->where(function($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', Carbon::now());
            })
            ->get();

        return response()->json([
            'room' => $room,
            'occupants' => $occupants
        ]);
    }

    /**
     * Reassign or remove student from room
     */
    public function removeOccupant($assignmentId)
    {
        $assignment = RoomAssignment::findOrFail($assignmentId);
        $assignment->end_date = Carbon::now();
        $assignment->save();

        return response()->json(['message' => 'Siswa berhasil dikeluarkan dari kamar.']);
    }

    /**
     * Add student to a room manually
     */
    public function assignStudent(Request $request, $roomId)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id'
        ]);

        $room = Room::findOrFail($roomId);
        $student = Student::findOrFail($request->student_id);

        if ($room->gender_type !== $student->gender) {
            return response()->json(['message' => 'Gender siswa tidak cocok dengan tipe kamar.'], 400);
        }

        // Check if room is full
        $currentOccupants = RoomAssignment::where('room_id', $roomId)
            ->where(function($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', Carbon::now());
            })->count();

        if ($currentOccupants >= $room->capacity) {
            return response()->json(['message' => 'Kamar sudah penuh.'], 400);
        }

        // Remove student from other active rooms first
        RoomAssignment::where('student_id', $student->id)
            ->where(function($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', Carbon::now());
            })->update(['end_date' => Carbon::now()]);

        // Assign to new room
        RoomAssignment::create([
            'student_id' => $student->id,
            'room_id' => $room->id,
            'start_date' => Carbon::now()
        ]);

        return response()->json(['message' => 'Siswa berhasil ditambahkan ke kamar.']);
    }
}
