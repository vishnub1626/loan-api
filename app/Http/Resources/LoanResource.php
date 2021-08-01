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
            'term' => $this->formatted_term,
            'status' => $this->status,
            'interest_rate' => $this->interest_rate,
            'reason_for_rejection' => $this->reason_for_rejection,
        ];
    }
}
