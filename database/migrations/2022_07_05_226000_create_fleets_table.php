<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFleetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fleets', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('city_id')->unsigned();
            $table->bigInteger('target_city_id')->unsigned();

            $table->bigInteger('fleet_task_id')->unsigned();

            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('target_city_id')->references('id')->on('cities')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('fleet_task_id')->references('id')->on('fleet_task_dictionary')->onDelete('cascade')->onUpdate('cascade');

            $table->integer('speed')->default(0);
            $table->integer('gold')->default(0);
            $table->integer('recursive')->default(0); // 0|1

            $table->integer('time')->default(0);
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
        Schema::dropIfExists('fleets');
    }
}
