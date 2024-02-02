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
        Schema::create('fleet_resources', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('fleet_id')->unsigned();
            $table->foreign('fleet_id')->references('id')->on('fleets');

            $table->bigInteger('resource_id')->unsigned();
            $table->foreign('resource_id')->references('id')->on('resources');

            $table->integer('qty');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fleet_resources');
    }
};
