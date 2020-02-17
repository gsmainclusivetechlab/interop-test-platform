<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestCasesStepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_cases_steps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('case_id');
            $table->foreign('case_id')->references('id')->on('test_cases')->onDelete('cascade');
            $table->unsignedBigInteger('step_id');
            $table->foreign('step_id')->references('id')->on('test_operations_steps')->onDelete('cascade');
            $table->longText('request_validation');
            $table->longText('response_validation');
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
        Schema::dropIfExists('test_cases_steps');
    }
}
