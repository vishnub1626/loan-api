<?php

namespace App\Http\Controllers;

use App\Events\LoanApproved;
use App\Events\LoanRejected;
use App\Http\Resources\LoanResource;
use App\Models\Loan;
use Illuminate\Http\Request;

class LoanApprovalController extends Controller
{
    public function approve(Request $request, Loan $loan)
    {
        if ($request->user()->cannot('approve', $loan)) {
            abort(403, "You don't have enough permissions to approve this loan.");
        }

        $request->validate([
            'interest_rate' => ['required', 'numeric', 'min:0.5', 'max:25']
        ]);

        $loan->interest_rate = $request->interest_rate;
        $loan->approve($request->user());
        $loan->save();

        LoanApproved::dispatch($loan);

        return new LoanResource($loan);
    }

    public function reject(Request $request, Loan $loan)
    {
        if ($request->user()->cannot('approve', $loan)) {
            abort(403, "You don't have enough permissions to approve the this loan.");
        }

        $request->validate([
            'reason' => ['required']
        ]);

        $loan->reject($request->user(), $request->reason);
        $loan->save();

        LoanRejected::dispatch($loan);

        return new LoanResource($loan);
    }
}
