<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Loan;
use App\Models\User;
use App\Events\LoanRejected;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use App\Listeners\SendLoanRejectedNotification;
use App\Notifications\LoanRejectedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SendLoanRejectedNotificationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function is_listening_to_loan_rejected_event()
    {
        Event::fake();
        Event::assertListening(
            LoanRejected::class,
            SendLoanRejectedNotification::class
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

        $event = new LoanRejected($loan);
        $listener = new SendLoanRejectedNotification();
        $listener->handle($event);

        Notification::assertSentTo($user, LoanRejectedNotification::class);
    }
}
