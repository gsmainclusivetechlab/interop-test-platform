<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestRunsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_runs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('session_id');
            $table->unsignedBigInteger('test_case_id');
            $table->foreign(['session_id', 'test_case_id'])->references(['session_id', 'test_case_id'])->on('test_plans')->onDelete('cascade');
            $table->timestamp('created_at');
            $table->timestamp('completed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('test_runs');
    }
}
