<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Events\LoanClosed;
use Tests\Helpers\CreatesLoans;
use Tests\Helpers\AuthenticatesUser;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoanWeeklyInstallmentTest extends TestCase
{
    use RefreshDatabase,
        CreatesLoans,
        AuthenticatesUser;

    /** @test */
    public function guest_user_is_not_allowed_to_make_an_installment()
    {
        $loan = $this->createLoan();

        $this->postJson("api/loans/{$loan->id}/installment")
            ->assertStatus(401);
    }

    /** @test */
    public function only_user_who_applied_for_the_loan_can_make_repayment()
    {
        $user = User::factory()->create();
        $loan = $this->createLoan([
            'user_id' => $user->id
        ]);

        $anotherUser = User::factory()->create();
        $this->login($anotherUser);

        $this->postJson("api/loans/{$loan->id}/installment")
            ->assertStatus(403);
    }

    /** @test */
    public function user_can_make_a_weekly_installment()
    {
        $user = User::factory()->create();
        $loan = $this->createLoan([
            'user_id' => $user->id,
            'weekly_installment_amount' => 1200,
            'installments_remaining' => 10,
            'status' => 'approved',
            'interest_rate' => 13,
        ]);

        $this->login($user);

        $response = $this->postJson("api/loans/{$loan->id}/installment", [
            'amount' => 12.00
        ]);

        $response->assertExactJson([
            'data' => [
                'id' => $loan->id,
                'amount' => $loan->formatted_amount,
                'weekly_installment_amount' => $loan->formatted_weekly_installment,
                'installments_remaining' => 9,
                'term_in_weeks' => $loan->loan_term_in_weeks,
                'status' => 'approved',
                'interest_rate' => 13.0,
                'reason_for_rejection' => null,
            ]
        ]);

        $this->assertDatabaseHas('loan_installments', [
            'loan_id' => $loan->id,
            'amount' => 1200,
        ]);

        $loan = $loan->fresh();
        $this->assertEquals(9, $loan->installments_remaining);
    }

    /** @test */
    public function loan_will_be_marked_closed_once_all_installments_are_done()
    {
        Event::fake();

        $user = User::factory()->create();
        $loan = $this->createLoan([
            'user_id' => $user->id,
            'weekly_installment_amount' => 1200,
            'installments_remaining' => 1,
            'status' => 'approved',
            'interest_rate' => 13,
        ]);

        $this->login($user);

        $this->postJson("api/loans/{$loan->id}/installment", [
            'amount' => 12.00
        ]);

        $loan = $loan->fresh();
        $this->assertEquals(0, $loan->installments_remaining);
        $this->assertEquals('closed', $loan->status);

        Event::assertDispatched(function (LoanClosed $event) use ($loan) {
            return $event->loan->id === $loan->id;
        });
    }

    /** @test */
    public function amount_is_required_while_making_installment()
    {
        $user = User::factory()->create();
        $loan = $this->createLoan([
            'user_id' => $user->id,
            'weekly_installment_amount' => 1200,
            'installments_remaining' => 1,
            'status' => 'approved',
        ]);

        $this->login($user);

        $response = $this->postJson("api/loans/{$loan->id}/installment", []);

        $response->assertStatus(422)->assertJsonValidationErrors(['amount']);
    }

    /** @test */
    public function amount_must_be_equal_to_weekly_installment_amount()
    {
        $user = User::factory()->create();
        $loan = $this->createLoan([
            'user_id' => $user->id,
            'weekly_installment_amount' => 1200,
            'installments_remaining' => 1,
            'status' => 'approved',
        ]);

        $this->login($user);

        $response = $this->postJson("api/loans/{$loan->id}/installment", [
            'amount' => 13.00
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors(['amount']);
    }

    /** @test */
    public function cannot_make_installment_if_all_installments_are_done()
    {
        $user = User::factory()->create();
        $loan = $this->createLoan([
            'user_id' => $user->id,
            'weekly_installment_amount' => 1200,
            'installments_remaining' => 0,
            'status' => 'approved',
        ]);

        $this->login($user);

        $response = $this->postJson("api/loans/{$loan->id}/installment", [
            'amount' => 12.00
        ]);

        $response->assertStatus(403);
    }
}
