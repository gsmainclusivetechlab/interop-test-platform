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
            $table->unsignedBigInteger('api_service_id')->nullable();
            $table->foreign('api_service_id')->references('id')->on('api_services')->onDelete('set null');
            $table->string('name');
            $table->text('description')->nullable();
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
