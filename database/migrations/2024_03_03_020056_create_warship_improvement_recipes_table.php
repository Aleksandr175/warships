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
        Schema::create('warship_improvement_recipes', function (Blueprint $table) {
            $table->id();
            $table->integer('warship_id');
            $table->string('improvement_type'); // e.g., 'attack', 'health', 'capacity'
            $table->integer('level');
            $table->integer('resource_id'); // ID of the resource required for improvement
            $table->integer('qty'); // Quantity of the resource required
            $table->integer('percent_improvement');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warship_improvement_recipes');
    }
};
