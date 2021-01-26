<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSlugToTestCases extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->generateSlugs();

        Schema::table('test_cases', function (Blueprint $table) {
            $table
                ->string('slug')
                ->nullable(false)
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('test_cases', function (Blueprint $table) {
            $table
                ->string('slug')
                ->nullable()
                ->change();
        });
    }

    protected function generateSlugs(): void
    {
        DB::table('test_cases')
            ->whereNull('slug')
            ->orderBy('id')
            ->each(function ($testCase) {
                $countSlugs = DB::table('test_cases')
                    ->where('slug', $slug = Str::slug($testCase->name))
                    ->count();

                $slug .= $countSlugs > 0 ? '-' . ($countSlugs + 1) : '';

                DB::table('test_cases')
                    ->where('id', $testCase->id)
                    ->update(['slug' => $slug]);
            });
    }
}
