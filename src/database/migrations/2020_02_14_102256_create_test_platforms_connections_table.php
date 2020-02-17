<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestPlatformsConnectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_platforms_connections', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('scenario_id');
            $table->foreign('scenario_id')->references('id')->on('test_scenarios')->onDelete('cascade');
            $table->unsignedBigInteger('source_id');
            $table->foreign('source_id')->references('id')->on('test_platforms')->onDelete('cascade');
            $table->unsignedBigInteger('target_id');
            $table->foreign('target_id')->references('id')->on('test_platforms')->onDelete('cascade');
            $table->string('connection');
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
        Schema::dropIfExists('test_platforms_connections');
    }
}
