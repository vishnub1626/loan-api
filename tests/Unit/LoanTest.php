<?php

namespace Tests\Unit;

use App\Models\Loan;
use App\Models\User;
use Tests\TestCase;

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
    public function can_mark_loan_as_approved()
    {
        $admin = User::factory()->make([
            'is_admin' => true
        ]); 

        $loan = Loan::factory()->make([
            'status' => 'pending',
            'approved_by' => null
        ]);

        $loan->approve($admin);

        $this->assertEquals('approved', $loan->status);
        $this->assertEquals($admin->id, $loan->approved_by);
    }

    /** @test */
    public function can_mark_loan_as_rejected()
    {
        $admin = User::factory()->make([
            'is_admin' => true
        ]); 

        $loan = Loan::factory()->make([
            'status' => 'pending',
            'approved_by' => null
        ]);

        $loan->reject($admin, 'Credit score not good enough.');

        $this->assertEquals('rejected', $loan->status);
        $this->assertEquals($admin->id, $loan->rejected_by);
        $this->assertEquals('Credit score not good enough.', $loan->reason_for_rejection);
    }

    /** @test */
    public function can_return_formatted_weekly_installment()
    {
        $loan = Loan::factory()->make([
            'weekly_installment_amount' => 10000
        ]);

        $this->assertEquals('100.00', $loan->formatted_weekly_installment);
    }
}
