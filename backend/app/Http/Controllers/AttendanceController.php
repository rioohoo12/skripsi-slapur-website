<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\MealCard;
use App\Models\MealReport;
use App\Models\QrScanLog;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function scanQr(Request $request)
    {
        $request->validate([
            'qr_payload' => 'required|string',
            'scan_type' => 'required|in:dining,room,class'
        ]);

        $payload = $request->qr_payload;
        // Format: STUDENT-{id}-{qr_token}
        $parts = explode('-', $payload);
        
        if (count($parts) !== 3 || $parts[0] !== 'STUDENT') {
            return response()->json(['message' => 'Format QR tidak valid.'], 400);
        }

        $studentId = $parts[1];
        $token = $parts[2];

        $student = Student::find($studentId);

        if (!$student || $student->qr_token !== $token) {
            return response()->json(['message' => 'QR Code tidak valid atau sudah kadaluarsa.'], 401);
        }

        $scanType = $request->scan_type;
        $user = auth()->user();

        // 1. Catat ke tabel log QR generik
        QrScanLog::create([
            'student_id' => $student->id,
            'scan_type' => $scanType,
            'scanned_at' => Carbon::now(),
            'scanned_by' => $user ? $user->id : null
        ]);

        // 2. Jika tipe dining, catat ke meal_reports
        if ($scanType === 'dining') {
            $mealCard = MealCard::where('student_id', $student->id)->first();
            
            if (!$mealCard) {
                return response()->json(['message' => 'Kartu makan belum diterbitkan untuk murid ini.'], 400);
            }

            // Tentukan jenis makan berdasarkan jam (contoh sederhana)
            $hour = Carbon::now()->hour;
            $mealType = 'Makan Siang';
            if ($hour < 11) $mealType = 'Sarapan';
            elseif ($hour > 15) $mealType = 'Makan Malam';

            MealReport::create([
                'student_id' => $student->id,
                'meal_card_number' => $mealCard->card_number,
                'date' => Carbon::today(),
                'time' => Carbon::now()->format('H:i:s'),
                'meal_type' => $mealType,
                'input_by_staff_id' => $user ? $user->id : null
            ]);

            return response()->json([
                'message' => 'Berhasil mencatat pengambilan makan.',
                'student_name' => $student->user->name ?? 'Siswa',
                'meal_type' => $mealType
            ]);
        }

        return response()->json([
            'message' => 'Berhasil memindai QR (Akses ' . ucfirst($scanType) . ').',
            'student_name' => $student->user->name ?? 'Siswa'
        ]);
    }
}
