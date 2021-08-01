<?php

namespace App\Listeners;

use App\Models\User;
use App\Events\LoanClosed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\LoanClosedNotification;

class SendLoanClosedNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param  LoanClosed  $event
     * @return void
     */
    public function handle(LoanClosed $event)
    {
        $user = User::find($event->loan->user_id);

        $user->notify(new LoanClosedNotification($event->loan));
    }
}
