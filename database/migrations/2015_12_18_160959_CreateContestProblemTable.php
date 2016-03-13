<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContestProblemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contest_problems', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('contest_id');
            $table->integer('problem_id');
            $table->integer('contest_problem_id');
            $table->string('problem_title');
            $table->integer('first_ac');
            $table->integer('problem_color');
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
        Schema::dropIfExists('contest_problems');
    }
}
