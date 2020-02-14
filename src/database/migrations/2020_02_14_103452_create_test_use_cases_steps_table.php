<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestUseCasesStepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_use_cases_steps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('use_case_id');
            $table->foreign('use_case_id')->references('id')->on('test_use_cases')->onDelete('cascade');
            $table->unsignedBigInteger('connection_id');
            $table->foreign('connection_id')->references('id')->on('test_platforms_connections')->onDelete('cascade');
            $table->string('path');
            $table->string('method');
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
        Schema::dropIfExists('test_use_cases_steps');
    }
}
