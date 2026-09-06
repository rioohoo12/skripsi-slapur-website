<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class StaffStudentController extends Controller
{
    /**
     * Get all students
     */
    public function index()
    {
        $students = Student::with('user')->latest()->get();
        return response()->json($students);
    }

    /**
     * Store a newly created student (Manual add by Staff)
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'nisn' => 'required|string|unique:students,nisn',
            'gender' => 'required|in:L,P',
            'date_of_birth' => 'required|date'
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'gender' => $request->gender,
            ]);
            
            $user->assignRole('Murid');

            $student = Student::create([
                'user_id' => $user->id,
                'nisn' => $request->nisn,
                'gender' => $request->gender,
                'date_of_birth' => $request->date_of_birth
            ]);

            DB::commit();
            return response()->json(['message' => 'Siswa berhasil ditambahkan.', 'student' => $student->load('user')], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal menambahkan siswa.'], 500);
        }
    }

    /**
     * Update student
     */
    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'required|in:L,P',
            'nisn' => 'required|string|unique:students,nisn,' . $student->id,
            'date_of_birth' => 'required|date'
        ]);

        DB::beginTransaction();
        try {
            $student->update([
                'nisn' => $request->nisn,
                'gender' => $request->gender,
                'date_of_birth' => $request->date_of_birth
            ]);

            if ($student->user) {
                $student->user->update([
                    'name' => $request->name,
                    'gender' => $request->gender
                ]);
            }

            DB::commit();
            return response()->json(['message' => 'Data siswa berhasil diperbarui.', 'student' => $student->load('user')]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal memperbarui siswa.'], 500);
        }
    }

    /**
     * Delete student
     */
    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        $user = $student->user;
        
        $student->delete();
        if ($user) {
            $user->delete(); // Cascades nicely
        }
        
        return response()->json(['message' => 'Siswa berhasil dihapus.']);
    }
}
