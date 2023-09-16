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
            //$table->bigInteger('city_id')->unsigned();
            //$table->bigInteger('target_city_id')->unsigned();

            //$table->bigInteger('fleet_task_id')->unsigned();
            //$table->bigInteger('status_id')->unsigned();

            $table->integer('city_id');
            $table->integer('target_city_id');
            $table->integer('fleet_task_id');
            $table->integer('status_id');

            $table->integer('speed')->default(0);
            $table->integer('gold')->default(0);
            $table->integer('population')->default(0);
            $table->integer('repeating')->default(0); // 0|1

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
