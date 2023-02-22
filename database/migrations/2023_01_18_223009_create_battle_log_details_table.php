<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBattleLogDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('battle_log_details', function (Blueprint $table) {
            $table->id();

            $table->integer('battle_log_id');
            $table->integer('user_id')->nullable();
            $table->integer('round');
            $table->integer('warship_id');
            $table->integer('qty');
            $table->integer('destroyed');

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
        Schema::dropIfExists('battle_log_details');
    }
}
