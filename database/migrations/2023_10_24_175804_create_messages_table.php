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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');

            $table->text('content');
            $table->integer('template_id')->default(1);
            $table->boolean('is_read')->default(false);
            $table->string('event_type')->nullable();
            $table->unsignedBigInteger('archipelago_id')->nullable();
            $table->integer('coord_x')->nullable();
            $table->integer('coord_y')->nullable();

            $table->integer('city_id')->nullable();
            $table->integer('target_city_id')->nullable();

            $table->integer('gold')->nullable();
            $table->integer('population')->nullable();

            $table->unsignedBigInteger('battle_log_id')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('battle_log_id')->references('id')->on('battle_logs')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
