<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProblemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('problems', function (Blueprint $table) {
            $table->increments('problem_id');
            $table->string('title');
            $table->boolean('is_spj');
            $table->longText('description');
            $table->integer('visibility_locks');
            $table->integer('time_limit');
            $table->integer('mem_limit');       /* in KB unit */
            $table->integer('output_limit');
            $table->integer('difficulty');
            $table->integer('author_id');
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
        Schema::dropIfExists('problems');
    }
}
