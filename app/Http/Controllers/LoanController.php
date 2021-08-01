<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Illuminate\Http\Request;
use App\Http\Resources\LoanResource;
use App\Http\Requests\LoanApplicationRequest;

class LoanController extends Controller
{
    public function index(Request $request)
    {
        $loans = Loan::query()
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return LoanResource::collection($loans);
    }

    public function find(Loan $loan)
    {
        return new LoanResource($loan);
    }

    public function store(LoanApplicationRequest $request)
    {
        $loan = Loan::create([
            'user_id' => $request->user()->id,
            'amount' => $request->amount * 100,
            'loan_term_in_weeks' => $request->term_in_weeks,
            'status' => 'pending'
        ]);

        return new LoanResource($loan);
    }
}
