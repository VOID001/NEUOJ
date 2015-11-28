<?php

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
            $table->increments('uid');
            $table->string('username');
            $table->integer('gid');
            $table->dateTime('registration_time');
            $table->dateTime('lastlogin_time');
            $table->string('regsitration_ip');
            $table->string('lastlogin_ip');
            $table->string('password');
            $table->string('email')->unique();
            $table->string('openid')->isnullable();
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
