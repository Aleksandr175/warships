<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBattleLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('battle_logs', function (Blueprint $table) {
            $table->id();

            $table->integer('battle_log_id');

            //$table->bigInteger('battle_log_detail_id')->unsigned();
            //$table->bigInteger('fleet_id')->unsigned();

            $table->integer('round');
            //$table->integer('damage');
            $table->string('type', 10); // attack or defend

            //$table->foreign('battle_log_detail_id')->references('id')->on('battle_log_details')->onDelete('cascade')->onUpdate('cascade');
            //$table->foreign('fleet_id')->references('id')->on('fleets')->onDelete('cascade')->onUpdate('cascade');

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
        Schema::dropIfExists('battle_logs');
    }
}
