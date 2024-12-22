<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/invoices",
     *     summary="List all invoices",
     *     tags={"Invoices"},
     *     @OA\Response(response=200, description="List of invoices")
     * )
     * 
     * @OA\Post(
     *     path="/api/invoices/generate",
     *     summary="Generate invoice for payment",
     *     tags={"Invoices"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="payment_id", type="integer")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Invoice generated")
     * )
     */
    public function index()
    {
        return Invoice::with('user')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'invoices' => 'required|array',
            'invoices.*.user_id' => 'required|exists:users,id',
            'invoices.*.amount' => 'required|numeric',
            'invoices.*.status' => 'required|in:booked,cancelled,processing'
        ]);

        $invoices = collect($data['invoices'])->map(function ($invoiceData) {
            return Invoice::create([
                'user_id' => $invoiceData['user_id'],
                'amount' => $invoiceData['amount'],
                'status' => $invoiceData['status'],
                'received_at' => now()
            ]);
        });

        return response()->json($invoices, 201);
    }
}
