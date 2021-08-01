<?php

namespace App\Listeners;

use App\Models\User;
use App\Events\LoanApproved;
use App\Notifications\LoanApprovedNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendLoanApprovedNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param  LoanApproved  $event
     * @return void
     */
    public function handle(LoanApproved $event)
    {
        $user = User::find($event->loan->user_id);

        $user->notify(new LoanApprovedNotification($event->loan));
    }
}
