<?php

use App\Models\ApiSpec;
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
        Schema::table('api_specs', function (Blueprint $table) {
            $table->string('file_path')->after('description');
        });

        ApiSpec::all()->each(function (ApiSpec $apiSpec) {
            $path = 'openapis/' . Str::random(32) . '.yaml';
            Storage::put($path, Writer::writeToYaml($apiSpec->openapi));
            File::chmod(Storage::path('openapis'), 777);
            $apiSpec->update([
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
