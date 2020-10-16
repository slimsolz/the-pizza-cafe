<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->string('cart_id');
            $table->foreignId('pizza_id');
            $table->integer('quantity')->default(1);
            $table->enum('size', ['S', 'M', 'L'])->default('S');
            $table->double('price', 8, 2)->default(0.00);
            $table->timestamps();

            $table->foreign('pizza_id')
                ->references('id')
                ->on('pizzas');

            $table->primary('cart_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carts');
    }
}
