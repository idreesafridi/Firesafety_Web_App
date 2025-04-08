<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSafetyCareQouteProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('safety_care_qoute_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('quote_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('qty');
            $table->float('unit_price', 11, 2);
            $table->string('productCapacity')->nullable();
            $table->timestamps();

            // $table->foreign('quote_id')
            //       ->references('id')->on('safety_care_qoutes')
            //       ->onDelete('cascade');
            // $table->foreign('product_id')
            //       ->references('id')->on('products')
            //       ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('safety_care_qoute_products');
    }
}
