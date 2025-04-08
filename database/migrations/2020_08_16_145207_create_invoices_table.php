<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration 
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->unsignedBigInteger('customer_id');  
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('quote_id')->nullable();
            $table->string('invoice_no')->nullable();
            $table->string('customer_po_no')->nullable();
            $table->string('refill_notification')->nullable();
            $table->string('sales_tax_invoice')->nullable();
            $table->string('branch')->nullable();
            $table->date('dated')->nullable();
            $table->text('termsConditions')->nullable();
            $table->float('transportaion', 8,2)->nullable();
            $table->float('total_tax', 8,2)->nullable();
            $table->float('total_amount', 8,2)->nullable();
            $table->float('paid_amount', 8,2)->nullable();
            $table->float('sub_total', 8,2)->nullable();
            $table->string('expiry_date')->nullable();
            $table->string('GST')->nullable();
            $table->string('other_products_name')->nullable();
            $table->string('other_products_qty')->nullable();
            $table->string('other_products_price')->nullable();
            $table->string('other_products_unit')->nullable();
            $table->string('customer_ntn_no')->nullable();
            $table->float('discount_percent', 8,2)->nullable();
            $table->float('discount_fixed', 8,2)->nullable();
            $table->float('delievery_challan_no', 8,2)->nullable();
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
        Schema::dropIfExists('invoices');
    }
}
