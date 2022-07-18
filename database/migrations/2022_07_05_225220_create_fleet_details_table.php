<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFleetDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fleet_details', function (Blueprint $table) {
            $table->id();

            $table->integer('fleet_id')->default(0);

            $table->bigInteger('warship_id')->unsigned();
            $table->foreign('warship_id')->references('id')->on('warship_dictionary')->onDelete('cascade')->onUpdate('cascade');

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
        Schema::dropIfExists('fleet_details');
    }
}
