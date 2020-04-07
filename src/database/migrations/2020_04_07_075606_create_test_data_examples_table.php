<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestDataExamplesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_data_examples', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('test_step_id');
            $table->foreign('test_step_id')->references('id')->on('test_steps')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('uri')->nullable();
            $table->enum('method', \App\Enums\HttpMethodEnum::values())->nullable();
            $table->json('request')->nullable();
            $table->json('response')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('test_data_examples');
    }
}
