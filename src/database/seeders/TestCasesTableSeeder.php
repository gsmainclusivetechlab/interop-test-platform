<?php

namespace Database\Seeders;

use App\Imports\TestCaseImport;
use App\Models\TestCase;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
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
                $rows = Yaml::parse($file->getContents());
                $baseTestCase = TestCase::where(
                    'test_case_group_id',
                    Arr::get(
                        TestCase::where('slug', Arr::get($rows, 'slug'))->first(),
                        'test_case_group_id'
                    )
                )->lastPerGroup()->first();
                if ($baseTestCase) {
                    $rows = array_merge($rows, [
                        'test_case_group_id' => $baseTestCase->test_case_group_id,
                        'draft' => true,
                    ]);
                }

                $testCase = (new TestCaseImport())->import($rows);
                $testCase->update(['public' => $baseTestCase ? $baseTestCase->public : true]);
                if ($baseTestCase) {
                    if ($baseGroups = $baseTestCase->groups()->pluck('id')) {
                        $testCase->groups()->sync($baseGroups);
                    }
                    if ($baseTestCase->draft) {
                        $baseTestCase->delete();
                    }
                }
            } catch (Throwable $e) {
                echo "Can't upload {$file->getRealPath()}: {$e->getMessage()}\n";
            }

            echo "Uploaded: {$file->getFilename()}" . PHP_EOL;
        }

        echo 'Finished!' . PHP_EOL;
    }
}
