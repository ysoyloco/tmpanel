<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
 * @OA\Get(
 *     path="/api/payments",
 *     summary="List all payments",
 *     tags={"Payments"},
 *     @OA\Response(response=200, description="List of payments")
 * )
 * 
 * @OA\Post(
 *     path="/api/payments",
 *     summary="Create payment",
 *     tags={"Payments"},
 *     @OA\RequestBody(
 *         @OA\JsonContent(
 *             @OA\Property(property="amount", type="number"),
 *             @OA\Property(property="payment_type", type="string", enum={"payu", "bank_transfer"}),
 *             @OA\Property(property="user_id", type="integer")
 *         )
 *     ),
 *     @OA\Response(response=201, description="Payment created")
 * )
 */     
    public function index()
    {
        return Payment::with('user')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'payments' => 'required|array',
            'payments.*.user_id' => 'required|exists:users,id',
            'payments.*.amount' => 'required|numeric',
            'payments.*.payment_type' => 'required|in:payu,bank_transfer',
            'payments.*.status' => 'required|in:booked,cancelled,processing'
        ]);

        $payments = collect($data['payments'])->map(function ($paymentData) {
            return Payment::create([
                'user_id' => $paymentData['user_id'],
                'amount' => $paymentData['amount'],
                'payment_type' => $paymentData['payment_type'],
                'status' => $paymentData['status'],
                'received_at' => now()
            ]);
        });

        return response()->json($payments, 201);
    }
}
