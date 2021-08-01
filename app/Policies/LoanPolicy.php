<?php

namespace App\Policies;

use App\Models\Loan;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LoanPolicy
{
    use HandlesAuthorization;

    public function approve(User $user, Loan $loan)
    {
        return $user->is_admin == true;
    }

    public function reject(User $user, Loan $loan)
    {
        return $user->is_admin == true;
    }

    public function repay(User $user, Loan $loan)
    {
        return $loan->installments_remaining > 0 && $user->id == $loan->user_id;
    }
}
