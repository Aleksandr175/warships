<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWarshipQueuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warship_queues', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');

            $table->bigInteger('city_id')->unsigned();
            $table->foreign('city_id')->references('id')->on('cities');

            $table->bigInteger('warship_id')->unsigned();
            $table->foreign('warship_id')->references('id')->on('warship_dictionary');

            $table->integer('qty');

            $table->integer('time');
            $table->timestamp('deadline');

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
        Schema::dropIfExists('warship_queues');
    }
}
