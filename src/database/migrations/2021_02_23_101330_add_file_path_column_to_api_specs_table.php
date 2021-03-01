<?php

use cebe\openapi\Reader;
use cebe\openapi\Writer;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFilePathColumnToApiSpecsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $columns = Schema::getColumnListing('api_specs');

        if (!in_array('file_path', $columns)) {
            Schema::table('api_specs', function (Blueprint $table) {
                $table->string('file_path')->after('description');
            });
        }

        DB::table('api_specs')
            ->orderBy('id')
            ->each(function ($apiSpec) {
                $path = 'openapis/' . Str::random(32) . '.yaml';
                Storage::put(
                    $path,
                    Writer::writeToYaml(Reader::readFromJson($apiSpec->openapi))
                );

                DB::table('api_specs')
                    ->where('id', $apiSpec->id)
                    ->update([
                        'file_path' => $path,
                    ]);
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
            $table->dropColumn('file_path');
        });
        Storage::delete(Storage::allFiles('openapis'));
    }
}
