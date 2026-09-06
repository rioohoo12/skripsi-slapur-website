<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Room;
use App\Models\Payment;
use App\Models\DiningService;
use App\Models\RoomAssignment;
use Carbon\Carbon;

class StaffReportController extends Controller
{
    /**
     * Get aggregate report data for Dashboard
     */
    public function dashboard()
    {
        // 1. Total Siswa
        $totalStudents = Student::count();

        // 2. Room Occupancy
        $rooms = Room::all();
        $totalCapacity = $rooms->sum('capacity');
        $activeOccupants = RoomAssignment::whereNull('end_date')
            ->orWhere('end_date', '>=', Carbon::now())
            ->count();
        $occupancyRate = $totalCapacity > 0 ? round(($activeOccupants / $totalCapacity) * 100, 1) : 0;

        // 3. Payment Stats
        $successfulPayments = Payment::where('status', 'success')->sum('amount');
        $pendingPaymentsCount = Payment::where('status', 'pending')->count();

        // 4. Dining Stats (Today)
        $today = Carbon::today()->toDateString();
        $totalEaten = DiningService::whereDate('date', $today)->where('status', 'eaten')->count();
        $totalMissed = DiningService::whereDate('date', $today)->where('status', 'missed')->count();

        return response()->json([
            'summary' => [
                'total_students' => $totalStudents,
                'occupancy_rate' => $occupancyRate,
                'total_revenue' => $successfulPayments,
                'pending_payments' => $pendingPaymentsCount,
            ],
            'charts' => [
                'dining' => [
                    'eaten' => $totalEaten,
                    'missed' => $totalMissed,
                ],
                'rooms' => [
                    'occupied' => $activeOccupants,
                    'empty' => max(0, $totalCapacity - $activeOccupants)
                ]
            ]
        ]);
    }
}
