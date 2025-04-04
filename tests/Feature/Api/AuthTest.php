<?php

namespace Tests\Feature\Api;

use App\CreatePersonalAccessClient;
use App\Models\User;
use App\Notifications\ForgotPasswordAPI;
use App\Notifications\VerifyEmailAPI;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Laravel\Passport\Passport;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase, CreatePersonalAccessClient;

    protected function setUp(): void
    {
        parent::setUp();

        $this->createPersonalAccessClient();
    }

    private function authenticate(User $user, array $scopes = [])
    {
        Passport::actingAs(
            $user,
            $scopes
        );
    }

    public function test_CheckIfShowMyUserInJsonFile()
    {
        $this->seed(DatabaseSeeder::class);

        $user = User::find(1);

        $this->authenticate($user);

        $responseData = [
            'message' => 'User show successfully :)',
            'data' => [
                'id' => 1,
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'email_verified_at' => $user->email_verified_at,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
                'role_user' => [
                    'id' => 1,
                    'name' => 'admin',
                    'created_at' => $user->roleUser->created_at,
                    'updated_at' => $user->roleUser->updated_at
                ],
            ],
        ];

        $response = $this->getJson(route('apiShowUser'));

        $response
            ->assertStatus(200)
            ->assertJsonFragment($responseData);
    }

    public function test_CheckIfICanRegisterInJsonFile()
    {
        $this->seed(DatabaseSeeder::class);

        Notification::fake();

        $requestData = [
            'name' => 'Name Test',
            'email' => 'example@example.com',
            'password' => 'P@ssw0rd',
            'password_confirmation' => 'P@ssw0rd',
        ];

        $responseData = [
            'message' =>  'User registered successfully. Check your inbox for verify email :)',
        ];

        $response = $this->postJson(route('apiRegister'), $requestData);

        $response
            ->assertStatus(201)
            ->assertJsonFragment($responseData);
    }

    public function test_CheckIfICanLoginInJsonFile()
    {
        $this->seed(DatabaseSeeder::class);

        $requestData = [
            'email' => 'john@example.com',
            'password' => 'P@ssw0rd',
        ];

        $responseData = [
            'message' =>  'User logged in successfully :)',
        ];

        $response = $this->postJson(route('apiLogin'), $requestData);

        $response
            ->assertStatus(200)
            ->assertJsonFragment($responseData);
    }

    public function test_CheckIfICanLoginWrongInJsonFile()
    {
        $this->seed(DatabaseSeeder::class);

        $requestData = [
            'email' => 'john@example.com',
            'password' => 'P@ssw0rd1',
        ];

        $responseData = [
            'message' =>  'The credentials are invalid :(',
        ];

        $response = $this->postJson(route('apiLogin'), $requestData);

        $response
            ->assertStatus(401)
            ->assertJsonFragment($responseData);
    }

    public function test_CheckIfICanLogoutInJsonFile()
    {
        $this->seed(DatabaseSeeder::class);

        $requestData = [
            'email' => 'john@example.com',
            'password' => 'P@ssw0rd',
        ];

        $response = $this->postJson(route('apiLogin'), $requestData);

        $token = $response['data']['token'];

        $requestHeader = [
            'Authorization' => 'Bearer ' . $token
        ];

        $responseData = [
            'message' =>  'Logged out successfully :)',
        ];

        $response = $this->postJson(route('apiLogout'), [], $requestHeader);

        $response
            ->assertStatus(200)
            ->assertJsonFragment($responseData);
    }

    public function test_CheckIfICanVerifyEmailInJsonFile()
    {
        $this->seed(DatabaseSeeder::class);

        Notification::fake();

        $requestData = [
            'name' => 'Name Test',
            'email' => 'example@example.com',
            'password' => 'P@ssw0rd',
            'password_confirmation' => 'P@ssw0rd',
        ];

        $response = $this->postJson(route('apiRegister'), $requestData);

        $userId = $response['data']['user']['id'];

        $user = User::find($userId);

        $user->notify(new VerifyEmailAPI($user));

        $notification = Notification::sent($user, VerifyEmailAPI::class)->first();

        $signedURL = $notification->toMail($user)->actionUrl;

        $responseData = [
            'message' =>  'Email successfully verified :)',
        ];

        $response = $this->getJson($signedURL);

        $response
            ->assertStatus(200)
            ->assertJsonFragment($responseData);
    }

    public function test_CheckIfICanVerifyEmailWrongInJsonFile()
    {
        $this->seed(DatabaseSeeder::class);

        $user = User::find(1);

        Notification::fake();

        $user->notify(new VerifyEmailAPI($user));

        $notification = Notification::sent($user, VerifyEmailAPI::class)->first();

        $signedURL = $notification->toMail($user)->actionUrl;

        $responseData = [
            'message' =>  'This user alredy has email verified :(',
        ];

        $response = $this->getJson($signedURL);

        $response
            ->assertStatus(409)
            ->assertJsonFragment($responseData);
    }

    public function test_CheckIfICanVerifyEmailWithWrongUserInJsonFile()
    {
        $this->seed(DatabaseSeeder::class);

        $user = User::find(1);

        Notification::fake();

        $user->notify(new VerifyEmailAPI($user));

        $notification = Notification::sent($user, VerifyEmailAPI::class)->first();

        $signedURL = $notification->toMail($user)->actionUrl;

        $responseData = [
            'message' =>  'This user does not exist :(',
        ];

        $user->delete();

        $response = $this->getJson($signedURL);

        $response
            ->assertStatus(404)
            ->assertJsonFragment($responseData);
    }

    public function test_CheckIfICanVerifyEmailWithWrongHashInJsonFile()
    {
        $this->seed(DatabaseSeeder::class);

        $user = User::find(1);

        Notification::fake();

        $user->notify(new VerifyEmailAPI($user));

        $notification = Notification::sent($user, VerifyEmailAPI::class)->first();

        $signedURL = $notification->toMail($user)->actionUrl;

        $responseData = [
            'message' =>  'This link is invalid :(',
        ];

        $user->email = '';
        $user->save();

        $response = $this->getJson($signedURL);

        $response
            ->assertStatus(400)
            ->assertJsonFragment($responseData);
    }

    public function test_CheckIfICanResendEmailInJsonFile()
    {
        $this->seed(DatabaseSeeder::class);

        Notification::fake();

        $requestData = [
            'name' => 'Name Test',
            'email' => 'example@example.com',
            'password' => 'P@ssw0rd',
            'password_confirmation' => 'P@ssw0rd',
        ];

        $response = $this->postJson(route('apiRegister'), $requestData);

        $userId = $response['data']['user']['id'];

        $user = User::find($userId);

        $this->authenticate($user);

        $responseData = [
            'message' =>  'Email sent successfully :)',
        ];

        $response = $this->postJson(route('apiResendEmail'));

        $response
            ->assertStatus(200)
            ->assertJsonFragment($responseData);
    }

    public function test_CheckIfICanResendEmailWrongInJsonFile()
    {
        $this->seed(DatabaseSeeder::class);

        $user = User::find(1);

        $this->authenticate($user);

        $responseData = [
            'message' =>  'This user alredy has email verified :(',
        ];

        $response = $this->postJson(route('apiResendEmail'));

        $response
            ->assertStatus(409)
            ->assertJsonFragment($responseData);
    }

    public function test_CheckIfICanForgotPasswordInJsonFile()
    {
        $this->seed(DatabaseSeeder::class);

        $requestData = [
            'email' => 'john@example.com'
        ];

        $responseData = [
            'message' =>  'A password reset email has been sent :)',
        ];

        $response = $this->postJson(route('apiForgotPassword'), $requestData);

        $response
            ->assertStatus(200)
            ->assertJsonFragment($responseData);
    }

    public function test_CheckIfICanForgotPasswordWrongInJsonFile()
    {
        $this->seed(DatabaseSeeder::class);

        $requestData = [
            'email' => 'error@example.com'
        ];

        $responseData = [
            'message' =>  'This user does not exist :(',
        ];

        $response = $this->postJson(route('apiForgotPassword'), $requestData);

        $response
            ->assertStatus(404)
            ->assertJsonFragment($responseData);
    }

    public function test_CheckIfICanResetPasswordInJsonFile()
    {
        $this->seed(DatabaseSeeder::class);

        Notification::fake();

        $requestData = [
            'new_password' => 'P@ssw0rd2',
            'new_password_confirmation' => 'P@ssw0rd2',
        ];

        $user = User::find(1);

        $user->notify(new ForgotPasswordAPI($user));

        $notification = Notification::sent($user, ForgotPasswordAPI::class)->first();

        $signedURL = $notification->toMail($user)->actionUrl;

        $responseData = [
            'message' =>  'The password has been update successfully :)',
        ];

        $response = $this->postJson($signedURL, $requestData);

        $response
            ->assertStatus(200)
            ->assertJsonFragment($responseData);
    }

    public function test_CheckIfICanResetPasswordWrongInJsonFile()
    {
        $this->seed(DatabaseSeeder::class);

        Notification::fake();

        $requestData = [
            'new_password' => 'P@ssw0rd',
            'new_password_confirmation' => 'P@ssw0rd',
        ];

        $user = User::find(1);

        $user->notify(new ForgotPasswordAPI($user));

        $notification = Notification::sent($user, ForgotPasswordAPI::class)->first();

        $signedURL = $notification->toMail($user)->actionUrl;

        $responseData = [
            'message' =>  'The new password cannot be the same as the old one :(',
        ];

        $response = $this->postJson($signedURL, $requestData);

        $response
            ->assertStatus(409)
            ->assertJsonFragment($responseData);
    }

    public function test_CheckIfICanResetPasswordWithWrongUserInJsonFile()
    {
        $this->seed(DatabaseSeeder::class);

        Notification::fake();

        $requestData = [
            'new_password' => 'P@ssw0rd2',
            'new_password_confirmation' => 'P@ssw0rd2',
        ];

        $user = User::find(1);

        $user->notify(new ForgotPasswordAPI($user));

        $notification = Notification::sent($user, ForgotPasswordAPI::class)->first();

        $signedURL = $notification->toMail($user)->actionUrl;

        $user->delete();

        $responseData = [
            'message' =>  'This user does not exist :(',
        ];

        $response = $this->postJson($signedURL, $requestData);

        $response
            ->assertStatus(404)
            ->assertJsonFragment($responseData);
    }

    public function test_CheckIfICanResetPasswordWithWrongHashInJsonFile()
    {
        $this->seed(DatabaseSeeder::class);

        Notification::fake();

        $requestData = [
            'new_password' => 'P@ssw0rd2',
            'new_password_confirmation' => 'P@ssw0rd2',
        ];

        $user = User::find(1);

        $user->notify(new ForgotPasswordAPI($user));

        $notification = Notification::sent($user, ForgotPasswordAPI::class)->first();

        $signedURL = $notification->toMail($user)->actionUrl;

        $user->email = '';
        $user->save();

        $responseData = [
            'message' =>  'This link is invalid :(',
        ];

        $response = $this->postJson($signedURL, $requestData);

        $response
            ->assertStatus(400)
            ->assertJsonFragment($responseData);
    }
}
