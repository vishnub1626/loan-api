<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoanApplicationRequest;
use App\Http\Resources\LoanResource;
use App\Models\Loan;

class LoanController extends Controller
{
    public function store(LoanApplicationRequest $request)
    {
        $loan = Loan::create([
            'user_id' => $request->user()->id,
            'amount' => $request->amount * 100,
            'loan_term_in_months' => $request->term['years'] * 12 + $request->term['months'],
            'status' => 'pending'
        ]);

        return new LoanResource($loan);
    }
}
