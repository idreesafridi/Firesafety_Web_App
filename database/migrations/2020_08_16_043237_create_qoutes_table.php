<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qoutes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->date('dated');
            $table->text('termsConditions')->nullable();
            $table->text('other_products_name')->nullable();
            $table->text('other_products_qty')->nullable();
            $table->text('other_products_price')->nullable();
            $table->string('other_products_unit')->nullable();
            $table->string('other_products_image')->nullable();
            $table->string('attachment')->nullable();
            $table->string('subject')->nullable();
            $table->string('GST')->nullable();
            $table->float('transportaion', 11,2)->nullable();
            $table->string('customer_company_name')->nullable();
            $table->float('discount_percent', 11,2)->nullable();
            $table->float('discount_fixed', 11,2)->nullable();
            $table->timestamps();

            $table->foreign('supplier_id')
                  ->references('id')->on('suppliers')
                  ->onDelete('cascade');
            $table->foreign('customer_id')
                  ->references('id')->on('customers')
                  ->onDelete('cascade');
            $table->foreign('user_id')
                  ->references('id')->on('users')
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
        Schema::dropIfExists('qoutes');
    }
}
