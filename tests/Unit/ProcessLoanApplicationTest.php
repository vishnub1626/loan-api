<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Loan;
use App\Models\User;
use App\Jobs\ProcessLoanApplication;
use Illuminate\Support\Facades\Notification;
use App\Support\RepaymentInstallmentCalculator;
use App\Notifications\LoanProcessedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProcessLoanApplicationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_calculates_the_weekly_installment()
    {
        Notification::fake();

        $user = User::factory()->create();
        $loan = Loan::factory()->create([
            'user_id' => $user->id,
            'amount' => 100000,
            'interest_rate' => 12,
            'loan_term_in_weeks' => 26
        ]); 

        (new ProcessLoanApplication($loan))->handle(new RepaymentInstallmentCalculator);

        $loan = $loan->fresh();

        $this->assertEquals(4077, $loan->weekly_installment_amount);
        $this->assertEquals(26, $loan->installments_remaining);
    }

    /** @test */
    public function it_sends_a_notification_to_user()
    {
        Notification::fake();

        $user = User::factory()->create();
        $loan = Loan::factory()->create([
            'user_id' => $user->id,
            'amount' => 100000,
            'interest_rate' => 12,
            'loan_term_in_weeks' => 26
        ]); 

        (new ProcessLoanApplication($loan))->handle(new RepaymentInstallmentCalculator);
        
        Notification::assertSentTo($user, LoanProcessedNotification::class);
    }
}
