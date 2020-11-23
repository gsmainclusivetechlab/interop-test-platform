<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoftDeletesToQuestionnaire extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('questionnaire_sections', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('questionnaire_questions', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('questionnaire_sections', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('questionnaire_questions', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
