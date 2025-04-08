<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSafetyCareQoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('safety_care_qoutes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->date('dated');
            $table->string('attachment')->nullable();


            $table->text('termsConditions')->nullable();

            $table->timestamps();

            $table->text('other_products_name');
            $table->text('other_products_qty');
            $table->text('other_products_price');
            $table->text('other_products_unit');
            $table->text('other_products_image')->nullable();

            $table->text('subject');
            $table->string('GST')->nullable();
            $table->float('transportaion',11,2)->nullable();
            $table->integer('productCapacity')->nullable();

            $table->float('increasePercent',11,2);

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
        Schema::dropIfExists('safety_care_qoutes');
    }
}
