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
            $table->bigIncrements('id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('scenario_id');
            $table->foreign('scenario_id')->references('id')->on('test_scenarios')->onDelete('cascade');
            $table->unsignedBigInteger('specification_id')->nullable();
            $table->foreign('specification_id')->references('id')->on('specifications')->onDelete('set null');
            $table->boolean('sut');
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
        Schema::dropIfExists('test_platforms');
    }
}
