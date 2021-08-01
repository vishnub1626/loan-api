<?php

namespace App\Http\Controllers;

use App\Events\LoanClosed;
use App\Http\Resources\LoanResource;
use App\Models\Loan;
use Illuminate\Http\Request;

class LoanInstallmentController extends Controller
{
    public function store(Request $request, Loan $loan)
    {
        if ($request->user()->cannot('repay', $loan)) {
            abort(403, "You don't have enough permissions to make installment for this this loan.");
        }

        $request->validate([
            'amount' => [
                'required',  
                'numeric', 
                function ($attribute, $value, $fail) use ($loan) {
                    if ($value * 100 != $loan->weekly_installment_amount) {
                        $fail("Amount must be equal to install ment amount");
                    }
                }     
            ]
        ]);

        $loan->installments()->create([
            'amount' => $request->amount * 100
        ]);

        $loan->decrement('installments_remaining');
        $loan->status = $loan->installments_remaining == 0 ? 'closed' : $loan->status;

        $loan->save();

        if ($loan->status == 'closed') {
            LoanClosed::dispatch($loan);
        }

        return new LoanResource($loan->fresh());
    }
}
