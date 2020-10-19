<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Pizza;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Tests\TestCase;
use Illuminate\Support\Str;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testItCreatesAnOrder()
    {
        Pizza::factory()->hasPhoto()->create();
        $cart_id = $this->getCartId();
        Cart::factory()->create([
            'cart_id' => $cart_id,
        ]);

        $order = $this->json('POST', 'api/v1/order', [
            'cart_id' => $cart_id,
            'delivery_address' => 'lagos',
            'currency' => 'EURO',
            'zip_code' => 1234,
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'testuser@gmail.com',
            'address' => 'Lagos, Nigeria',
            'phone_number' => 1234567890
        ])
            ->assertStatus(Response::HTTP_CREATED);

        $data = Arr::get(json_decode($order->getContent(), true), 'data');

        return $data;
    }

    public function testItFailsToPlaceAnOrderWhenFieldsAreMissing()
    {
        $this->json('POST', 'api/v1/order')
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                "cart_id" => ["The cart id field is required."],
                "delivery_address" => ["The delivery address field is required."],
                "currency" => ["The currency field is required."],
                "zip_code" => ["The zip code field is required."],
            ]
        ]);
    }

    public function testItGetsOrderSummary()
    {
        $order = $this->testItCreatesAnOrder();
        $this->json('GET', 'api/v1/order/'. $order['id'])
            ->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'success' => true,
            ]);
    }

    private function getCartId()
    {
        return Str::random(20);
    }
}
