<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\DiningService;
use Carbon\Carbon;

class StaffDiningController extends Controller
{
    /**
     * Get dining logs for a specific date and meal type
     */
    public function index(Request $request)
    {
        $date = $request->query('date', Carbon::today()->toDateString());
        $mealType = $request->query('meal_type', 'breakfast');

        $students = Student::with(['user:id,name', 'diningServices' => function($q) use ($date, $mealType) {
            $q->whereDate('date', $date)
              ->where('meal_type', $mealType);
        }])->get();

        $result = $students->map(function ($student) {
            return [
                'id' => $student->id,
                'name' => $student->user->name ?? 'Unknown',
                'nisn' => $student->nisn,
                'dining_id' => $student->diningServices->first()->id ?? null,
                'status' => $student->diningServices->first()->status ?? null,
                'menu_served' => $student->diningServices->first()->menu_served ?? ''
            ];
        });

        return response()->json($result);
    }

    /**
     * Store dining logs
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'meal_type' => 'required|in:breakfast,lunch,dinner',
            'dinings' => 'required|array',
            'dinings.*.student_id' => 'required|exists:students,id',
            'dinings.*.status' => 'required|in:eaten,missed',
            'dinings.*.menu_served' => 'nullable|string'
        ]);

        $saved = 0;
        foreach ($request->dinings as $d) {
            DiningService::updateOrCreate(
                [
                    'student_id' => $d['student_id'],
                    'date' => $request->date,
                    'meal_type' => $request->meal_type
                ],
                [
                    'status' => $d['status'],
                    'menu_served' => $d['menu_served'] ?? null
                ]
            );
            $saved++;
        }

        return response()->json([
            'message' => "Berhasil menyimpan log layanan makan untuk {$saved} siswa."
        ]);
    }
}
