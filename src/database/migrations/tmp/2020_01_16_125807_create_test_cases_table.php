<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_cases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('suite_id')->nullable();
            $table->foreign('suite_id')->references('id')->on('test_suites')->onDelete('set null');
            $table->string('name');
            $table->string('behavior');
            $table->string('description')->nullable();
            $table->string('preconditions')->nullable();
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
        Schema::dropIfExists('test_cases');
    }
}
