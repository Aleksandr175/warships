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
        Schema::create('warship_combat_multipliers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('warship_attacker_id'); // Reference to warship type for attacker
            $table->unsignedBigInteger('warship_defender_id'); // Reference to warship type for defender
            $table->float('multiplier', 3, 1); // Attack multiplier
            $table->timestamps();

            // Foreign keys assuming 'warships' as the reference table for warship types
            $table->foreign('warship_attacker_id')->references('id')->on('warship_dictionary')->onDelete('cascade');
            $table->foreign('warship_defender_id')->references('id')->on('warship_dictionary')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warship_combat_multipliers');
    }
};
