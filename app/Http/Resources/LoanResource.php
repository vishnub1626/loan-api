<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LoanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'amount' => $this->formatted_amount,
            'weekly_installment_amount' => $this->formatted_weekly_installment,
            'installments_remaining' => is_null($this->installments_remaining) ? null : (int) $this->installments_remaining,
            'term_in_weeks' => (int) $this->loan_term_in_weeks,
            'status' => $this->status,
            'interest_rate' => is_null($this->interest_rate) ? null : (float) $this->interest_rate,
            'reason_for_rejection' => $this->reason_for_rejection,
        ];
    }
}
