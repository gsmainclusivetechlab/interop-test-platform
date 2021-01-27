<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupTestCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_test_cases', function (Blueprint $table) {
            $table->unsignedBigInteger('group_id');
            $table
                ->foreign('group_id')
                ->references('id')
                ->on('groups')
                ->onDelete('cascade');
            $table->unsignedBigInteger('test_case_id');
            $table
                ->foreign('test_case_id')
                ->references('id')
                ->on('test_cases')
                ->onDelete('cascade');
            $table->primary(['group_id', 'test_case_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_test_cases');
    }
}
