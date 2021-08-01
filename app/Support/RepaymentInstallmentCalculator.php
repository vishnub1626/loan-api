<?php
namespace App\Support;

use App\Models\Loan;

class RepaymentInstallmentCalculator
{
    const WEEKS_IN_A_YEAR = 52;

    public function handle(Loan $loan)
    {
        $yearlyInterest = $loan->amount * ($loan->interest_rate / 100);
        $weeklyInterest = $yearlyInterest / static::WEEKS_IN_A_YEAR;
        $interestToBePaid =  $weeklyInterest * $loan->loan_term_in_weeks;

        $weeklyInstallment = ($loan->amount + $interestToBePaid) / $loan->loan_term_in_weeks;
        
        return round($weeklyInstallment);
    }
}