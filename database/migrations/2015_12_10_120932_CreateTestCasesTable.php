<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('testcases', function (Blueprint $table) {
            $table->increments('testcase_id');
            $table->integer('pid');
            $table->integer('rank');
            $table->string('input_file_name');
            $table->string('output_file_name');
            $table->string('md5sum_input');
            $table->string('md5sum_output');
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
        Schema::drop('testcases');
    }
}
