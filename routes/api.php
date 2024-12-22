<?php

Route::middleware('auth.basic')->group(function () {
    Route::apiResource('users', UserController::class)->only(['index', 'store']);
    Route::apiResource('payments', PaymentController::class)->only(['index', 'store']);
    Route::apiResource('invoices', InvoiceController::class)->only(['index', 'store']);
});
