<?php

namespace Tests\Unit;

use App\Models\Loan;
use PHPUnit\Framework\TestCase;
use App\Support\RepaymentInstallmentCalculator;

class RepaymentInstallmentCalculatorTest extends TestCase
{
    /** @test */
    public function it_can_calculate_repayment_installment_amount()
    {
        $loan = Loan::factory()->make([
            'amount' => 100000,
            'interest_rate' => 12,
            'loan_term_in_weeks' => 26
        ]);
       
        $installmentCalculator = new RepaymentInstallmentCalculator;

        $weeklyInstallment = $installmentCalculator->handle($loan);

        $this->assertEquals(4077, $weeklyInstallment);
    }
}
