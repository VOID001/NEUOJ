<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrainInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('train_info', function(Blueprint $table){
            $table->increments('train_id');
            $table->string('train_name');
            $table->integer('train_chepter');
            $table->longText('description');
            $table->integer('train_type');
            $table->integer('auth_id');
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
        Schema::dropIfExists('train_info');
    }
}
