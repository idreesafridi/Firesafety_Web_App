<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('username')->nullable();
            $table->string('address')->nullable();
            $table->string('branch')->nullable();
            $table->enum('designation', ['Super Admin', 'Branch Admin', 'Staff'])->nullable();
            $table->string('custom_designation');
            $table->string('phone_number')->unique()->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('avatar')->nullable();
            $table->enum('salary', [11,2])->nullable();
            $table->string('bank')->nullable();
            $table->string('account_no')->nullable();
            $table->string('rights')->nullable();
            $table->string('signature')->nullable();
            $table->string('Tel')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
