<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function processPayment(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'payment_method' => 'required|in:credit_card,paypal',
        ]);

        $order = Order::find($request->order_id);

        if ($order->status !== 'confirmed') {
            return response()->json(['message' => 'Payment can only be processed for confirmed orders.'], 400);
        }

        // Simulate payment processing for testing purposes
        $status = ['successful', 'failed'][array_rand(['successful', 'failed'])];

        $payment = Payment::create([
            'order_id' => $order->id,
            'payment_method' => $request->payment_method,
            'status' => $status,
            'transaction_id' => Str::uuid(), // Simulated transaction ID
        ]);

        return response()->json([
            'message' => 'Payment processed successfully',
            'payment' => $payment,
        ]);
    }

    public function getPayments(Request $request)
    {
        $payments = Payment::paginate(10); // Paginated
        return response()->json($payments);
    }

    public function getPaymentsByOrder($order_id)
    {
        $payments = Payment::where('order_id', $order_id)->get();
        return response()->json($payments);
    }
}
