<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Events\LoanApproved;
use App\Events\LoanRejected;
use Tests\Helpers\CreatesLoans;
use Tests\Helpers\AuthenticatesUser;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoanApprovalTest extends TestCase
{
    use RefreshDatabase,
        CreatesLoans,
        AuthenticatesUser;

    /** @test */
    public function guest_cannot_approve_or_reject_loan_applications()
    {
        $this->postJson('api/loans/1/approve')
            ->assertStatus(401);

        $this->postJson('api/loans/1/reject')
            ->assertStatus(401);
    }

    /** @test */
    public function non_admin_cannot_approve_or_reject_loan_applications()
    {
        $loan = $this->createLoan();

        $this->login();

        $this->postJson("api/loans/{$loan->id}/approve")
            ->assertStatus(403);

        $this->postJson("api/loans/{$loan->id}/reject")
            ->assertStatus(403);
    }

    /** @test */
    public function admin_can_approve_loan_application()
    {
        Event::fake();

        $loan = $this->createLoan();

        $admin = $this->loginAsAdmin();

        $response = $this->postJson("api/loans/{$loan->id}/approve", [
            'interest_rate' => 13.00
        ]);

        $response->assertExactJson([
            'data' => [
                'id' => $loan->id,
                'amount' => $loan->formatted_amount,
                'term' => $loan->formatted_term,
                'status' => 'approved',
                'interest_rate' => 13.00,
                'reason_for_rejection' => null,
            ]
        ]);

        $loan = $loan->fresh();

        $this->assertEquals(13.00, $loan->interest_rate);
        $this->assertEquals('approved', $loan->status);
        $this->assertEquals($admin->id, $loan->approved_by);

        Event::assertDispatched(function (LoanApproved $event) use ($loan) {
            return $event->loan->id === $loan->id;
        });
    }

    /** @test */
    public function interest_rate_is_required_for_approving_loan()
    {
        $loan = $this->createLoan();

        $this->loginAsAdmin();

        $response = $this->postJson("api/loans/{$loan->id}/approve", []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['interest_rate']);
    }

    /** 
     * @test 
     * @dataProvider invalidInterestRates
     */
    public function approving_should_fail_validation_for_invalid_interest_rates($interestRate)
    {
        $loan = $this->createLoan();

        $this->loginAsAdmin();

        $response = $this->postJson("api/loans/{$loan->id}/approve", [
            'interest_rate' => $interestRate
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['interest_rate']);
    }

    /** @test */
    public function admin_can_reject_loan_application()
    {
        Event::fake();

        $loan = $this->createLoan();

        $admin = $this->loginAsAdmin();

        $response = $this->postJson("api/loans/{$loan->id}/reject", [
            'reason' => "Credit score not good enough."
        ]);

        $response->assertExactJson([
            'data' => [
                'id' => $loan->id,
                'amount' => $loan->formatted_amount,
                'term' => $loan->formatted_term,
                'status' => 'rejected',
                'interest_rate' => null,
                'reason_for_rejection' => 'Credit score not good enough.'
            ]
        ]);

        $loan = $loan->fresh();

        $this->assertEquals('rejected', $loan->status);
        $this->assertEquals($admin->id, $loan->rejected_by);
        $this->assertEquals('Credit score not good enough.', $loan->reason_for_rejection);

        Event::assertDispatched(function (LoanRejected $event) use ($loan) {
            return $event->loan->id === $loan->id;
        });
    }

    /** @test */
    public function reason_is_required_for_rejecting_a_loan_application()
    {
        $loan = $this->createLoan();

        $this->loginAsAdmin();

        $response = $this->postJson("api/loans/{$loan->id}/reject", []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['reason']);
    }

    public function invalidInterestRates()
    {
        return [
            [''],
            ['abc'],
            [0],
            [26],
            [-100],
        ];
    }
}
