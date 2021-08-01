<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoanApprovalController;
use App\Http\Controllers\LoanInstallmentController;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/loans', [LoanController::class, 'store']);
    Route::get('/loans', [LoanController::class, 'index']);
    Route::get('/loans/{loan}', [LoanController::class, 'find']);

    Route::post('/loans/{loan}/approve', [LoanApprovalController::class, 'approve']);
    Route::post('/loans/{loan}/reject', [LoanApprovalController::class, 'reject']);

    Route::post('/loans/{loan}/installment', [LoanInstallmentController::class, 'store']);
});

Route::post('/register', [UserController::class, 'store']);
Route::post('/login', [UserController::class, 'login']);
