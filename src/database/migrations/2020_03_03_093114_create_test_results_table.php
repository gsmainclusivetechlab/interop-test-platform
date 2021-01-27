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
            $table->id();
            $table->unsignedBigInteger('test_run_id');
            $table
                ->foreign('test_run_id')
                ->references('id')
                ->on('test_runs')
                ->onDelete('cascade');
            $table->unsignedBigInteger('test_step_id');
            $table
                ->foreign('test_step_id')
                ->references('id')
                ->on('test_steps')
                ->onDelete('cascade');
            $table->json('request')->nullable();
            $table->json('response')->nullable();
            $table->text('exception')->nullable();
            $table->string('status')->index();
            $table->unsignedInteger('duration');
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
