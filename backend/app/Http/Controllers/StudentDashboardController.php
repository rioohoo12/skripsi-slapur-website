<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Student;
use App\Models\ClassSchedule;
use App\Models\FinancialTransaction;
use Carbon\Carbon;

class StudentDashboardController extends Controller
{
    public function dashboard($id)
    {
        $student = Student::where('user_id', $id)->firstOrFail();

        $dayOfWeek = Carbon::now()->locale('id')->isoFormat('dddd');
        $currentTime = Carbon::now()->format('H:i:s');

        $nextClass = ClassSchedule::with('teacher.user')
            ->where('day', $dayOfWeek)
            ->where('time_start', '>=', $currentTime)
            ->orderBy('time_start', 'asc')
            ->first();

        if (!$nextClass) {
            $nextClass = ClassSchedule::with('teacher.user')->first();
        }

        $latestTransaction = FinancialTransaction::where('student_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->first();

        $balance = $latestTransaction ? (float)$latestTransaction->balance : 0;

        return response()->json([
            'next_class' => $nextClass,
            'financial_balance' => $balance
        ]);
    }

    public function finance($id)
    {
        $student = Student::where('user_id', $id)->firstOrFail();
        
        $transactions = FinancialTransaction::where('student_id', $student->id)
                            ->orderBy('created_at', 'desc')
                            ->orderBy('id', 'desc')
                            ->get();
                            
        return response()->json($transactions);
    }

    public function mealCard($id)
    {
        $student = Student::where('user_id', $id)->firstOrFail();
        $card = \App\Models\MealCard::where('student_id', $student->id)->first();
        
        if (!$card) {
            return response()->json(['message' => 'Kartu makan belum diterbitkan. Selesaikan pendaftaran.'], 404);
        }
        
        return response()->json($card);
    }

    public function mealReports($id)
    {
        $student = Student::where('user_id', $id)->firstOrFail();
        $reports = \App\Models\MealReport::where('student_id', $student->id)
                        ->orderBy('date', 'desc')
                        ->orderBy('time', 'desc')
                        ->get();
                        
        return response()->json($reports);
    }

    public function qr($id)
    {
        $student = Student::where('user_id', $id)->firstOrFail();
        
        // Generate token if not exists
        if (!$student->qr_token) {
            $student->qr_token = \Illuminate\Support\Str::random(16);
            $student->save();
        }
        
        $payload = "STUDENT-" . $student->id . "-" . $student->qr_token;
        
        return response()->json([
            'qr_payload' => $payload
        ]);
    }
}
