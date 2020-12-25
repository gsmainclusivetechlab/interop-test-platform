<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSlugColumnToComponentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('components', function (Blueprint $table) {
            $table->string('slug')
                ->after('sutable');
        });

        $this->addSlugs();

        Schema::table('components', function (Blueprint $table) {
            $table->unique('slug');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('components', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }

    protected function addSlugs(): void
    {
        collect([
            'Service Provider' => 'service-provider',
            'Mobile Money Operator 1' => 'mmo-1',
            'Mojaloop' => 'mojaloop',
            'Mobile Money Operator 2' => 'mmo-2'
        ])->each(function ($slug, $name) {
            DB::table('components')->where('name', $name)->update([
                'slug' => $slug
            ]);
        });

        DB::table('components')
            ->where('slug', '')
            ->orderBy('id')
            ->each(function ($testCase) {
                $i = 1;
                $slug = $originalSlug = Str::slug($testCase->name);

                while ($this->otherRecordExistsWithSlug($slug)) {
                    $slug = $originalSlug . '-' . $i++;
                }

                DB::table('components')
                    ->where('id', $testCase->id)
                    ->update(['slug' => $slug]);
            });
    }

    public function otherRecordExistsWithSlug($slug): bool
    {
        return DB::table('components')
            ->where('slug', $slug)
            ->exists();
    }
}
