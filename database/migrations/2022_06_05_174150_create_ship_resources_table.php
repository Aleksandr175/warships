<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipResourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ship_resources', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('ship_id')->unsigned();
            $table->foreign('ship_id')->references('id')->on('ship_dictionary');

            $table->integer('gold');
            $table->integer('population');

            $table->integer('time');

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
        Schema::dropIfExists('ship_resources');
    }
}
