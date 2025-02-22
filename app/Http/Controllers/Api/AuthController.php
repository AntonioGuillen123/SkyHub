<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\ForgotPasswordAPI;
use App\Notifications\VerifyEmailAPI;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class AuthController extends Controller
{
    private const PERSONAL_ACCESS_CLIENT_NAME = 'passport.personal_access_client.name';

    public function showUser(Request $request){
        $user = $this->getUserFromRequest($request);

        $userWithRole = $this->hideRoleUser($user);

        return $this->responseWithSuccess('User show successfully', $userWithRole);
    }

    public function register(Request $request)
    {
        $validated = $this->validateData($request, 'register');

        $user = $this->createUser($validated);

        $token = $this->generateAccessToken($user);

        $this->sendNotification($user, 'verifyEmail');

        return $this->responseWithSuccess('User registered successfully. Check your inbox for verify email', [
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

        return $this->responseWithSuccess('User logged in successfully', [
            'user' => $user,
            'token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        $user = $this->getUserFromRequest($request);

        $this->revokeCurrentToken($user);

        return $this->responseWithSuccess('Logged out successfully');
    }

    public function verifyEmail(Request $request)
    {
        $user = $this->getUserFromRoute($request->route('id'));

        if (!$user) {
            return $this->responseWithError('This user not exists', 404);
        }

        $isHashCorrect = $this->checkHashFromRoute($user, $request->route('email'));

        if (!$isHashCorrect) {
            return $this->responseWithError('This link is invalid', 400);
        }

        $hasVerifiedEmail = $user->hasVerifiedEmail();

        if ($hasVerifiedEmail) {
            return $this->responseWithError('This user alredy has email verified', 409);
        }

        $user->markEmailAsVerified();

        return $this->responseWithSuccess('Email successfully verified');
    }

    public function resendEmail(Request $request)
    {
        $user = $this->getUserFromRequest($request);

        $hasVerifiedEmail = $user->hasVerifiedEmail();

        if ($hasVerifiedEmail) {
            return $this->responseWithError('This user alredy has email verified', 409);
        }

        $this->sendNotification($user, 'verifyEmail');

        return $this->responseWithSuccess('Email sent successfully');
    }

    private function hideRoleUser(User $user){
        return $user->fresh('roleUser')->makeHidden('role_user_id');
    }

    private function validateData(Request $request, string $option)
    {
        $rules = [];

        if($option === 'register'){
            $rules = [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email|max:255',
                'password' => 'required|string|min:8|confirmed'
            ];
        }

        if($option === 'login'){
            $rules = [
                'email' => 'required|email|max:255',
                'password' => 'required|string|min:8'
            ];
        }

        if($option === 'forgot'){
            $rules = [
                'email' => 'required|email|max:255'
            ];
        }

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

    private function getUserFromRequest(Request $request)
    {
        return $request->user();
    }

    private function getUserFromRoute(string $id)
    {
        return User::find($id);
    }

    private function checkHashFromRoute(User $user, string $hash)
    {
        $encryptionAlgorithm = env('MAIL_HASH', 'sha256');

        $userMailHash = hash($encryptionAlgorithm, $user->email);

        return hash_equals($userMailHash, $hash);
    }

    private function revokeTokensFromUser(User $user)
    { // Se revoca ya que así se podrá tener un registro de los tokens en la DB :)
        $user->tokens()->update(['revoked' => true]);
    }

    private function revokeCurrentToken(User $user)
    {
        $user->token()->revoke();
    }

    private function sendNotification(User $user, string $option)
    {
        $notifications = [
            'verifyEmail' => VerifyEmailAPI::class,
            'forgotPassword' => ForgotPasswordAPI::class,
        ];

        $user->notify(new $notifications[$option]($user));
    }

    private function responseWithSuccess(string $message, mixed $data = null, int $status = 200)
    {
        $response = [
            'message' => $message . ' :)'
        ];

        if (!is_null($data)) {
            $response['data'] = $data;
        }

        return response()->json($response, $status);
    }

    private function responseWithError(string $message, int $status)
    {
        return response()->json([
            'message' => $message . ' :('
        ], $status);
    }
}
