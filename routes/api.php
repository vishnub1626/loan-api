<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\LoanApprovalController;
use App\Http\Controllers\LoanInstallmentController;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/loans', [LoanController::class, 'store']);

    Route::post('/loans/{loan}/approve', [LoanApprovalController::class, 'approve']);
    Route::post('/loans/{loan}/reject', [LoanApprovalController::class, 'reject']);

    Route::post('/loans/{loan}/installment', [LoanInstallmentController::class, 'store']);
});
