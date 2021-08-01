<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoanController;

Route::middleware('auth:sanctum')->post('/loans', [LoanController::class, 'store']);