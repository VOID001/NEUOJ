<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRunningTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('runnings', function (Blueprint $table) {
            $table->increments('child_runid');
            $table->integer('runid');
            $table->integer('testcase_id');
            $table->integer('testcase_rank_id');
            $table->integer('pid');
            $table->integer('uid');
            $table->integer('cid');
            $table->double('exec_time');
            $table->integer('exec_mem');
            $table->string('lang');
            $table->string('result');
            $table->longText('err_info');
            $table->longText('output_diff');
            $table->string('assessment');
            $table->integer('judge_status'); // 0 for in queue 1 for send to judge 2 for finished
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
        Schema::drop('runnings');
    }
}
