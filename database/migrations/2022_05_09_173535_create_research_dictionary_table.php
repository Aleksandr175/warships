<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResearchDictionaryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('research_dictionary', function (Blueprint $table) {
            $table->id();

            $table->string('title', 50);
            $table->text('description');
            $table->string('improvement_type')->nullable(); // e.g., 'attack', 'health', 'capacity'
            $table->integer('base_increment')->nullable(); // ex. 10%

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('research_dictionary');
    }
}
