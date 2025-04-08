<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id');
            $table->float('amount_recieved', 11,2);
            $table->float('wh_tax', 11,2);
            $table->float('sales_tax', 11,2);
            $table->date('dated');
            $table->unsignedBigInteger('recieved_by');
            $table->enum('payment_mode', ['Cash', 'Cheque']);
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
        Schema::dropIfExists('invoice_payments');
    }
};
