<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContestInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contest_info', function (Blueprint $table) {
            $table->increments('contest_id');
            $table->string('contest_name');
            $table->dateTime('begin_time');
            $table->dateTime('end_time');
            $table->integer('admin_id');    //The person who create the contest
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
        Schema::drop('contest_info');
    }
}
