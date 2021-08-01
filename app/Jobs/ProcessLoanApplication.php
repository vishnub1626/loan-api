<?php

namespace App\Jobs;

use App\Models\Loan;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Support\RepaymentInstallmentCalculator;
use App\Notifications\LoanProcessedNotification;

class ProcessLoanApplication implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $loan;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Loan $loan)
    {
        $this->loan = $loan;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(RepaymentInstallmentCalculator $installmentCalculator)
    {
        $this->loan->weekly_installment_amount = $installmentCalculator->handle($this->loan);
        $this->loan->installments_remaining = $this->loan->loan_term_in_weeks;
        $this->loan->save();

        $user = User::find($this->loan->user_id);
        $user->notify(new LoanProcessedNotification($this->loan));
    }
}
