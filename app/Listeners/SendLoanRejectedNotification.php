<?php

namespace App\Listeners;

use App\Models\User;
use App\Events\LoanRejected;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\LoanRejectedNotification;

class SendLoanRejectedNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param  LoanRejected  $event
     * @return void
     */
    public function handle(LoanRejected $event)
    {
        $user = User::find($event->loan->user_id);

        $user->notify(new LoanRejectedNotification($event->loan));
    }
}
