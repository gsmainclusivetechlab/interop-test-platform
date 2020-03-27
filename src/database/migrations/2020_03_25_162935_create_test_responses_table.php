<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_responses', function (Blueprint $table) {
            $table->unsignedBigInteger('test_result_id');
            $table->foreign('test_result_id')->references('id')->on('test_results')->onDelete('cascade');
            $table->primary('test_result_id');
            $table->unsignedInteger('status');
            $table->longText('headers');
            $table->longText('body');
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
        Schema::dropIfExists('test_responses');
    }
}
