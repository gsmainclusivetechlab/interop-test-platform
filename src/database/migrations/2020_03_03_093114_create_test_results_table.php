<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_results', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('test_run_id');
            $table->foreign('test_run_id')->references('id')->on('test_runs')->onDelete('cascade');
            $table->unsignedBigInteger('source_id');
            $table->unsignedBigInteger('target_id');
            $table->foreign(['source_id', 'target_id'])->references(['source_id', 'target_id'])->on('component_paths')->onDelete('cascade')->onUpdate('cascade');
            $table->longText('request');
            $table->longText('response');
            $table->unsignedInteger('total');
            $table->unsignedInteger('passed');
            $table->unsignedInteger('errors');
            $table->unsignedInteger('failures');
            $table->unsignedInteger('time');
            $table->unsignedBigInteger('position');
            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('test_results');
    }
}
