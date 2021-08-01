<?php

namespace Tests\Helpers;

use App\Models\User;
use Laravel\Sanctum\Sanctum;

trait AuthenticatesUser
{
    public function login()
    {
        $user = User::factory()->create([
            'is_admin' => false
        ]);

        Sanctum::actingAs($user);

        return $user;
    }

    public function loginAsAdmin()
    {
        $user = User::factory()->create([
            'is_admin' => true
        ]);

        Sanctum::actingAs($user);

        return $user;
    }
}
