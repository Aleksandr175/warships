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

            $table->integer('attacker_user_id')->nullable();
            $table->integer('defender_user_id')->nullable();

            $table->integer('city_id')->nullable();
            $table->string('winner')->nullable();

            $table->integer('round')->default(1);
            $table->integer('fortressPercent')->default(0);

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
