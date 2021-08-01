<?php

namespace Tests\Unit;

use App\Models\Loan;
use PHPUnit\Framework\TestCase;

class LoanTest extends TestCase
{
    /** @test */
    public function can_return_formatted_loan_amount()
    {
        $loan = Loan::factory()->make([
            'amount' => 1000000
        ]);

        $this->assertEquals('10,000.00', $loan->formatted_amount);
    }

    /** @test */
    public function can_return_formatted_loan_term()
    {
        $loan = Loan::factory()->make([
            'loan_term_in_months' => 38
        ]);

        $this->assertEquals([
            'years' => 3,
            'months' => 2,
        ], $loan->formatted_term);

        $loan = Loan::factory()->make([
            'loan_term_in_months' => 24
        ]);

        $this->assertEquals([
            'years' => 2,
            'months' => 0,
        ], $loan->formatted_term);

        $loan = Loan::factory()->make([
            'loan_term_in_months' => 4
        ]);

        $this->assertEquals([
            'years' => 0,
            'months' => 4,
        ], $loan->formatted_term);
    }
}
