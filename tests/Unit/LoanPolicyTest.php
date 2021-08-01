<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Loan;
use App\Models\User;
use App\Policies\LoanPolicy;

class LoanPolicyTest extends TestCase
{
    /** @test */
    public function admin_user_can_approve_a_loan()
    {
        $admin = User::factory()->make([
            'is_admin' => true
        ]); 

        $loan = Loan::factory()->make([
            'user_id' => 1
        ]); 

        $this->assertTrue((new LoanPolicy)->approve($admin, $loan));
    }

    /** @test */
    public function non_admin_user_cannot_approve_a_loan()
    {
        $user = User::factory()->make([
            'is_admin' => false
        ]); 

        $loan = Loan::factory()->make([
            'user_id' => 1
        ]); 

        $this->assertFalse((new LoanPolicy)->approve($user, $loan));
    }

    /** @test */
    public function admin_user_can_reject_a_loan()
    {
        $admin = User::factory()->make([
            'is_admin' => true
        ]); 

        $loan = Loan::factory()->make([
            'user_id' => 1
        ]); 

        $this->assertTrue((new LoanPolicy)->reject($admin, $loan));
    }

    /** @test */
    public function non_admin_user_cannot_reject_a_loan()
    {
        $user = User::factory()->make([
            'is_admin' => false
        ]); 

        $loan = Loan::factory()->make([
            'user_id' => 1
        ]); 

        $this->assertFalse((new LoanPolicy)->reject($user, $loan));
    }

    /** @test */
    public function only_borrower_can_repay_loan()
    {
        $user = new User;
        $user->id = 1;

        $anotherUser = new User;
        $anotherUser->id = 2;

        $loan = Loan::factory()->make([
            'user_id' => $user->id,
            'installments_remaining' => 2,
        ]); 

        $this->assertTrue((new LoanPolicy)->repay($user, $loan));
        $this->assertFalse((new LoanPolicy)->repay($anotherUser, $loan));
    }

    /** @test */
    public function repay_is_possible_only_if_installments_are_remaining()
    {
        $user = new User;

        $loan = Loan::factory()->make([
            'user_id' => $user->id,
            'installments_remaining' => 0,
        ]); 

        $this->assertFalse((new LoanPolicy)->repay($user, $loan));
    }
}
