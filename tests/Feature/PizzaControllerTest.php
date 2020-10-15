<?php

namespace Tests\Feature;

use App\Models\Pizza;
use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class PizzaControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testItGetMenu()
    {
        Pizza::factory()->hasPhoto()->create();
        $this->get('api/v1/pizza')->assertStatus(Response::HTTP_OK);
    }

    public function testItIdentifiesAnEmptyMenu()
    {
        $this->get('api/v1/pizza')->assertStatus(Response::HTTP_NO_CONTENT);
    }

    public function testItAGetSinglePizza()
    {
        $pizza = Pizza::factory()->hasPhoto()->create();
        $this->json('GET', 'api/v1/pizza/' . $pizza->id)
            ->assertStatus(Response::HTTP_OK);
    }

    public function testItFailsToGetAGetSinglePizza()
    {
        $this->get('api/v1/pizza/5')
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson([
                "message" => "Pizza not found"
            ]);
    }

    public function testItADeleteSinglePizza()
    {
        $pizza = Pizza::factory()->hasPhoto()->create();
        $this->withHeaders([
            'Authorization' => 'Bearer '. $this->adminUser(),
        ])
            ->json('DELETE', 'api/v1/pizza/' . $pizza->id)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson([
                "message" => "deleted successfully"
            ]);
    }

    public function testItFailsDeleteNonExistingPizza()
    {
        $this->withHeaders([
            'Authorization' => 'Bearer '. $this->adminUser(),
        ])
            ->json('DELETE', 'api/v1/pizza/4')
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testItFailsDeletePizzaDueToPermission()
    {
        $this->withHeaders([
            'Authorization' => 'Bearer '. $this->regularUser(),
        ])
            ->json('DELETE', 'api/v1/pizza/4')
            ->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertJson([
                'message' => 'action not permitted',
            ]);
    }

    public function testAllFieldsAreRequiredToAppPizza()
    {
        $this->withHeaders([
            'Authorization' => 'Bearer '. $this->adminUser(),
        ])
            ->json('POST', 'api/v1/pizza')
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "name" => ["The name field is required."],
                    "description" => ["The description field is required."],
                    "price" => ["The price field is required."],
                    "size" => ["The size field is required."],
                    "image" => ["The image field is required."]
                ]
            ]);
    }

    public function testItFailsToCreateAPizzaDueToPermission()
{
    $this->withHeaders([
        'Authorization' => 'Bearer '. $this->regularUser(),
    ])
        ->json('POST', 'api/v1/pizza', [
            "name" => "test",
            "description" => "sweet",
            "price" => 4.00,
            "size" => "S",
            "image" => UploadedFile::fake()->image('avatar.jpg')
        ])
        ->assertStatus(Response::HTTP_FORBIDDEN)
        ->assertJson([
            'message' => 'action not permitted',
        ]);
}

    public function testItSuccessfullyCreateAPizza()
    {
        $this->withHeaders([
            'Authorization' => 'Bearer '. $this->adminUser(),
        ])
            ->json('POST', 'api/v1/pizza', [
                "name" => "test",
                "description" => "sweet",
                "price" => 4.00,
                "size" => "S",
                "image" => UploadedFile::fake()->image('avatar.jpg')
            ])
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'id',
                    'name',
                    'size',
                    'price',
                    'description',
                    'images_url'
                ]
            ])
            ->assertJson([
                'message' => 'pizza successfully added',
            ]);
    }

    // Create and authenticate an admin user
    protected function adminUser(){
        $user = User::factory()->create(["isAdmin" => true]);
        $token = $this->jwt($user);
        return $token;
    }

    // Create and authenticate a regular user
    protected function regularUser(){
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
