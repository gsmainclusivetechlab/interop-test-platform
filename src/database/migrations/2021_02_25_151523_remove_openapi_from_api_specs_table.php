<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class RemoveOpenapiFromApiSpecsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('api_specs', function (Blueprint $table) {
            $table->string('api_path')->after('file_path');
        });

        DB::table('api_specs')
            ->orderBy('id')
            ->each(function ($apiSpec) {
                $path = 'openapis/' . Str::random(40) . '.txt';
                $data = json_encode(json_decode($apiSpec->openapi));

                Storage::put($path, $data);

                DB::table('api_specs')
                    ->where('id', $apiSpec->id)
                    ->update(['api_path' => $path]);
            });

        Schema::table('api_specs', function (Blueprint $table) {
            $table->dropColumn('openapi');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('api_specs', function (Blueprint $table) {
            $table->json('openapi')->after('name');
        });

        DB::table('api_specs')
            ->orderBy('id')
            ->each(function ($apiSpec) {
                DB::table('api_specs')
                    ->where('id', $apiSpec->id)
                    ->update(['openapi' => Storage::get($apiSpec->api_path)]);
            });

        Schema::table('api_specs', function (Blueprint $table) {
            $table->dropColumn('api_path');
        });
    }
}
