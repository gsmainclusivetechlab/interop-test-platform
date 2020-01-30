<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestComponentTestScenarioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_component_test_scenario', function (Blueprint $table) {
            $table->unsignedBigInteger('test_component_id');
            $table->foreign('test_component_id')->references('id')->on('test_components')->onDelete('cascade');
            $table->unsignedBigInteger('test_scenario_id');
            $table->foreign('test_scenario_id')->references('id')->on('test_scenarios')->onDelete('cascade');
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
        Schema::dropIfExists('test_component_test_scenario');
    }
}
