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
        Schema::create('warship_resources', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('warship_id');
            $table->unsignedBigInteger('resource_id');

            $table->integer('qty');

            $table->foreign('warship_id')->references('id')->on('warship_dictionary')->onDelete('cascade');
            $table->foreign('resource_id')->references('id')->on('resources')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warship_resources');
    }
};
