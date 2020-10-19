<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('cart_id');
            $table->integer('user_id')->nullable();
            $table->string('delivery_address');
            $table->double('delivery_fee', 8, 2)->default(1.99);
            $table->double('sub_total', 8, 2)->default(0.00);
            $table->string('currency');
            $table->integer('zip_code');
            $table->string('phone_number');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
