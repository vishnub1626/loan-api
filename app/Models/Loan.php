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

    public function approve(User $user)
    {
        $this->status = 'approved';
        $this->approved_by = $user->id; 
    }

    public function reject(User $user, string $reason)
    {
        $this->status = 'rejected';
        $this->rejected_by = $user->id; 
        $this->reason_for_rejection = $reason; 
    }
}
