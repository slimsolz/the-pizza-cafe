<?php

namespace Database\Factories;

use App\Models\Photo;
use App\Models\Pizza;
use Illuminate\Database\Eloquent\Factories\Factory;

class PhotoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Photo::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'image_url' => $this->faker->name,
            'public_id' =>  $this->faker->name,
            'pizza_id' => Pizza::factory()
        ];
    }
}
