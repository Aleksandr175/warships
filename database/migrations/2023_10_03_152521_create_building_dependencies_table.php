<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('building_dependencies', function (Blueprint $table) {
            $table->id();

            $table->integer('building_id'); // The building id that requires another params
            $table->integer('building_lvl'); // The building lvl that requires another params

            $table->string('required_entity');
            $table->integer('required_entity_id')->nullable();
            $table->integer('required_entity_lvl')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('building_dependencies');
    }
};
