<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|unique:users,email',
            'password' => ['required', Password::min(8)],
        ]);

        $user = User::create([
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return new UserResource($user);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $authenticated = Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
        ]);

        if (!$authenticated) {
            throw new AuthenticationException('Invalid credentials');
        }

        $user = Auth::user();
        $user->token = $user->createToken(now())->plainTextToken;

        return new UserResource($user);
    }
}