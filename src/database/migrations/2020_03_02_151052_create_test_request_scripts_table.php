<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestRequestScriptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_request_scripts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('step_id');
            $table->foreign('step_id')->references('id')->on('test_steps')->onDelete('cascade');
            $table->string('name');
            $table->longText('rules');
            $table->longText('messages');
            $table->longText('attributes');
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
        Schema::dropIfExists('test_request_scripts');
    }
}
