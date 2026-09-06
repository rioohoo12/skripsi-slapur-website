<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;

class StaffPaymentController extends Controller
{
    /**
     * Get all payments
     */
    public function index()
    {
        $payments = Payment::with('student.user')->latest()->get();
        return response()->json($payments);
    }

    /**
     * Verify or override payment status manually
     */
    public function verify(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:success,failed,pending'
        ]);

        $payment = Payment::findOrFail($id);
        $payment->status = $request->status;
        $payment->save();

        return response()->json([
            'message' => 'Status pembayaran berhasil diubah menjadi ' . $request->status,
            'payment' => $payment->load('student.user')
        ]);
    }
}
