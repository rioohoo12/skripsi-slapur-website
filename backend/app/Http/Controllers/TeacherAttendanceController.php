<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Attendance;
use Carbon\Carbon;

class TeacherAttendanceController extends Controller
{
    /**
     * Get list of students and their attendance for a specific date
     */
    public function index(Request $request)
    {
        $date = $request->query('date', Carbon::today()->toDateString());
        
        $students = Student::with(['user:id,name', 'attendances' => function($q) use ($date) {
            $q->whereDate('date', $date);
        }])->get();

        $result = $students->map(function ($student) {
            return [
                'id' => $student->id,
                'name' => $student->user->name ?? 'Unknown',
                'nisn' => $student->nisn,
                'attendance_id' => $student->attendances->first()->id ?? null,
                'status' => $student->attendances->first()->status ?? null,
            ];
        });

        return response()->json($result);
    }

    /**
     * Store or update attendance
     */
    public function store(Request $request)
    {
        $request->validate([
            'attendances' => 'required|array',
            'attendances.*.student_id' => 'required|exists:students,id',
            'attendances.*.status' => 'required|in:present,absent,sick,leave',
            'date' => 'required|date'
        ]);

        $date = $request->date;
        $saved = 0;

        foreach ($request->attendances as $att) {
            Attendance::updateOrCreate(
                [
                    'student_id' => $att['student_id'],
                    'date' => $date
                ],
                [
                    'status' => $att['status']
                ]
            );
            $saved++;
        }

        return response()->json([
            'message' => "Berhasil menyimpan absensi {$saved} murid."
        ]);
    }
}
