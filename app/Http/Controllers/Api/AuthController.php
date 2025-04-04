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
     *     summary="Show the authenticated user data",
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
     *                 description="Authenticated user data",
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
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", description="Error message when user is not authenticated", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", description="Error message when user's email is not verified", example="Your email address is not verified.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=429,
     *         description="Request limit exceeded",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", description="Error message when rate limit is exceeded", example="Too Many Attempts.")
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

    /**
     * @OA\Post(
     *     path="/api/auth/user/register",
     *     tags={"Auth"},
     *     summary="Register a new user",
     *     description="This endpoint registers a new user and returns the user data along with an access token.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password", "password_confirmation"},
     *             @OA\Property(property="name", type="string", description="The full name of the user", example="John Doe"),
     *             @OA\Property(property="email", type="string", description="The email address of the user", example="john@example.com"),
     *             @OA\Property(property="password", type="string", format="password", description="The user's password", example="P@ssw0rd"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", description="Password confirmation", example="P@ssw0rd")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Created",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", description="Response message", example="User registered successfully. Check your inbox for verify email :)"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 description="User data and authentication token",
     *                 @OA\Property(
     *                     property="user",
     *                     type="object",
     *                     description="User details",
     *                     @OA\Property(property="id", type="integer", description="The unique identifier of the user", example=1),
     *                     @OA\Property(property="name", type="string", description="The name of the user", example="John Doe"),
     *                     @OA\Property(property="email", type="string", description="The email of the user", example="john@example.com"),
     *                     @OA\Property(property="email_verified_at", type="string", format="date-time", description="The date when the user verified their email", example="2025-02-25T22:11:34.000000Z"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", description="The date when the user was created", example="2025-03-04T13:22:00.000000Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", description="The date when the user data was last updated", example="2025-03-04T13:22:00.000000Z"),
     *                     @OA\Property(property="role_user_id", type="integer", description="The unique identifier of the user role", example="2")
     *                 ),
     *                 @OA\Property(property="token", type="string", description="The generated access token for the user", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9...")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", description="Validation error message", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object", description="Validation errors details",
     *                 @OA\Property(property="name", type="array", description="Name validation errors",
     *                     @OA\Items(type="string", example="The name field is required.")
     *                 ),
     *                 @OA\Property(property="email", type="array", description="Email validation errors",
     *                     @OA\Items(type="email", example="The email field is required.")
     *                 ),
     *                 @OA\Property(property="password", type="array", description="Password validation errors", 
     *                     @OA\Items(type="string", example="The password field is required.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=429,
     *         description="Too many requests",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", description="Rate limit exceeded message", example="Too Many Attempts.")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/api/auth/user/login",
     *     tags={"Auth"},
     *     summary="User login",
     *     description="This endpoint logs in an existing user and returns the user data along with an access token.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", description="The email address of the user", example="john@example.com"),
     *             @OA\Property(property="password", type="string", format="password", description="The user's password", example="P@ssw0rd")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", description="Response message", example="User logged in successfully :)"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 description="Authenticated user data",
     *                 @OA\Property(
     *                     property="user",
     *                     type="object",
     *                     description="User details",
     *                     @OA\Property(property="id", type="integer", description="The unique identifier of the user", example=1),
     *                     @OA\Property(property="name", type="string", description="The name of the user", example="John Doe"),
     *                     @OA\Property(property="email", type="string", description="The email of the user", example="john@example.com"),
     *                     @OA\Property(property="email_verified_at", type="string", format="date-time", description="The date when the user verified their email", example="2025-02-25T22:09:30.000000Z"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", description="The date when the user was created", example="2025-02-25T22:09:32.000000Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", description="The date when the user data was last updated", example="2025-02-25T22:09:32.000000Z"),
     *                     @OA\Property(property="role_user_id", type="integer", description="The unique identifier of the user role", example=1)
     *                 ),
     *                 @OA\Property(property="token", type="string", description="Access token for authentication", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9...") 
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", description="Error message", example="The credentials are invalid")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", description="Validation error message", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="email", type="array", description="Email validation errors",
     *                     @OA\Items(type="email", example="The email field is required.")
     *                 ),
     *                 @OA\Property(property="password", type="array", description="Password validation errors", 
     *                     @OA\Items(type="string", example="The password field is required.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=429,
     *         description="Too many requests",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", description="Rate limit exceeded message", example="Too Many Attempts.")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/api/auth/user/logout",
     *     tags={"Auth"},
     *     security={{ "pat": {} }},
     *     summary="Logout the authenticated user",
     *     description="This endpoint logs out the authenticated user by revoking their access token.",
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", description="Response message indicating successful logout", example="Logged out successfully :)")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", description="Error message when the user is not authenticated", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=429,
     *         description="Too many requests",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", description="Error message when the request limit is exceeded", example="Too Many Attempts.")
     *         )
     *     )
     * )
     */
    public function logout(Request $request)
    {
        $user = $this->getUserFromRequest($request);

        $this->revokeCurrentToken($user);

        return $this->responseWithSuccess('Logged out successfully');
    }

    /**
     * @OA\Get(
     *     path="/api/auth/email/verify/{id}/{hash}",
     *     tags={"Auth"},
     *     summary="Verify user's email",
     *     description="This endpoint verifies a user's email using their ID and a verification hash.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The unique identifier of the user whose email is being verified",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="hash",
     *         in="path",
     *         required=true,
     *         description="A unique hash used to verify the email",
     *         @OA\Schema(type="string", example="e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855")
     *     ),
     *     @OA\Parameter(
     *         name="expires",
     *         in="query",
     *         required=true,
     *         description="The timestamp indicating when the verification link expires",
     *         @OA\Schema(type="integer", example=1741136442)
     *     ),
     *     @OA\Parameter(
     *         name="signature",
     *         in="query",
     *         required=true,
     *         description="A cryptographic signature used to verify the integrity of the URL",
     *         @OA\Schema(type="string", example="056a6465f1c22955bae514cca945ba691228442ad96d7b5bfb399378de38b8f4")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", description="Response message indicating successful email verification", example="Email successfully verified :)")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", description="Error message when the verification link is invalid", example="This link is invalid :(")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", description="Error message when the signature is invalid", example="Invalid signature.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", description="Error message when the user ID does not exist", example="This user does not exist :(")
     *         )
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="Conflict",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", description="Error message when the user's email is already verified", example="This user already has email verified :(")
     *         )
     *     ),
     *     @OA\Response(
     *         response=429,
     *         description="Too many requests",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", description="Error message when the request limit is exceeded", example="Too Many Attempts.")
     *         )
     *     )
     * )
     */
    public function verifyEmail(Request $request)
    {
        $user = $this->checkRoute($request->route('id'), $request->route('hash'));

        if ($user instanceof \Illuminate\Http\JsonResponse) {
            return $user;
        }

        $hasVerifiedEmail = $user->hasVerifiedEmail();

        if ($hasVerifiedEmail) {
            return $this->responseWithError('This user alredy has email verified', 409);
        }

        $user->markEmailAsVerified();

        return $this->responseWithSuccess('Email successfully verified');
    }

    /**
     * @OA\Post(
     *     path="/api/auth/email/resend",
     *     tags={"Auth"},
     *     summary="Resend verification email",
     *     description="This endpoint resends the verification email to the authenticated user if their email is not yet verified.",
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Email sent successfully :)")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="Conflict",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="This user already has email verified :(")
     *         )
     *     ),
     *     @OA\Response(
     *         response=429,
     *         description="Too many attempts",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Too Many Attempts.")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/api/auth/password/forgot",
     *     tags={"Auth"},
     *     summary="Send password reset email",
     *     description="This endpoint sends a password reset email to the user with the provided email address. The email will contain a link to reset the user's password.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email"},
     *             @OA\Property(property="email", type="string", description="The email address of the user who requested a password reset.", example="john@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="A password reset email has been sent :)")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="This user does not exist :(")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="email", type="array",
     *                     @OA\Items(type="email", example="The email field is required.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=429,
     *         description="Too many attempts",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Too Many Attempts.")
     *         )
     *     )
     * )
     */
    public function forgotPassword(Request $request)
    {
        $validated = $this->validateData($request, 'forgot');

        $user = $this->getUserFromEmail($validated['email']);

        if (!$user) {
            return $this->responseWithError('This user does not exist', 404);
        }

        $this->sendNotification($user, 'forgotPassword');

        return $this->responseWithSuccess('A password reset email has been sent');
    }

    /**
     * @OA\Post(
     *     path="/api/auth/password/reset/{id}/{hash}",
     *     tags={"Auth"},
     *     summary="Reset the user's password",
     *     description="Resets the password for the user identified by the provided ID and hash. The new password must meet the required criteria.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="User ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="hash",
     *         in="path",
     *         required=true,
     *         description="Password reset hash",
     *         @OA\Schema(type="string", example="e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855")
     *     ),
     *     @OA\Parameter(
     *         name="expires",
     *         in="query",
     *         required=true,
     *         description="Url expiration time",
     *         @OA\Schema(type="integer", example=1741136442)
     *     ),
     *     @OA\Parameter(
     *         name="signature",
     *         in="query",
     *         required=true,
     *         description="Signature of Url",
     *         @OA\Schema(type="string", example="056a6465f1c22955bae514cca945ba691228442ad96d7b5bfb399378de38b8f4")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"new_password", "new_password_confirmation"},
     *             @OA\Property(property="new_password", type="string", example="newStrongPassword123", description="The new password for the user"),
     *             @OA\Property(property="new_password_confirmation", type="string", example="newStrongPassword123", description="Confirmation of the new password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The password has been updated successfully :)")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="This link is invalid :(")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Invalid signature.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="This user does not exist :(")
     *         )
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="Conflict",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The new password cannot be the same as the old one :(")
     *         )
     *     ),
     *     @OA\Response(
     *         response=429,
     *         description="Too many attempts",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Too Many Attempts.")
     *         )
     *     )
     * )
     */
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
        ])->fresh();
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
                'manage-airplanes',
                'manage-flights',
                'list-all-bookings'
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
            return $this->responseWithError('This user does not exist', 404);
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
