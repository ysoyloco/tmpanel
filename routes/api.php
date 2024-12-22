<?php

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('helpdesk', HelpdeskController::class);
    Route::post('helpdesk/{subject}/reply', [HelpdeskController::class, 'reply']);
    
    Route::apiResource('payments', PaymentController::class);
    Route::apiResource('invoices', InvoiceController::class);
    Route::apiResource('users', UserController::class);
});