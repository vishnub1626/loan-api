<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Loan;
use App\Models\User;
use App\Events\LoanClosed;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use App\Listeners\SendLoanClosedNotification;
use App\Notifications\LoanClosedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SendLoanClosedNotificationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function is_listening_to_loan_closed_event()
    {
        Event::fake();
        Event::assertListening(
            LoanClosed::class,
            SendLoanClosedNotification::class
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

        $event = new LoanClosed($loan);
        $listener = new SendLoanClosedNotification();
        $listener->handle($event);

        Notification::assertSentTo($user, LoanClosedNotification::class);
    }
}
