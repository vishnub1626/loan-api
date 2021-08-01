<?php

namespace Tests\Unit;

use App\Models\Loan;
use App\Models\LoanInstallment;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoanTest extends TestCase
{
    use RefreshDatabase;

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

    /** @test */
    public function a_post_has_many_comments()
    {
        $loan = Loan::factory()->create([
            'user_id' => 1
        ]);
        LoanInstallment::factory()->count(3)->create([
            'loan_id' => $loan->id,
            'amount' => 1200
        ]);

        $loan = $loan->fresh();

        $this->assertCount(3, $loan->installments);
        $this->assertInstanceOf(Collection::class, $loan->installments);

        $loan->installments->each(function ($installment) {
            $this->assertInstanceOf(LoanInstallment::class, $installment);
        });
    }
}
