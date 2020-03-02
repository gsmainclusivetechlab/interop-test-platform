<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestOverridesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_overrides', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('step_id');
            $table->foreign('step_id')->references('id')->on('test_steps')->onDelete('cascade');
            $table->string('name');
            $table->string('type');
            $table->longText('values');
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
        Schema::dropIfExists('test_overrides');
    }
}
