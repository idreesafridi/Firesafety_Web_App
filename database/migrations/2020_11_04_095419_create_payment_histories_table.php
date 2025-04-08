<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('invoice_id');
            $table->float('amount_paid', 8,2);
            $table->date('dated');
            $table->text('comments')->nullable();
            $table->unsignedBigInteger('recieved_by');
            $table->enum('verified', ['No', 'Yes']);
            $table->timestamps();

            $table->foreign('invoice_id')
                  ->references('id')->on('invoices')
                  ->onDelete('cascade');
            $table->foreign('recieved_by')
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
        Schema::dropIfExists('payment_histories');
    }
}
