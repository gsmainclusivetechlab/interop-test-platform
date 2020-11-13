<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVersionColumnsToTestCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('test_cases', function (Blueprint $table) {
            $table->unsignedInteger('test_case_group_id')->after('uuid');
            $table->unsignedInteger('version')->default(1)->after('test_case_group_id');
            $table->unique(['test_case_group_id', 'version']);
        });

//        $query = \Illuminate\Support\Facades\DB::table('test_cases')->find('*');
//        \Illuminate\Support\Facades\DB::table('test_cases')->insertUsing(['test_case_group_id', rand()], $query);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('test_cases', function (Blueprint $table) {
            $table->dropColumn(['test_case_group_id']);
            $table->dropColumn(['version']);
        });
    }
}
