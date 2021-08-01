<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Loan;
use App\Models\User;
use App\Events\LoanApproved;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use App\Listeners\SendLoanApprovedNotification;
use App\Notifications\LoanApprovedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SendLoanApprovedNotificationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function is_listening_to_loan_approved_event()
    {
        Event::fake();
        Event::assertListening(
            LoanApproved::class,
            SendLoanApprovedNotification::class
        ); 
    }

    /** @test */
    public function it_sends_a_notification_to_user()
    {
        Notification::fake();

        $user = User::factory()->create();

        $loan = Loan::factory()->make([
            'id' => 1,
            'user_id' => $user->id,
            'status' => 'approved',
        ]);

        $event = new LoanApproved($loan);
        $listener = new SendLoanApprovedNotification();
        $listener->handle($event);

        Notification::assertSentTo($user, LoanApprovedNotification::class);
    }
}
