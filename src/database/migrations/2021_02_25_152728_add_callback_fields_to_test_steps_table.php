<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCallbackFieldsToTestStepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('test_steps', function (Blueprint $table) {
            $table
                ->string('callback_origin_path')
                ->nullable()
                ->after('mtls');
            $table
                ->string('callback_origin_method')
                ->nullable()
                ->after('callback_origin_path');
            $table
                ->string('callback_name')
                ->nullable()
                ->after('callback_origin_method');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('test_steps', function (Blueprint $table) {
            $table->dropColumn([
                'callback_origin_path',
                'callback_origin_method',
                'callback_name',
            ]);
        });
    }
}
