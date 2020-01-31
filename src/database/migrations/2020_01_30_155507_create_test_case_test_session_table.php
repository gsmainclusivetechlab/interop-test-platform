<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestCaseTestSessionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_case_test_session', function (Blueprint $table) {
            $table->unsignedBigInteger('test_case_id');
            $table->foreign('test_case_id')->references('id')->on('test_cases')->onDelete('cascade');
            $table->unsignedBigInteger('test_session_id');
            $table->foreign('test_session_id')->references('id')->on('test_sessions')->onDelete('cascade');
            $table->unsignedBigInteger('test_suite_id');
            $table->foreign('test_suite_id')->references('test_suite_id')->on('test_cases')->onDelete('cascade')->onUpdate('cascade');
            $table->primary(['test_case_id', 'test_session_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('test_case_test_session');
    }
}
