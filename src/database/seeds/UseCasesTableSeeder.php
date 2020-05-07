<?php

use App\Imports\TestCaseImport;
use App\Models\UseCase;
use Illuminate\Database\Seeder;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

class UseCasesTableSeeder extends Seeder
{
    /**
     * @return void
     * @throws Throwable
     */
    public function run()
    {
        factory(UseCase::class)
            ->createMany($this->getUseCasesData())
            ->each(function (UseCase $useCase, $key) {
                $finder = (new Finder())
                    ->files()
                    ->name('*.yaml')
                    ->in(database_path('seeds/test-cases'));

                foreach ($finder as $file) {
                    (new TestCaseImport($useCase))->import(Yaml::parse($file->getContents()));
                }
            });
    }

    /**
     * @return array
     */
    public function getUseCasesData()
    {
        return [
            'MIMP' => [
                'name' => 'Merchant-Initiated Merchant Payment',
                'description' => 'A Merchant-Initiated Merchant Payment is typically a receive amount, where the Payer FSP is not disclosing any fees to the Payee FSP. Please refer to 5.1.6.8 in "Open API for FSP Interoperability Specification" for more details',
            ],
        ];
    }
}
