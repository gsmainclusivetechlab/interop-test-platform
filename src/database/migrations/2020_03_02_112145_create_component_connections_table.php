<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComponentConnectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('component_connections', function (Blueprint $table) {
            $table->unsignedBigInteger('source_id');
            $table
                ->foreign('source_id')
                ->references('id')
                ->on('components')
                ->onDelete('cascade');
            $table->unsignedBigInteger('target_id');
            $table
                ->foreign('target_id')
                ->references('id')
                ->on('components')
                ->onDelete('cascade');
            $table->primary(['source_id', 'target_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('component_connections');
    }
}
