<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContestBalloonEventTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contest_balloon_events', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('event_status'); //0 for send 1 for withdraw
            $table->integer('runid');
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
        Schema::dropIfExists('contest_balloon_events');
    }
}
