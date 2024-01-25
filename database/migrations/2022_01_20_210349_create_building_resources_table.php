<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuildingResourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('building_resources', function (Blueprint $table) {
            $table->id();

            $table->integer('building_id');
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
        Schema::dropIfExists('building_resources');
    }
}
