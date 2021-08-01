<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Helpers\AuthenticatesUser;

class LoanListingTest extends TestCase
{
    use RefreshDatabase,
        AuthenticatesUser;

    /** @test */
    public function a_user_can_list_his_loans()
    {
        $user = User::factory()->create();
        Loan::factory()->count(20)->create([
            'user_id' => $user->id
        ]);

        $this->login($user);

        $this->json('GET', '/api/loans')
            ->assertOk()
            ->assertJsonStructure([
                'data',
                'links',
                'meta'
            ]);
    }

    /** @test */
    public function a_user_can_get_a_single_loan()
    {
        $user = User::factory()->create();
        $loan = Loan::factory()->create([
            'user_id' => $user->id,
            'amount' => 10000,
            'weekly_installment_amount' => 1000,
            'installments_remaining' => 10,
            'status' => 'pending',
            'loan_term_in_weeks' => 10,
            'interest_rate' => 1
        ]);

        $this->login($user);

        $this->json('GET', '/api/loans/' . $loan->id)
            ->assertOk()
            ->assertExactJson([
                'data' => [
                    'id' => $loan->id,
                    'amount' => '100.00',
                    'weekly_installment_amount' => '10.00',
                    'installments_remaining' => 10,
                    'term_in_weeks' => 10,
                    'status' => 'pending',
                    'interest_rate' => 1,
                    'reason_for_rejection' => null,
                ]
            ]);
    }
}
