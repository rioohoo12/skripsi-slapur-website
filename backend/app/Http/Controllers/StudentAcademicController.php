<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassSchedule;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Attendance;

class StudentAcademicController extends Controller
{
    public function myClass($id)
    {
        $student = Student::where('user_id', $id)->firstOrFail();
        $schedules = ClassSchedule::with('teacher.user')->get();
        return response()->json($schedules);
    }

    public function classSchedule(Request $request)
    {
        $schedules = ClassSchedule::with('teacher.user')->get();
        return response()->json($schedules);
    }

    public function attendance($id)
    {
        $student = Student::where('user_id', $id)->firstOrFail();
        $attendances = Attendance::with('classSchedule.teacher.user')
            ->where('student_id', $student->id)
            ->get();
            
        return response()->json($attendances);
    }

    public function gradesByType($id, Request $request)
    {
        $student = Student::where('user_id', $id)->firstOrFail();
        $type = $request->query('type');
        
        $query = Grade::with('teacher.user')->where('student_id', $student->id);
        
        if ($type) {
            $query->where('grade_type', $type);
        }
        
        return response()->json($query->get());
    }
}
