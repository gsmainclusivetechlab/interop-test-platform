<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestRequestExamplesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_request_examples', function (Blueprint $table) {
            $table->unsignedBigInteger('test_step_id');
            $table->primary('test_step_id');
            $table->foreign('test_step_id')->references('id')->on('test_steps')
                ->onDelete('cascade');
            $table->string('method')->nullable();
            $table->string('uri')->nullable();
            $table->longText('headers')->nullable();
            $table->longText('body')->nullable();
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
        Schema::dropIfExists('test_request_examples');
    }
}
