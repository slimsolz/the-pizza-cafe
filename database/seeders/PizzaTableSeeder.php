<?php

namespace Database\Seeders;

use App\Models\Pizza;
use Illuminate\Database\Seeder;

class PizzaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Pizza::factory()->hasPhoto([
             'id' => '1',
             'name' => 'pizza1.jpeg',
             'image_url' => 'http://res.cloudinary.com/dhscfltvv/image/upload/c_fit,h_177,w_284/v1/Pizza-app/ijdr7kfnuhby7h9edjlx.png',
             'public_id' => 'Pizza-app/ijdr7kfnuhby7h9edjlx',
             'pizza_id' => '1',
             'created_at' => '2020-10-19 16:44:17',
             'updated_at' => '2020-10-19 16:44:17'
        ])->create([
            'id' => 1,
            'name' => 'perperoni  pizza',
            'description' => 'delicious',
            'size' => 'S',
            'price' => 2.50,
            'created_at' => '2020-10-19 16:44:17',
            'updated_at' => '2020-10-19 16:44:17'
        ]);

       Pizza::factory()->hasPhoto([
        'id' => '2',
        'name' => 'pizza1.jpeg',
        'image_url' => 'http://res.cloudinary.com/dhscfltvv/image/upload/c_fit,h_177,w_284/v1/Pizza-app/nset0rogfodvx4jvbsss.png',
        'public_id' => 'Pizza-app/nset0rogfodvx4jvbsss',
        'pizza_id' => '2',
        'created_at' => '2020-10-19 16:44:35',
        'updated_at' => '2020-10-19 16:44:35'
        ])->create([
            'id' => 2,
            'name' => 'perperoni cheese pizza',
            'description' => 'delicious',
            'size' => 'S',
            'price' => 1.50,
            'created_at' => '2020-10-19 16:44:35',
            'updated_at' => '2020-10-19 16:44:35'
        ]);

        Pizza::factory()->hasPhoto([
         'id' => '3',
         'name' => 'pizza-7.jpg',
         'image_url' => 'http://res.cloudinary.com/dhscfltvv/image/upload/c_fit,h_1349,w_1080/v1/Pizza-app/tldwikvqz9u2urwta7ih.png',
         'public_id' => 'Pizza-app/tldwikvqz9u2urwta7ih',
         'pizza_id' => '3',
         'created_at' => '2020-10-19 16:45:06',
         'updated_at' => '2020-10-19 16:45:06'
             ])->create([
           'id' => 3,
           'name' => 'sausage pizza',
           'description' => 'delicious',
           'size' => 'S',
           'price' => 2.99,
           'created_at' => '2020-10-19 16:45:06',
           'updated_at' => '2020-10-19 16:45:06'
        ]);

        Pizza::factory()->hasPhoto([
            'id' => '4',
            'name' => 'pizza-6.jpg',
            'image_url' => 'http://res.cloudinary.com/dhscfltvv/image/upload/c_fit,h_1067,w_1600/v1/Pizza-app/zbcacfsrqsemnafsp7ju.png',
            'public_id' => 'Pizza-app/zbcacfsrqsemnafsp7ju',
            'pizza_id' => '4',
            'created_at' => '2020-10-19 16:45:38',
            'updated_at' => '2020-10-19 16:45:38'
        ])->create([
           'id' => 4,
           'name' => 'Chicken cheese',
           'description' => 'juicy  delicious',
           'size' => 'S',
           'price' => 3.00,
           'created_at' => '2020-10-19 16:45:38',
           'updated_at' => '2020-10-19 16:45:38'
        ]);

        Pizza::factory()->hasPhoto([
            'id' => '5',
            'name' => 'pizza-5.jpg',
            'image_url' => 'http://res.cloudinary.com/dhscfltvv/image/upload/c_fit,h_1286,w_1920/v1/Pizza-app/gvwawmmdilgdmfznqyoa.png',
            'public_id' => 'Pizza-app/gvwawmmdilgdmfznqyoa',
            'pizza_id' => '5',
            'created_at' => '2020-10-19 16:46:57',
            'updated_at' => '2020-10-19 16:46:57'
        ])->create([
           'id' => 5,
           'name' => 'Extra Pepper cheese',
           'description' => 'juicy  delicious',
           'size' => 'S',
           'price' => 3.10,
           'created_at' => '2020-10-19 16:46:57',
           'updated_at' => '2020-10-19 16:46:57'
        ]);

        Pizza::factory()->hasPhoto([
            'id' => '6',
            'name' => 'pizza1.jpeg',
            'image_url' => 'http://res.cloudinary.com/dhscfltvv/image/upload/c_fit,h_177,w_284/v1/Pizza-app/ijdr7kfnuhby7h9edjlx.png',
            'public_id' => 'Pizza-app/ijdr7kfnuhby7h9edjlx',
            'pizza_id' => '6',
            'created_at' => '2020-10-19 16:44:17',
            'updated_at' => '2020-10-19 16:44:17'
       ])->create([
           'id' => 6,
           'name' => 'perperoni  pizza',
           'description' => 'delicious',
           'size' => 'S',
           'price' => 2.60,
           'created_at' => '2020-10-19 16:44:17',
           'updated_at' => '2020-10-19 16:44:17'
       ]);

       Pizza::factory()->hasPhoto([
        'id' => '7',
        'name' => 'pizza-7.jpg',
        'image_url' => 'http://res.cloudinary.com/dhscfltvv/image/upload/c_fit,h_1349,w_1080/v1/Pizza-app/tldwikvqz9u2urwta7ih.png',
        'public_id' => 'Pizza-app/tldwikvqz9u2urwta7ih',
        'pizza_id' => '7',
        'created_at' => '2020-10-19 16:45:06',
        'updated_at' => '2020-10-19 16:45:06'
            ])->create([
          'id' => 7,
          'name' => 'sausage pizza',
          'description' => 'delicious',
          'size' => 'S',
          'price' => 2.99,
          'created_at' => '2020-10-19 16:45:06',
          'updated_at' => '2020-10-19 16:45:06'
       ]);

       Pizza::factory()->hasPhoto([
        'id' => '8',
        'name' => 'pizza-7.jpg',
        'image_url' => 'http://res.cloudinary.com/dhscfltvv/image/upload/c_fit,h_1349,w_1080/v1/Pizza-app/tldwikvqz9u2urwta7ih.png',
        'public_id' => 'Pizza-app/tldwikvqz9u2urwta7ih',
        'pizza_id' => '8',
        'created_at' => '2020-10-19 16:45:06',
        'updated_at' => '2020-10-19 16:45:06'
            ])->create([
          'id' => 8,
          'name' => 'big sausage pizza',
          'description' => 'delicious',
          'size' => 'S',
          'price' => 5.99,
          'created_at' => '2020-10-19 16:45:06',
          'updated_at' => '2020-10-19 16:45:06'
       ]);
    }
}
