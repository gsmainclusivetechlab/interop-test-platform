<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComponentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('components', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('scenario_id');
            $table->foreign('scenario_id')->references('id')->on('scenarios')->onDelete('cascade');
            $table->unsignedBigInteger('api_service_id')->nullable();
            $table->foreign('api_service_id')->references('id')->on('api_services')->onDelete('set null');
            $table->string('name');
            $table->text('description')->nullable();
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
        Schema::dropIfExists('components');
    }
}
