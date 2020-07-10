<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessageMismatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_mismatches', function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId('session_id')
                ->nullable()
                ->constrained()
                ->onDelete('cascade');
            $table->json('request');
            $table->text('exception');
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
        Schema::dropIfExists('message_mismatches');
    }
}
