<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserControllerTest extends TestCase
{
    // use RefreshDatabase;

    private $testUser = [
        'first_name' => 'Test',
        'last_name' => 'User',
        'email' => 'testuser@gmail.com',
        'password' => 'testPassword123!'
    ];

    public function testFieldsAreRequiredForRegistration()
    {
        $this->json('POST', 'api/v1/auth/register')
            ->assertStatus(422)
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
        $this->withoutExceptionHandling();
        $this->json('POST', '/api/v1/auth/register', [
            'first_name' => $this->testUser['first_name'],
            'last_name' => $this->testUser['last_name'],
            'email' => $this->testUser['email'],
            'password' => $this->testUser['password']
        ])
            ->assertStatus(201)
            ->assertJsonStructure(['token', 'message', 'data' => ['id', 'first_name', 'last_name', 'email']])
            ->assertJson([
                'message' => 'registration successful',
                'data' => [
                    'email' => $this->testUser['email']
                ]
            ]);
    }

    public function testItSuccessfullyLoginAUser()
    {
        $this->withoutExceptionHandling();
        $this->json('POST', '/api/v1/auth/login', [
            'email' => $this->testUser['email'],
            'password' => $this->testUser['password']
        ])
            ->assertStatus(200)
            ->assertJsonStructure(['token', 'message', 'data' => ['id', 'first_name', 'last_name', 'email']])
            ->assertJson([
                'message' => 'login successful',
                'data' => [
                    'email' => $this->testUser['email']
                ]
            ]);
    }

    public function testItReturns401IfEmailIsWrong()
    {
        $this->json('POST', '/api/v1/auth/login', [
            'email' => 'wrongemail@gmail.com',
            'password' => $this->testUser['password']
        ])
            ->assertStatus(401)
            ->assertJsonStructure(['message'])
            ->assertJson([
                'message' => 'Invalid email or password'
            ]);
    }

    public function testItReturns401IfPasswordIsWrong()
    {
        $response = $this->json('POST', '/api/v1/auth/login', [
            'email' => $this->testUser['email'],
            'password' => 'wrongPassword21!'
        ])
            ->assertStatus(401)
            ->assertJsonStructure(['message'])
            ->assertJson([
                'message' => 'Invalid email or password'
            ]);
    }
}
