<?php

namespace Tests\Helpers;

use App\Models\Loan;
use App\Models\User;

trait CreatesLoans
{
    public function createLoan()
    {
        return Loan::factory()->create([
            'user_id' => User::factory()->create()
        ]);
    }
}
