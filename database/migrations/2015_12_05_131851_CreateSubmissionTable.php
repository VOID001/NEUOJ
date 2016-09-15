<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubmissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('submissions', function (Blueprint $table) {
            $table->increments('runid');
            $table->integer('pid');
            $table->integer('uid');
            $table->integer('cid');
            $table->dateTime('submit_time');
            $table->double('exec_time');
            $table->integer('exec_mem');
            $table->string('lang');
            $table->string('result');
            $table->integer('score');
            $table->longText('err_info');
            $table->string('assessment');
            $table->string('judgeid');
            $table->string('submit_file');
            $table->string('md5sum');
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
        Schema::dropIfExists('submissions');
    }
}
