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
            $table->id();
            $table->unsignedBigInteger('test_case_id');
            $table->foreign('test_case_id')->references('id')->on('test_cases')->onDelete('cascade');
            $table->unsignedBigInteger('source_id');
            $table->unsignedBigInteger('target_id');
            $table->foreign(['source_id', 'target_id'])->references(['source_id', 'target_id'])->on('component_connections')->onDelete('cascade');
            $table->unsignedBigInteger('api_spec_id')->nullable();
            $table->foreign('api_spec_id')->references('id')->on('api_specs')->onDelete('set null');
            $table->string('path');
            $table->string('method');
            $table->string('pattern');
            $table->json('trigger');
            $table->json('request')->nullable();
            $table->json('response')->nullable();
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
