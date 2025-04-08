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
        Schema::create('salaries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('month');
            $table->float('salary', 11,2);
            $table->float('over_time', 11,2);
            $table->float('night_half', 11,2);
            $table->float('night_full', 11,2);
            $table->float('dns_allounce', 11,2);
            $table->integer('medical_allounce');
            $table->float('house_rent', 11,2);
            $table->float('convence', 11,2);
            $table->float('bike_maintenance', 11,2);
            $table->float('advance',  11,2);
            $table->integer('absent_days');
            $table->float('absent_amount', 11,2);
            $table->float('half_day', 11,2);
            $table->float('ensurance', 11,2);
            $table->float('provident', 11,2);
            $table->float('professional', 11,2);
            $table->float('tax', 11,2);
            $table->float('gross_earning', 11,2);
            $table->float('total_deduction', 11,2);
            $table->float('net_salary', 11,2);
            $table->date('dated');
            $table->string('prepared_by');
            $table->timestamps();

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
        Schema::dropIfExists('salaries');
    }
};
