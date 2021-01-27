<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGroupEnvironmentsToSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sessions', function (Blueprint $table) {
            $table
                ->unsignedBigInteger('group_environment_id')
                ->nullable()
                ->after('description');
            $table
                ->foreign('group_environment_id')
                ->references('id')
                ->on('group_environments')
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
            $table->dropForeign(['group_environment_id']);
            $table->dropColumn(['group_environment_id']);
        });
    }
}
