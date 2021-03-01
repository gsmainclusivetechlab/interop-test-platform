<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTestResultRequestAndResponse extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('test_results', function (Blueprint $table) {
            $table
                ->mediumText('request')
                ->nullable()
                ->change();
            $table
                ->mediumText('response')
                ->nullable()
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('test_results', function (Blueprint $table) {
            $table
                ->json('request')
                ->nullable()
                ->change();
            $table
                ->json('response')
                ->nullable()
                ->change();
        });
    }
}
