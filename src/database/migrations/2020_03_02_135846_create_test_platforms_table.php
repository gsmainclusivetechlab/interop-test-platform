<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestPlatformsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_platforms', function (Blueprint $table) {
            $table->unsignedBigInteger('component_id');
            $table->foreign('component_id')->references('id')->on('test_components')->onDelete('cascade');
            $table->primary('component_id');
            $table->unsignedBigInteger('specification_id');
            $table->foreign('specification_id')->references('id')->on('specifications_versions')->onDelete('cascade');
            $table->string('server');
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
        Schema::dropIfExists('test_platforms');
    }
}
