<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChallansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    { 
        Schema::create('challans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('reference_no')->nullable();
            $table->string('customer_order_no')->nullable();
            $table->date('customer_order_date')->nullable();
            $table->date('created_date')->nullable();
            $table->string('invoice_no')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->longtext('descriptions');
            $table->string('qty');
            $table->string('unit');
            $table->text('remarks'); // products descriptions
            $table->enum('type', ['Incoming', 'Delivery']);
            $table->string('productCapacity');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('sequence')->nullable();
            $table->timestamps();

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
        Schema::dropIfExists('challans');
    }
}
