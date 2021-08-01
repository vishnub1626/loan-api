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
}
