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
            $table->unsignedBigInteger('run_id');
            $table->foreign('run_id')->references('id')->on('test_runs')->onDelete('cascade');
            $table->unsignedBigInteger('step_id');
            $table->foreign('step_id')->references('id')->on('test_cases_steps')->onDelete('cascade');
            $table->text('result')->nullable();
            $table->longText('request')->nullable();
            $table->longText('response')->nullable();
            $table->string('status');
            $table->unsignedInteger('time');
            $table->unsignedInteger('position');
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
