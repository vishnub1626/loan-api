<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\LoanApprovalController;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/loans', [LoanController::class, 'store']);
    Route::post('/loans/{loan}/approve', [LoanApprovalController::class, 'approve']);
    Route::post('/loans/{loan}/reject', [LoanApprovalController::class, 'reject']);
});
