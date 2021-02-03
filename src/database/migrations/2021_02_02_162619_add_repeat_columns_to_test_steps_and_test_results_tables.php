<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRepeatColumnsToTestStepsAndTestResultsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('test_steps', function (Blueprint $table) {
            $table->unsignedTinyInteger('max_repeats')->default(1)->after('test_step_id');
            $table->timestamp('updated_at')->after('created_at');
        });

        Schema::table('test_results', function (Blueprint $table) {
            $table->unsignedTinyInteger('repeats')->default(1)->after('test_step_id');
            $table->timestamp('updated_at')->after('created_at');
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
            $table->dropColumn(['repeats', 'updated_at']);
        });

        Schema::table('test_results', function (Blueprint $table) {
            $table->dropColumn(['repeats', 'updated_at']);
        });
    }
}
