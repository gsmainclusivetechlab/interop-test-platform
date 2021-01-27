<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionnaireTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questionnaire_sections', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
        });

        Schema::create('questionnaire_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('section_id');
            $table->string('name');
            $table->string('question');
            $table->json('preconditions')->nullable();
            $table->string('type');
            $table->json('values');

            $table
                ->foreign('section_id')
                ->references('id')
                ->on('questionnaire_sections')
                ->onDelete('cascade');
        });

        Schema::create('questionnaire_test_cases', function (Blueprint $table) {
            $table->id();
            $table->string('test_case_slug');
            $table->json('matches');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('questionnaire_test_cases');
        Schema::dropIfExists('questionnaire_questions');
        Schema::dropIfExists('questionnaire_sections');
    }
}
