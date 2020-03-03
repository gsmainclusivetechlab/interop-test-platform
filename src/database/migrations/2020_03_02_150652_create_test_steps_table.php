<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestStepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_steps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('case_id');
            $table->foreign('case_id')->references('id')->on('test_cases')->onDelete('cascade');
            $table->unsignedBigInteger('source_id');
            $table->unsignedBigInteger('target_id');
            $table->foreign(['source_id', 'target_id'])->references(['source_id', 'target_id'])->on('components_connections')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign(['target_id'])->references(['component_id'])->on('components_services')->onDelete('cascade');
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
        Schema::dropIfExists('test_steps');
    }
}
