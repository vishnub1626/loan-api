<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        $response = [
            'id' => $this->id,
            'email' => $this->email,
        ];

        if ($this->token) {
            $response['token'] = $this->token;
        }

        return $response;
    }
}