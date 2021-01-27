<?php

namespace Database\Seeders;

use App\Imports\TestCaseImport;
use Illuminate\Database\Seeder;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

class TestCasesTableSeeder extends Seeder
{
    /**
     * @return void
     * @throws Throwable
     */
    public function run()
    {
        $finder = (new Finder())
            ->files()
            ->name(['*.yml', '*.yaml'])
            ->in(database_path('seeders/test-cases'));

        foreach ($finder as $file) {
            $testCase = (new TestCaseImport())->import(
                Yaml::parse($file->getContents())
            );
            $testCase->update(['public' => true]);
        }
    }
}
