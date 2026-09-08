<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\RegistrationProgress;
use App\Models\DormitoryType;
use App\Models\Room;
use App\Models\RoomAssignment;

class RegistrationController extends Controller
{
    public function progress($id)
    {
        $student = Student::where('user_id', $id)->firstOrFail();
        $progress = RegistrationProgress::firstOrCreate(
            ['student_id' => $student->id],
            ['step0_status' => 'pending', 'step1_status' => 'pending', 'step2_status' => 'pending']
        );
        
        return response()->json($progress);
    }

    public function submitApplication($id, Request $request)
    {
        $student = Student::where('user_id', $id)->firstOrFail();
        
        $request->validate([
            'name' => 'required|string',
            'address' => 'required|string',
            'class_name' => 'required|string',
            'dormitory_preference' => 'required|in:sederhana,standar',
        ]);
        
        $student->user->name = $request->name;
        $student->user->save();
        
        $student->address = $request->address;
        $student->class_name = $request->class_name;
        $student->dormitory_preference = $request->dormitory_preference;
        $student->save();
        
        // Calculate Total Cost based on reference
        $spp = 500000;
        $dining = 300000;
        $asrama = ($request->dormitory_preference === 'standar') ? 400000 : 200000;
        $total = $spp + $dining + $asrama;
        
        return response()->json([
            'breakdown' => [
                'SPP Sekolah' => $spp,
                'Biaya Makan (Dining)' => $dining,
                'Biaya Asrama' => $asrama
            ],
            'total_biaya' => $total
        ]);
    }
    
    public function dormitoryOptions($id)
    {
        $student = Student::where('user_id', $id)->firstOrFail();
        $gender = $student->gender;
        $category = $student->dormitory_preference;
        
        if (!$category) {
            return response()->json(['message' => 'Anda belum memilih tipe asrama di Langkah 0.'], 400);
        }
        
        $types = DormitoryType::where('gender', $gender)->where('category', $category)->pluck('id');
        
        $rooms = Room::with('dormitoryType')
            ->whereIn('dormitory_type_id', $types)
            ->get();
            
        return response()->json($rooms);
    }
    
    public function dormitorySelection($id, Request $request)
    {
        $student = Student::where('user_id', $id)->firstOrFail();
        $request->validate([
            'room_id' => 'required|exists:rooms,id'
        ]);
        
        $room = Room::with('dormitoryType')->findOrFail($request->room_id);
        
        if ($room->dormitoryType->gender !== $student->gender || $room->dormitoryType->category !== $student->dormitory_preference) {
            return response()->json(['message' => 'Kamar ini tidak sesuai dengan pilihan asrama atau gender Anda.'], 403);
        }
        
        if ($room->occupied_count >= $room->capacity) {
            return response()->json(['message' => 'Kamar ini sudah penuh.'], 403);
        }
        
        $existing = RoomAssignment::where('student_id', $student->id)->where('status', 'active')->first();
        if ($existing) {
            return response()->json(['message' => 'Anda sudah memiliki kamar.'], 400);
        }
        
        RoomAssignment::create([
            'student_id' => $student->id,
            'room_id' => $room->id,
            'status' => 'active',
            'assigned_date' => now(),
            'end_date' => now()->addYear()
        ]);
        
        $room->increment('occupied_count');
        
        $progress = RegistrationProgress::where('student_id', $student->id)->first();
        if ($progress) {
            $progress->step2_status = 'completed';
            $progress->save();
        }
        
        return response()->json(['message' => 'Kamar berhasil dipilih.']);
    }
}
