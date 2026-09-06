<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\RoomAssignment;
use App\Models\Student;
use Illuminate\Validation\ValidationException;

class RoomController extends Controller
{
    /**
     * Get available rooms filtered by gender of the logged-in student.
     */
    public function availableRooms(Request $request)
    {
        // 1. Get logged in student profile
        $user = $request->user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return response()->json(['message' => 'Profil murid tidak ditemukan.'], 404);
        }

        // 2. Fetch rooms matching the student's gender
        // and calculate remaining capacity
        $rooms = Room::where('gender_type', $student->gender)->get();

        $availableRooms = $rooms->map(function ($room) {
            $occupiedCount = RoomAssignment::where('room_id', $room->id)
                                           ->where('status', 'active')
                                           ->count();
            $remaining = $room->capacity - $occupiedCount;
            
            return [
                'id' => $room->id,
                'name' => $room->name,
                'capacity' => $room->capacity,
                'occupied' => $occupiedCount,
                'remaining' => $remaining,
                'gender_type' => $room->gender_type,
            ];
        })->filter(function ($room) {
            return $room['remaining'] > 0;
        })->values();

        return response()->json($availableRooms);
    }

    /**
     * Assign student to a room.
     */
    public function assignRoom(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'academic_year' => 'required|string',
        ]);

        $user = $request->user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return response()->json(['message' => 'Profil murid tidak ditemukan.'], 404);
        }

        // Check if student already has an active room
        $existingAssignment = RoomAssignment::where('student_id', $student->id)
                                            ->where('status', 'active')
                                            ->first();
        if ($existingAssignment) {
            return response()->json(['message' => 'Anda sudah memiliki kamar aktif.'], 422);
        }

        $room = Room::findOrFail($request->room_id);

        // DEFENSE IN DEPTH: Strict gender validation
        if ($room->gender_type !== $student->gender) {
            return response()->json(['message' => 'Akses ditolak. Gender kamar tidak sesuai.'], 403);
        }

        // Capacity validation
        $occupiedCount = RoomAssignment::where('room_id', $room->id)
                                       ->where('status', 'active')
                                       ->count();
        if ($occupiedCount >= $room->capacity) {
            return response()->json(['message' => 'Kamar sudah penuh.'], 422);
        }

        // Assign Room
        $assignment = RoomAssignment::create([
            'room_id' => $room->id,
            'student_id' => $student->id,
            'academic_year' => $request->academic_year,
            'status' => 'active'
        ]);

        return response()->json([
            'message' => 'Kamar berhasil dipilih.',
            'data' => $assignment
        ]);
    }
    
    /**
     * Get student's current room assignment
     */
    public function myRoom(Request $request)
    {
        $user = $request->user();
        $student = Student::where('user_id', $user->id)->first();
        
        if (!$student) {
            return response()->json(['message' => 'Profil murid tidak ditemukan.'], 404);
        }

        $assignment = RoomAssignment::with('room')
                                    ->where('student_id', $student->id)
                                    ->where('status', 'active')
                                    ->first();
                                    
        return response()->json($assignment);
    }
}
