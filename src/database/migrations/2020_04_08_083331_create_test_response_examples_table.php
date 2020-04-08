<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestResponseExamplesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_response_examples', function (Blueprint $table) {
            $table->unsignedBigInteger('test_step_id');
            $table->primary('test_step_id');
            $table->foreign('test_step_id')->references('id')->on('test_steps')
                ->onDelete('cascade');
            $table->string('status')->nullable();
            $table->longText('headers')->nullable();
            $table->longText('body')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('test_response_examples');
    }
}
