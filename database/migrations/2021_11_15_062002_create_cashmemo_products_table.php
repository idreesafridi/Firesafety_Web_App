<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCashmemoProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cashmemo_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('cashmemo_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('qty');
            $table->float('unit_price', 11, 2);
            $table->string('productCapacity');
            $table->integer('sequence');
            $table->timestamps();

            $table->foreign('cashmemo_id')
                  ->references('id')->on('cash_memos')
                  ->onDelete('cascade');
            $table->foreign('product_id')
                  ->references('id')->on('products')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cashmemo_products');
    }
}
