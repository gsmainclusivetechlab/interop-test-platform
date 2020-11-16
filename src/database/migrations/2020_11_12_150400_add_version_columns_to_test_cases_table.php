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
        });

        DB::table('test_cases')->chunkById(100, function ($testCases) {
            foreach ($testCases as $testCase) {
                DB::table('test_cases')
                    ->where('id', $testCase->id)
                    ->update(['test_case_group_id' => rand(1, 999999999)]);
            }
        });

        Schema::table('test_cases', function (Blueprint $table) {
            $table->unique(['test_case_group_id', 'version']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('test_cases', function (Blueprint $table) {
            $table->dropUnique(['test_case_group_id', 'version']);
            $table->dropColumn(['test_case_group_id']);
            $table->dropColumn(['version']);
        });
    }
}
