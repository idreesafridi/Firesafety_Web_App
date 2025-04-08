<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCashMemosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_memos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('quote_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('customer_order_no')->nullable();
            $table->date('customer_order_date')->nullable();
            $table->date('created_date')->nullable();
            $table->string('reference_no')->nullable();
            $table->longtext('descriptions');
            $table->string('qty');
            $table->string('unit_price');
            $table->string('productCapacity')->nullable();
            $table->string('total_amounts');
            $table->string('sequence')->nullable();
            $table->timestamps();

            $table->foreign('customer_id') 
                  ->references('id')->on('customers')
                  ->onDelete('cascade');
            $table->foreign('quote_id')
                  ->references('id')->on('qoutes')
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
        Schema::dropIfExists('cash_memos');
    }
}
