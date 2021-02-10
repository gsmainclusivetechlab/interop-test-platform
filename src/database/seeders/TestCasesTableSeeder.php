<?php

namespace Database\Seeders;

use App\Imports\TestCaseImport;
use Illuminate\Database\Seeder;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;
use Throwable;

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
            echo "Uploading: {$file->getFilename()}" . PHP_EOL;

            try {
                $testCase = (new TestCaseImport())->import(
                    Yaml::parse($file->getContents())
                );
                $testCase->update(['public' => true]);
            } catch (Throwable $e) {
                echo "Can't upload {$file->getRealPath()}: {$e->getMessage()}\n";
            }

            echo "Uploaded: {$file->getFilename()}" . PHP_EOL;
        }

        echo 'Finished!' . PHP_EOL;
    }
}
