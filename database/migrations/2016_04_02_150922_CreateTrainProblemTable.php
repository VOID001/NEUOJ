<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrainProblemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('train_problem', function(Blueprint $table){
            $table->increments('id');
            $table->integer('train_id');
            $table->integer('problem_id');
            $table->integer('chapter_id');
            $table->integer('train_problem_id');
            $table->string('problem_title');
            $table->integer('problem_level');
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
        Schema::dropIfExists('train_problem');
    }
}
