<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestScriptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_scripts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('test_step_id');
            $table
                ->foreign('test_step_id')
                ->references('id')
                ->on('test_steps')
                ->onDelete('cascade');
            $table->string('name');
            $table->string('type')->index();
            $table->json('rules');
            $table->json('messages')->nullable();
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
        Schema::dropIfExists('test_scripts');
    }
}
