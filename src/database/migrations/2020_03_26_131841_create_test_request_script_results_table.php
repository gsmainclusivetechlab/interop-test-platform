<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestRequestScriptResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_request_script_results', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('test_result_id');
            $table->foreign('test_result_id')->references('test_result_id')->on('test_requests')->onDelete('cascade');
            $table->string('name');
            $table->string('status')->index();
            $table->string('message')->nullable();
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
        Schema::dropIfExists('test_request_script_results');
    }
}
