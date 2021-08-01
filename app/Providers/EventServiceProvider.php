<?php

namespace App\Providers;

use App\Events\LoanApproved;
use App\Events\LoanClosed;
use App\Events\LoanRejected;
use App\Listeners\SendLoanApprovedNotification;
use App\Listeners\SendLoanClosedNotification;
use App\Listeners\SendLoanRejectedNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        LoanApproved::class => [
            SendLoanApprovedNotification::class,
        ],

        LoanRejected::class => [
            SendLoanRejectedNotification::class,
        ],

        LoanClosed::class => [
            SendLoanClosedNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
