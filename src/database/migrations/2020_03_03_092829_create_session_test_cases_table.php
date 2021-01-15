<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionTestCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('session_test_cases', function (Blueprint $table) {
            $table->unsignedBigInteger('session_id');
            $table
                ->foreign('session_id')
                ->references('id')
                ->on('sessions')
                ->onDelete('cascade');
            $table->unsignedBigInteger('test_case_id');
            $table
                ->foreign('test_case_id')
                ->references('id')
                ->on('test_cases')
                ->onDelete('cascade');
            $table->primary(['session_id', 'test_case_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('session_test_cases');
    }
}
