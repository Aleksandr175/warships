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

            $table->integer('research_id');
            $table->integer('lvl');
            $table->integer('resource_id');

            $table->integer('qty')->default(0);
            $table->integer('time_required')->default(0);

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
