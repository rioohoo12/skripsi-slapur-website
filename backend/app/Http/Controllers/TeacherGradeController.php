<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Grade;
use Illuminate\Support\Facades\Auth;

class TeacherGradeController extends Controller
{
    /**
     * Get list of students and their grades for a specific subject & semester
     */
    public function index(Request $request)
    {
        $subject = $request->query('subject_name');
        $semester = $request->query('semester', 'Ganjil 2026');

        if (!$subject) {
            return response()->json([]);
        }

        $students = Student::with(['user:id,name', 'grades' => function($q) use ($subject, $semester) {
            $q->where('subject_name', $subject)
              ->where('semester', $semester);
        }])->get();

        $result = $students->map(function ($student) {
            return [
                'id' => $student->id,
                'name' => $student->user->name ?? 'Unknown',
                'nisn' => $student->nisn,
                'grade_id' => $student->grades->first()->id ?? null,
                'score' => $student->grades->first()->score ?? null,
            ];
        });

        return response()->json($result);
    }

    /**
     * Store or update grades
     */
    public function store(Request $request)
    {
        $request->validate([
            'grades' => 'required|array',
            'grades.*.student_id' => 'required|exists:students,id',
            'grades.*.score' => 'required|numeric|min:0|max:100',
            'subject_name' => 'required|string',
            'semester' => 'required|string',
        ]);

        $teacherId = Auth::user()->teacher->id ?? 1; // Fallback if no teacher profile somehow

        $saved = 0;
        foreach ($request->grades as $g) {
            Grade::updateOrCreate(
                [
                    'student_id' => $g['student_id'],
                    'subject_name' => $request->subject_name,
                    'semester' => $request->semester,
                ],
                [
                    'teacher_id' => $teacherId,
                    'score' => $g['score']
                ]
            );
            $saved++;
        }

        return response()->json([
            'message' => "Berhasil menyimpan {$saved} nilai murid."
        ]);
    }
}
