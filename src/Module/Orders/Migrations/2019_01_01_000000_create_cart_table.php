<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_carts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('order_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->bigInteger('session_id');
            $table->smallInteger('item_count')->default(0);
            $table->json('items')->default('{}');
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
        Schema::dropIfExists('cart_carts');
    }
}
