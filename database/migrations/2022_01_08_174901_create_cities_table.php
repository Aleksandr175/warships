<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('city_dictionary_id')->unsigned()->default(1);
            $table->unsignedInteger('adventure_id')->nullable();

            $table->integer('user_id')->nullable();
            $table->string('title', 50);

            $table->integer('archipelago_id');
            $table->integer('coord_x');
            $table->integer('coord_y');

            $table->integer('appearance_id')->default(1);

            $table->smallInteger('raided')->default(0); // 1 | 0, used for adventure islands

            $table->foreign('city_dictionary_id')->references('id')->on('city_dictionary')->onDelete('cascade')->onUpdate('cascade');

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
        Schema::dropIfExists('cities');
    }
}
