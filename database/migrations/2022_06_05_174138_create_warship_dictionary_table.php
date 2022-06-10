<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWarshipDictionaryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warship_dictionary', function (Blueprint $table) {
            $table->id();

            $table->string('title', 50);
            $table->text('description');

            $table->integer('attack');
            $table->integer('speed');
            $table->integer('capacity');
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
        Schema::dropIfExists('warship_dictionary');
    }
}
