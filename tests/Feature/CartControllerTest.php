<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Pizza;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Support\Str;

class CartControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testItCreatesCartId()
    {
        $this->get('api/v1/cart/uniqueId')->assertStatus(Response::HTTP_OK);
    }

    public function testPizzaGetsAddedToCart()
    {
        $cart_id = $this->getCartId();
        $pizza = Pizza::factory()->hasPhoto()->create();
        $this->post('api/v1/cart/'.$pizza->id, [
            'cart_id' => $cart_id,
            'quantity' => rand(1, 5),
            'size' => 'M',
            'price' => 1.20
        ])
            ->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'message' => 'added to cart',
            ]);
    }

    public function testItFailsToAddToCartWhenFieldsAreMissing()
    {
        $pizza = Pizza::factory()->hasPhoto()->create();
        $this->json('POST', 'api/v1/cart/' . $pizza->id)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                        "cart_id" => ["The cart id field is required."],
                        "quantity" => ["The quantity field is required."],
                        "size" => ["The size field is required."],
                    ]
                ]);
    }

    public function testItFailsToItemInCartWhenCartIdIsNotFound()
    {
        $pizza = Pizza::factory()->hasPhoto()->create();
        $this->json('PATCH', 'api/v1/cart/' . $this->getCartId() . '/' . $pizza->id)
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson(["message" => "not found"]);
    }

    public function testItFailsToItemInCartWhenPizzaIdIsNotFound()
    {
        $this->json('PATCH', 'api/v1/cart/' . $this->getCartId() . '/2')
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson(["message" => "not found"]);
    }

    public function testItFailsToItemInCartWhenFieldsAreMissing()
    {
        $pizza = Pizza::factory()->hasPhoto()->create();
        $cart_id = $this->getCartId();
        Cart::factory()->create([
            'cart_id' => $cart_id,
        ]);
        $this->json('PATCH', 'api/v1/cart/' . $cart_id .'/'. $pizza->id, [
            'size' => 'L',
            'quantity' => 3
        ])
            ->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'message' => 'successfully updated',
            ]);
    }

    public function testItFailsToRemoveItemFromCart()
    {
        $pizza = Pizza::factory()->hasPhoto()->create();
        $cart_id = $this->getCartId();
        $this->json('DELETE', 'api/v1/cart/' . $cart_id .'/'. $pizza->id)
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testItRemovesAnItemFromCart()
    {
        $pizza = Pizza::factory()->hasPhoto()->create();
        $cart_id = $this->getCartId();
        Cart::factory()->create([
            'cart_id' => $cart_id,
        ]);
        $this->json('DELETE', 'api/v1/cart/' . $cart_id .'/'. $pizza->id)
            ->assertStatus(Response::HTTP_NO_CONTENT);
    }

    public function testItFailsToEmptiesTheCart()
    {
        $cart_id = $this->getCartId();
        $this->json('DELETE', 'api/v1/cart/' . $cart_id)
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testItEmptiesTheCart()
    {
        Pizza::factory()->hasPhoto()->create();
        $cart_id = $this->getCartId();
        Cart::factory()->create([
            'cart_id' => $cart_id,
        ]);
        $this->json('DELETE', 'api/v1/cart/' . $cart_id)
            ->assertStatus(Response::HTTP_NO_CONTENT);
    }

    public function testIfFailsToGetTotalIfCartDoesNotExist()
    {
        $cart_id = $this->getCartId();
        $this->json('GET', 'api/v1/cart/total/' . $cart_id)
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testIfGetsTotalInTheCart()
    {
        $cart_id = $this->getCartId();
        Cart::factory()->create([
            'cart_id' => $cart_id,
        ]);
        $this->json('GET', 'api/v1/cart/total/' . $cart_id)
            ->assertStatus(Response::HTTP_OK);
    }

    public function testItViewCart()
    {
        $cart_id = $this->getCartId();
        Cart::factory()->create([
            'cart_id' => $cart_id,
        ]);
        $this->json('GET', 'api/v1/cart/' . $cart_id)
            ->assertStatus(Response::HTTP_OK);
    }

    private function getCartId()
    {
        return Str::random(20);
    }
}
