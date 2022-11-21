<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWarshipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warships', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('warship_id')->unsigned();
            $table->bigInteger('city_id')->unsigned();
            $table->bigInteger('user_id')->unsigned()->nullable();

            $table->integer('qty')->default(0);

            $table->foreign('warship_id')->references('id')->on('warship_dictionary')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

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
        Schema::dropIfExists('warships');
    }
}
