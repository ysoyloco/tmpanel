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
     *     summary="Lista wszystkich faktur",
     *     tags={"Faktury"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista faktur pobrana pomyślnie"
     *     )
     * )
     *
     * @OA\Post(
     *     path="/api/invoices",
     *     summary="Tworzenie wielu faktur",
     *     tags={"Faktury"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="invoices",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="user_id", type="integer", example=1),
     *                     @OA\Property(property="amount", type="number", format="float", example=199.99),
     *                     @OA\Property(property="status", type="string", enum={"booked", "cancelled", "processing"})
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=201, description="Faktury utworzone pomyślnie"),
     *     @OA\Response(response=422, description="Błędne dane wejściowe")
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
