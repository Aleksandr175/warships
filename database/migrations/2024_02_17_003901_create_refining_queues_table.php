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
        Schema::create('refining_queues', function (Blueprint $table) {
            $table->id();
            $table->integer('city_id');
            $table->integer('refining_recipe_id');
            $table->integer('input_resource_id');
            $table->integer('input_qty');
            $table->integer('output_resource_id');
            $table->integer('output_qty');

            $table->integer('time');
            $table->timestamp('deadline');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refining_queue');
    }
};
