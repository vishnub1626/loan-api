<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoanApplicationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_user_cannot_apply_for_a_loan()
    {
        $this->postJson('api/loans')
            ->assertStatus(401);
    }

    /** @test */
    public function an_authenticated_user_can_apply_for_a_loan()
    {
        Sanctum::actingAs($user = User::factory()->create());

        $response = $this->postJson('api/loans', [
            'amount' => 10000.00,
            'term_in_weeks' => 52
        ]);

        $response->assertStatus(201)
            ->assertExactJson([
                'data' => [
                    'id' => 1,
                    'amount' => "10,000.00",
                    'weekly_installment_amount' => '0.00',
                    'installments_remaining' => null,
                    'term_in_weeks' => 52,
                    'status' => 'pending',
                    'interest_rate' => null,
                    'reason_for_rejection' => null,
                ]
            ]);

        $this->assertDatabaseHas('loans', [
            'user_id' => $user->id,
            'amount' => 1000000,
            'loan_term_in_weeks' => 52,
            'status' => 'pending'
        ]);
    }

    /** 
     * @test 
     * @dataProvider requiredFields
     */
    public function it_should_fail_validation_if_amount_is_not_provided($requiredField)
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->postJson('api/loans', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([$requiredField]);
    }

    /** 
     * @test 
     * @dataProvider invalidAmounts
     */
    public function it_should_fail_validation_for_invalid_amounts($invalidAmount)
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->postJson('api/loans', [
            'amount' => $invalidAmount,
            'term' => [
                'years' => 1,
                'months' => 0
            ]
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('amount');
    }

    /** 
     * @test 
     * @dataProvider invalidTerms
     */
    public function it_should_fail_validation_for_invalid_terms($invalidTerm)
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->postJson('api/loans', [
            'amount' => 10000.00,
            'term_in_weeks' => $invalidTerm
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('term_in_weeks');
    }

    public function requiredFields()
    {
        return [
            ['amount'],
            ['term_in_weeks'],
        ];
    }

    public function invalidAmounts()
    {
        return [
            [''],
            ['abc'],
            [0],
            [90],
            [-100],
        ];
    }

    public function invalidTerms()
    {
        return [
            [''],
            ['abc'],
            [1],
            [3],
            [-2],
            [0],
        ];
    }
}
