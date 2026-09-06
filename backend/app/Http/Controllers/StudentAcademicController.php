<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AcademicSchedule;
use App\Models\Grade;
use App\Models\Student;

class StudentAcademicController extends Controller
{
    /**
     * Get schedules for the student.
     */
    public function schedules(Request $request)
    {
        // Currently schedules might be global or per class. 
        // We'll return all academic schedules for now.
        $schedules = AcademicSchedule::with('teacher.user')->get();
        return response()->json($schedules);
    }

    /**
     * Get grades for the logged in student.
     */
    public function grades(Request $request)
    {
        $user = $request->user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return response()->json(['message' => 'Profil murid tidak ditemukan.'], 404);
        }

        $grades = Grade::with('teacher.user')->where('student_id', $student->id)->get();
        return response()->json($grades);
    }
}
