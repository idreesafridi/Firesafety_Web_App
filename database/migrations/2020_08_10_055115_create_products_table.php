<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code');
            $table->unsignedBigInteger('supplier_id');
            $table->string('name');
            $table->unsignedBigInteger('category_id');
            $table->float('buying_price_per_unit_1', 11, 2)->nullable();
            $table->float('selling_price_per_unit_1', 11, 2)->nullable();
            $table->text('capacity');
            $table->text('buying_price_per_unit');
            $table->text('selling_price_per_unit');
            $table->string('unit');
            $table->string('image');
            $table->text('description');
            $table->date('dated')->nullable();
            $table->date('expiry_date')->nullable();
            $table->timestamps();

            $table->foreign('supplier_id')
                  ->references('id')->on('suppliers')
                  ->onDelete('cascade');
            $table->foreign('category_id')
                  ->references('id')->on('categories')
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
        Schema::dropIfExists('products');
    }
}
