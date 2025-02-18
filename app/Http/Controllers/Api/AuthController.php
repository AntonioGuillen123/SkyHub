<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function login(Request $request)
    {
        $validated = $this->validateData($request, 'login');

        $isLogged = $this->authenticate($validated);

        if (!$isLogged) {
            return $this->responseWithError('The credentials are invalid', 401);
        }

        $user = $this->getUserFromAuth();

        $this->revokeTokensFromUser($user);

        $token = $this->generateAccessToken($user);

        return $this->responseWithSuccess([
            'user' => $user,
            'token' => $token
        ], 200);
    }

    private function authenticate(array $data)
    {
        return Auth::attempt(
            [
                'email' => $data['email'],
                'password' => $data['password']
            ]
        );
    }

    private function getUserFromAuth()
    {
        return Auth::user();
    }

    private function revokeTokensFromUser(User $user)
    {// Se revoca ya que así se podrá tener un registro de los tokens en la DB :)
        $user->tokens()->update(['revoked' => true]);
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

    private function createUser(array $data)
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

    private function responseWithError(string $message, int $status)
    {
        return response()->json([
            'message' => $message . ' :('
        ], $status);
    }
}
