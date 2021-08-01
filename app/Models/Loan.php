<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount / 100, 2);
    }

    public function getFormattedTermAttribute()
    {
        return [
            'years' => floor($this->loan_term_in_months / 12),
            'months' => $this->loan_term_in_months % 12,
        ];
    }
}
