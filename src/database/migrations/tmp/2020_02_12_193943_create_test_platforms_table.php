<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestPlatformsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_platforms', function (Blueprint $table) {
            $table->unsignedBigInteger('test_scenario_id');
            $table->foreign('test_scenario_id')->references('id')->on('test_scenarios')->onDelete('cascade');
            $table->unsignedBigInteger('component_id');
            $table->foreign('component_id')->references('id')->on('components')->onDelete('cascade');
            $table->primary(['test_scenario_id', 'component_id']);
            $table->unsignedBigInteger('specification_version_id')->nullable();
            $table->foreign('specification_version_id')->references('id')->on('specification_versions')->onDelete('set null');
            $table->unsignedInteger('position');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('test_platforms');
    }
}
