<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResearchResourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('research_resources', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('research_id')->unsigned();
            $table->foreign('research_id')->references('id')->on('research_dictionary');

            $table->integer('gold');
            $table->integer('population');

            $table->integer('lvl');

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
        Schema::dropIfExists('research_resources');
    }
}
