<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuildingResourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('building_resources', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('building_id')->unsigned();
            $table->foreign('building_id')->references('id')->on('building_dictionary');

            $table->integer('gold');
            $table->integer('population');

            $table->integer('time');

            $table->integer('lvl');

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
        Schema::dropIfExists('building_resources');
    }
}
