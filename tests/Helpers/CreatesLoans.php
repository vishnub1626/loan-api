<?php

namespace Tests\Helpers;

use App\Models\Loan;
use App\Models\User;

trait CreatesLoans
{
    public function createLoan($attributes = [])
    {
        return Loan::factory()->create(array_merge([
            'user_id' => User::factory()->create()
        ], $attributes));
    }
}
