<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContestRanklistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contest_ranklist', function(Blueprint $table){
            $table->increments('id');
            $table->integer('contest_id');
            $table->integer('uid');
            $table->json('penalty_list');
            $table->string('total_penalty');
            $table->integer('total_ac');
            $table->json('result_list');
            $table->integer('rank');
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
        Schema::dropIfExists('train_users');
    }
}
