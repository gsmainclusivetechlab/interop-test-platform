<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestSetupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_setups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('test_step_id');
            $table->foreign('test_step_id')->references('id')->on('test_steps')->onDelete('cascade');
            $table->string('name');
            $table->string('type')->index();
            $table->longText('values');
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
        Schema::dropIfExists('test_setups');
    }
}
