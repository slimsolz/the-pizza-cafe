<?php

namespace Tests\Feature;

use App\Models\User;
use Firebase\JWT\JWT;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    private $testUser = [
        'first_name' => 'Test',
        'last_name' => 'User',
        'email' => 'testuser@gmail.com',
        'password' => 'testPassword123!'
    ];

    public function testAllFieldsAreRequiredForRegistration()
    {
        $this->json('POST', 'api/v1/auth/register')
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "first_name" => ["The first name field is required."],
                    "last_name" => ["The last name field is required."],
                    "email" => ["The email field is required."],
                    "password" => ["The password field is required."],
                ]
            ]);
    }

    public function testItSuccessfullyRegisterAUser()
    {
        $this->json('POST', '/api/v1/auth/register', [
            'first_name' => $this->testUser['first_name'],
            'last_name' => $this->testUser['last_name'],
            'email' => $this->testUser['email'],
            'password' => $this->testUser['password']
        ])
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'token',
                'message',
                'data' => [
                    'id',
                    'first_name',
                    'last_name',
                    'email'
                ]
            ])
            ->assertJson([
                'message' => 'registration successful',
                'data' => [
                    'email' => $this->testUser['email']
                ]
            ]);
    }

    public function testItSuccessfullyLoginAUser()
    {
        $newUser = User::factory()->create();

        $res = $this->json('POST', '/api/v1/auth/login', [
            'email' => $newUser['email'],
            'password' => 'testPassword1$'
        ])
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'token',
                'message',
                'data' => [
                    'id',
                    'first_name',
                    'last_name',
                    'email'
                ]
            ])
            ->assertJson([
                'message' => 'login successful',
                'data' => [
                    'email' => $newUser['email']
                ]
            ]);
    }

    public function testItReturns401IfEmailIsWrong()
    {
        $this->json('POST', '/api/v1/auth/login', [
            'email' => 'wrongemail@gmail.com',
            'password' => $this->testUser['password']
        ])
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJsonStructure(['message'])
            ->assertJson([
                'message' => 'Invalid email or password'
            ]);
    }

    public function testItReturns401IfPasswordIsWrong()
    {
        $this->json('POST', '/api/v1/auth/login', [
            'email' => $this->testUser['email'],
            'password' => 'wrongPassword21!'
        ])
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJsonStructure(['message'])
            ->assertJson([
                'message' => 'Invalid email or password'
            ]);
    }

    public function testItSuccessfullyRetrievesUserProfile()
    {
        $this->withHeaders([
            'Authorization' => 'Bearer '. $this->authenticate(),
        ])
            ->json('GET', '/api/v1/profile')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'id',
                    'first_name',
                    'last_name',
                    'email',
                    'phone_number',
                    'address'
                ]
            ]);
    }

    public function testIfFailsIfAddressIsMissing()
    {
        $userDetails = [
            'phone_number' => 1234567890
        ];

        $this->withHeaders([
            'Authorization' => 'Bearer '. $this->authenticate(),
        ])
            ->json('PATCH', '/api/v1/profile', $userDetails)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "address" => ["The address field is required."],
                ]
            ]);
    }

    public function testIfFailsIfPhoneNumberIsMissing()
    {
        $userDetails = [
            'address' => 'Lagos, Nigeria'
        ];
        $this->withHeaders([
            'Authorization' => 'Bearer '. $this->authenticate(),
        ])
            ->json('PATCH', '/api/v1/profile', $userDetails)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "phone_number" => ["The phone number field is required."],
                ]
            ]);
    }

    public function testIfFailsIfPhoneNumberIsNotAString()
    {
        $userDetails = [
            'address' => 'Lagos, Nigeria',
            'phone_number' => 'testing'
        ];
        $res = $this->withHeaders([
            'Authorization' => 'Bearer '. $this->authenticate(),
        ])
            ->json('PATCH', '/api/v1/profile', $userDetails)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                "message" => "The given data was invalid.",
               "errors" => [
                    "phone_number" => ["The phone number must be a number."],
                ]
            ]);
    }

    public function testItSuccessfullyUpdatesUserProfile()
    {
        $userDetails = [
            'address' => 'Lagos, Nigeria',
            'phone_number' => 1234567890
        ];
        $this->withHeaders([
            'Authorization' => 'Bearer '. $this->authenticate(),
        ])
            ->json('PATCH', '/api/v1/profile', $userDetails)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'id',
                    'first_name',
                    'last_name',
                    'email',
                    'phone_number',
                    'address'
                ]
            ]);
    }

    //Create user and authenticate the user
    protected function authenticate(){
        $user = User::factory()->create();
        $token = $this->jwt($user);
        return $token;
    }

    protected function jwt(User $user) {
        $payload = [
            'iss' => "jwt",
            'sub' => $user->id,
            'iat' => time(),
            'exp' => time() + 60 * 60 * 24
        ];
        return JWT::encode($payload, env('JWT_SECRET'));
     }

}
