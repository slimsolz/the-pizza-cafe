<?php

namespace Tests\Feature;

use App\Models\Pizza;
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
        $this->json('GET', 'api/v1/pizza')
            ->assertStatus(Response::HTTP_OK);
    }

    public function testItIdentifiesAnEmptyMenu()
    {
        $this->json('GET', 'api/v1/pizza')
            ->assertStatus(Response::HTTP_NO_CONTENT);
    }

    public function testItAGetSinglePizza()
    {
        $pizza = Pizza::factory()->hasPhoto()->create();
        $this->json('GET', 'api/v1/pizza/' . $pizza->id)
            ->assertStatus(Response::HTTP_OK);
    }

    public function testItFailsToGetAGetSinglePizza()
    {
        $pizza = Pizza::factory()->hasPhoto()->create();
        $res = $this->json('GET', 'api/v1/pizza/5')
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson([
                "message" => "Pizza not found"
            ]);
    }

    public function testItADeleteSinglePizza()
    {
        $pizza = Pizza::factory()->hasPhoto()->create();
        $this->json('DELETE', 'api/v1/pizza/' . $pizza->id)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson([
                "message" => "deleted successfully"
            ]);
    }

    public function testItFailsDeleteNonExistingPizza()
    {
        $this->json('DELETE', 'api/v1/pizza/4')
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testAllFieldsAreRequiredToAppPizza()
    {
        $this->json('POST', 'api/v1/pizza')
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

    public function testItSuccessfullyCreateAPizza()
    {
        $this->json('POST', 'api/v1/pizza', [
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
}
