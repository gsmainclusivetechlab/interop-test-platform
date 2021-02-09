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
            $table->unsignedTinyInteger('repeat_max')->default(0)->after('response');
            $table->unsignedTinyInteger('repeat_count')->default(0)->after('repeat_max');
            $table->json('repeat_condition')->nullable()->after('repeat_count');
            $table->json('repeat_response')->nullable()->after('repeat_condition');
        });

        Schema::table('test_results', function (Blueprint $table) {
            $table->unsignedTinyInteger('iteration')->default(0)->after('test_step_id');
            $table->timestamp('updated_at');
            $table->timestamp('completed_at')->nullable();
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
                'repeat_max',
                'repeat_count',
                'repeat_condition',
                'repeat_response'
            ]);
        });

        Schema::table('test_results', function (Blueprint $table) {
            $table->dropColumn([
                'iteration',
                'updated_at',
                'completed_at'
            ]);
        });
    }
}
