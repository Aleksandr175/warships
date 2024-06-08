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
        Schema::create('message_fleet_details', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('message_id')->unsigned();

            $table->integer('warship_id')->default(1);
            $table->integer('qty')->default(0);

            $table->foreign('message_id')->references('id')->on('messages')->onDelete('cascade')->onUpdate('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('message_fleet_details');
    }
};
