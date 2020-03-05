<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_services', function (Blueprint $table) {
            $table->unsignedBigInteger('component_id');
            $table->foreign('component_id')->references('id')->on('test_components')->onDelete('cascade');
            $table->primary('component_id');
            $table->unsignedBigInteger('api_id');
            $table->foreign('api_id')->references('id')->on('api_services')->onDelete('cascade');
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
        Schema::dropIfExists('test_services');
    }
}
