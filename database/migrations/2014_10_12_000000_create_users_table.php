<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->string('full_name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('no_hp');
            $table->string('saldo')->nullable();
            $table->string('alamat');
            $table->string('date_birth');
            $table->string('image');
            $table->bigInteger('role_id');
            $table->char('status', 1)->default('0');
            $table->string('popularitas')->nullable();
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
