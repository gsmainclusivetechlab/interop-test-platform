<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestComponentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_components', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('test_scenario_id');
            $table->foreign('test_scenario_id')->references('id')->on('test_scenarios')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('driver');
            $table->boolean('sut');
            $table->boolean('simulated');
            $table->unsignedInteger('position');
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
        Schema::dropIfExists('test_components');
    }
}
