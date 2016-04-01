<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('userinfo', function (Blueprint $table) {
            $table->increments("info_id");
            $table->integer("uid")->unique();
            $table->string("nickname");
            $table->string("realname");
            $table->string("school");
            $table->string("stu_id");
            $table->integer("submit_count");
            $table->integer("ac_count");
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
        Schema::dropIfExists("userinfo");
    }
}
