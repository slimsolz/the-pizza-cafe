<?php

namespace Database\Factories;

use App\Models\Cart;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CartFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Cart::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'cart_id' => $this->generateCartId(),
            'pizza_id' => 1,
            'size' => 'M',
            'quantity' => 2,
        ];
    }

    private function generateCartId()
    {
        return Str::random(20);
    }
}
