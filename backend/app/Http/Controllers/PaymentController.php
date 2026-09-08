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

        $spp = 500000;
        $dining = 300000;
        $asrama = ($student->dormitory_preference === 'standar') ? 400000 : 200000;
        $total = $spp + $dining + $asrama;

        $payment = Payment::create([
            'student_id' => $student->id,
            'amount' => $total,
            'payment_date' => now(),
            'type' => 'tuition',
            'status' => 'pending'
        ]);

        $params = array(
            'transaction_details' => array(
                'order_id' => 'ORDER-' . $payment->id . '-' . time(),
                'gross_amount' => $total,
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

    public function notification(Request $request)
    {
        $payload = $request->all();
        $orderId = $payload['order_id'];
        $statusCode = $payload['status_code'];
        $grossAmount = $payload['gross_amount'];
        $transactionStatus = $payload['transaction_status'];
        
        // order_id format is ORDER-{payment_id}-{time}
        $parts = explode('-', $orderId);
        $paymentId = $parts[1] ?? null;
        
        if (!$paymentId) return response()->json(['message' => 'Invalid order ID'], 400);
        
        $payment = Payment::find($paymentId);
        if (!$payment) return response()->json(['message' => 'Payment not found'], 404);

        if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
            $payment->update(['status' => 'paid']);
            
            // Update Registration Progress
            $progress = \App\Models\RegistrationProgress::where('student_id', $payment->student_id)->first();
            if ($progress) {
                $progress->step0_status = 'paid';
                $progress->step1_status = 'paid';
                $progress->save();
                
                // Create Meal Card (Bagian 5)
                \App\Models\MealCard::firstOrCreate(
                    ['student_id' => $payment->student_id],
                    ['card_number' => (string)$payment->student_id]
                );
            }
            
            // Insert into Financial Transactions and calculate running balance
            $lastTransaction = \App\Models\FinancialTransaction::where('student_id', $payment->student_id)
                                ->orderBy('created_at', 'desc')
                                ->orderBy('id', 'desc')
                                ->first();
            $lastBalance = $lastTransaction ? $lastTransaction->balance : 0;
            $newBalance = $lastBalance + $payment->amount; // Pembayaran Pendaftaran is a credit/deposit
            
            \App\Models\FinancialTransaction::create([
                'student_id' => $payment->student_id,
                'date' => now()->toDateString(),
                'description' => 'Pembayaran Pendaftaran (SPP + Makan + Asrama)',
                'debit' => 0,
                'credit' => $payment->amount,
                'balance' => $newBalance
            ]);
            
        } else if ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
            $payment->update(['status' => 'failed']);
        }

        return response()->json(['message' => 'OK']);
    }
}
