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
        Schema::create('city_resources_production_coefficients', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('resource_id')->unsigned();
            $table->bigInteger('city_id')->unsigned();
            $table->decimal('coefficient', 4, 2)->unsigned()->nullable()->default(1);

            $table->foreign('resource_id')->references('id')->on('resources')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade')->onUpdate('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('city_resources_production_coefficients');
    }
};
