<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMtlsColumnToTestStepsTable extends Migration
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
                ->boolean('mtls')
                ->after('position')
                ->default(false);
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
            $table->dropColumn('mtls');
        });
    }
}
