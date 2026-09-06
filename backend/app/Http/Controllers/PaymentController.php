<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use App\Models\Payment;
use App\Models\Student;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Set your Merchant Server Key
        Config::$serverKey = env('MIDTRANS_SERVER_KEY', 'SB-Mid-server-XXXXX');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        // Set sanitization on (default)
        Config::$isSanitized = env('MIDTRANS_IS_SANITIZED', true);
        // Set 3DS transaction for credit card to true
        Config::$is3ds = env('MIDTRANS_IS_3DS', true);
    }

    public function createTransaction(Request $request)
    {
        $user = $request->user();
        $student = Student::where('user_id', $user->id)->first();
        
        if (!$student) {
            return response()->json(['message' => 'Profil murid tidak ditemukan.'], 404);
        }

        // Dummy create payment record
        $payment = Payment::create([
            'student_id' => $student->id,
            'amount' => 1500000,
            'payment_date' => now(),
            'type' => 'tuition',
            'status' => 'pending'
        ]);

        $params = array(
            'transaction_details' => array(
                'order_id' => 'ORDER-' . $payment->id . '-' . time(),
                'gross_amount' => 1500000,
            ),
            'customer_details' => array(
                'first_name' => $user->name,
                'email' => $user->email,
                'phone' => $student->parent_phone ?? '08111222333',
            ),
        );

        try {
            $snapToken = Snap::getSnapToken($params);
            return response()->json(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
