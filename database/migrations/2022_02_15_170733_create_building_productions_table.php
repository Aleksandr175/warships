<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuildingProductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('building_productions', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('building_id')->unsigned();
            $table->foreign('building_id')->references('id')->on('buildings');

            $table->integer('lvl')->default(1);
            $table->string('resource', 50);

            $table->integer('qty')->default(0);

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
        Schema::dropIfExists('building_productions');
    }
}
