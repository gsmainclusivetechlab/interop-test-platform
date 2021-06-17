<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSimulatorPluginsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('simulator_plugins', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('group_id');
            $table->string('name');
            $table->string('file_path');

            $table->timestamps();

            $table
                ->foreign('group_id')
                ->references('id')
                ->on('groups')
                ->onDelete('cascade');
        });

        Schema::table('sessions', function (Blueprint $table) {
            $table
                ->unsignedBigInteger('simulator_plugin_id')
                ->after('environments')
                ->nullable();

            $table
                ->foreign('simulator_plugin_id')
                ->references('id')
                ->on('simulator_plugins')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sessions', function (Blueprint $table) {
            $table->dropForeign(['simulator_plugin_id']);

            $table->dropColumn('simulator_plugin_id');
        });

        Schema::dropIfExists('simulator_plugins');
    }
}
