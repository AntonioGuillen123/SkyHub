<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private const PERSONAL_ACCESS_CLIENT_NAME = 'passport.personal_access_client.name';

    public function register(Request $request)
    {
        $validated = $this->validateData($request, 'register');

        $user = $this->createUser($validated);

        $token = $this->generateAccessToken($user);

        return $this->responseWithSuccess([
            'user' => $user,
            'token' => $token
        ], 201);
    }

    private function validateData(Request $request, string $option)
    {
        $rules = $option === 'register'
            ? [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email|max:255',
                'password' => 'required|string|min:8|confirmed'
            ]
            : [
                'email' => 'required|email|max:255',
                'password' => 'required|string|min:8'
            ];

        return $request->validate($rules);
    }

    private function createUser(mixed $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    private function generateAccessToken(User $user)
    {
        $tokenName = 'User' . config(self::PERSONAL_ACCESS_CLIENT_NAME);

        $scopes = [
            'make-booking',
            'cancel-booking',
            'list-flights',
            'list-bookings'
        ];

        return $user->createToken($tokenName, $scopes)->accessToken;
    }

    private function responseWithSuccess(mixed $data, int $status = 200)
    {
        return response()->json($data, $status);
    }
}
