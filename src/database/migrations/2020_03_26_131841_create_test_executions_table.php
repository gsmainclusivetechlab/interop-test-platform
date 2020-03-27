<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestExecutionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_executions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('test_result_id');
            $table->foreign('test_result_id')->references('id')->on('test_results')->onDelete('cascade');
            $table->string('name');
            $table->string('group')->index();
            $table->string('status')->index();
            $table->text('message')->nullable();
            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('test_executions');
    }
}
