<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\ForgotPasswordAPI;
use App\Notifications\VerifyEmailAPI;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

class AuthController extends Controller
{
    private const PERSONAL_ACCESS_CLIENT_NAME = 'passport.personal_access_client.name';

    /**
     * @OA\Get(
     *     path="/api/auth/user",
     *     tags={"Auth"},
     *     security={{ "pat": {} }},
     *     summary="Show user data",
     *     description="This endpoint returns a list of all data from authenticated User.",
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", description="Response message", example="User show successfully :)"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", description="The unique identifier of the user", example=1),
     *                 @OA\Property(property="name", type="string", description="The name of the user", example="John Doe"),
     *                 @OA\Property(property="email", type="string", description="The email of the user", example="john@example.com"),
     *                 @OA\Property(property="email_verified_at", type="string", format="date-time", description="The date when the user verified their email", example="2025-02-25T22:11:34.000000Z"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", description="The date when the user was created", example="2025-02-25T22:10:30.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", description="The date when the user data was last updated", example="2025-02-25T22:11:34.000000Z"),
     *                 @OA\Property(
     *                     property="role_user",
     *                     type="object",
     *                     description="The role assigned to the user",
     *                     @OA\Property(property="id", type="integer", description="The unique identifier of the role", example=2),
     *                     @OA\Property(property="name", type="string", description="The name of the role", example="user"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", description="The date when the role was created", example="2025-02-25T22:09:29.000000Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", description="The date when the role was last updated", example="2025-02-25T22:09:29.000000Z")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="User not authenticated",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unverified user",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Your email address is not verified.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=429,
     *         description="Request limit exceeded",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Too Many Attempts.")
     *         )
     *     )
     * )
     */
    public function showUser(Request $request)
    {
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
        $user = $this->checkRoute($request->route('id'), $request->route('hash'));

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

    public function forgotPassword(Request $request)
    {
        $validated = $this->validateData($request, 'forgot');

        $user = $this->getUserFromEmail($validated['email']);

        $this->sendNotification($user, 'forgotPassword');

        return $this->responseWithSuccess('A password reset email has been sent');
    }

    public function resetPassword(Request $request)
    {
        $user = $this->checkRoute($request->route('id'), $request->route('hash'));

        if ($user instanceof \Illuminate\Http\JsonResponse) {
            return $user;
        }

        $validated = $this->validateData($request, 'reset');

        $newPassword = $validated['new_password'];

        $passwordExists = $this->passwordExists($user, $newPassword);

        if ($passwordExists) {
            return $this->responseWithError('The new password cannot be the same as the old one', 409);
        }

        $this->revokeTokensFromUser($user);

        $this->updatePassword($user, $newPassword);

        return $this->responseWithSuccess('The password has been update successfully');
    }

    private function hideRoleUser(User $user)
    {
        return $user->fresh('roleUser')->makeHidden('role_user_id');
    }

    private function validateData(Request $request, string $option)
    {
        $rules = [
            'register' => [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email|max:255',
                'password' => 'required|string|min:8|confirmed'
            ],
            'login' => [
                'email' => 'required|email|max:255',
                'password' => 'required|string|min:8'
            ],
            'forgot' => [
                'email' => 'required|email|max:255'
            ],
            'reset' => [
                'new_password' => 'required|string|min:8|confirmed'
            ]
        ];

        return $request->validate($rules[$option]);
    }

    private function createUser(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    private function updatePassword(User $user, string $password)
    {
        $user->update([
            'password' => bcrypt($password)
        ]);
    }

    private function generateAccessToken(User $user)
    {
        $tokenName = 'User' . config(self::PERSONAL_ACCESS_CLIENT_NAME);

        $scopes = $this->chooseScopes($user);

        return $user->createToken($tokenName, $scopes)->accessToken;
    }

    private function chooseScopes(User $user)
    {
        $isAdmin = $user->hasRole('admin');

        $scopes = $isAdmin
            ? [
                'manage-airplanes'
            ]
            : [
                'make-booking',
                'cancel-booking',
                'list-flights',
                'list-bookings'
            ];

        return $scopes;
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

    private function getUserFromEmail(string $email)
    {
        return User::where('email', $email)->get()->first();
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

    private function checkRoute(string $id, string $hash)
    {
        $user = $this->getUserFromRoute($id);

        if (!$user) {
            return $this->responseWithError('This user not exists', 404);
        }

        $isHashCorrect = $this->checkHashFromRoute($user, $hash);

        if (!$isHashCorrect) {
            return $this->responseWithError('This link is invalid', 400);
        }

        return $user;
    }

    private function passwordExists(User $user, string $newPassword)
    {
        $currentPassword = $user->password;

        return Hash::check($newPassword, $currentPassword);
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
