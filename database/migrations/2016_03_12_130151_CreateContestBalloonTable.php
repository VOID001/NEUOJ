<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContestBalloonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contest_balloons', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('runid');
            $table->integer('balloon_status');  //0 for send balloon 1 for AC rejudging
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
        Schema::dropIfExists('contest_balloons');
    }
}
