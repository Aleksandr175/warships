<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // table provides info about how many slots available for some lvl of some building
        Schema::create('building_queue_slots', function (Blueprint $table) {
            $table->id();

            $table->integer('building_id');
            $table->integer('building_lvl');
            $table->integer('slots');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('building_queue_slots');
    }
};
