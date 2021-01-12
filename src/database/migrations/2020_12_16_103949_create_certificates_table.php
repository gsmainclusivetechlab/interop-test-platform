<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCertificatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_id')->nullable();
            $table->string('name');
            $table->string('ca_md5');
            $table->string('ca_crt_path');
            $table->string('client_crt_path');
            $table->string('client_key_path');
            $table->string('passphrase')->nullable();
            $table->timestamp('created_at')->nullable();
        });

        Schema::table('session_components', function (Blueprint $table) {
            $table->boolean('use_encryption')->default(false);
            $table->unsignedBigInteger('certificate_id')->nullable();

            $table
                ->foreign('certificate_id')
                ->references('id')
                ->on('certificates')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('session_components', function (Blueprint $table) {
            $table->dropForeign(['certificate_id']);

            $table->dropColumn(['use_encryption', 'certificate_id']);
        });

        Schema::dropIfExists('certificates');
    }
}
