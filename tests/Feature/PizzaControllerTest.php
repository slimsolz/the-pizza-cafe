<?php

namespace Tests\Feature;

use App\Models\Pizza;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
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
}
