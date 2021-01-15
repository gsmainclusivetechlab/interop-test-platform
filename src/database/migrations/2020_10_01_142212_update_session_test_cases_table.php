<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSessionTestCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('session_test_cases', function (Blueprint $table) {
            $table
                ->timestamp('deleted_at')
                ->nullable()
                ->after('test_case_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('session_test_cases', function (Blueprint $table) {
            $table->dropColumn(['deleted_at']);
        });
    }
}
