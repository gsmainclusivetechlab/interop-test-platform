<?php

use App\Models\TestCase;
use App\Models\TestSuite;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class TestSuitesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(TestSuite::class)->createMany($this->getData())->each(function ($suite, $key) {
            $cases = Arr::get($this->getCasesData(), $key);

            foreach ($cases as $case) {
                $suite->cases()->save(factory(TestCase::class)->make($case));
            }
        });
    }

    /**
     * @return array
     */
    protected function getData()
    {
        return [
            ['name' => 'Merchant Initiated Flow'],
        ];
    }

    /**
     * @return array
     */
    protected function getCasesData()
    {
        return [
            [
                [
                    'name' => 'Authorised Transaction',
                    'behavior' => TestCase::BEHAVIOR_POSITIVE,
                ],
                [
                    'name' => 'Unauthorised Transaction by MMO1',
                    'behavior' => TestCase::BEHAVIOR_POSITIVE,
                ],
                [
                    'name' => 'Unauthorised Transaction by Mojaloop',
                    'behavior' => TestCase::BEHAVIOR_POSITIVE,
                ],
                [
                    'name' => 'Unauthorised Transaction by MMO2',
                    'behavior' => TestCase::BEHAVIOR_POSITIVE,
                ],
                [
                    'name' => 'Bad Response Filled from MMO1',
                    'behavior' => TestCase::BEHAVIOR_NEGATIVE,
                ],
                [
                    'name' => 'Bad Response Filled from Mojaloop',
                    'behavior' => TestCase::BEHAVIOR_NEGATIVE,
                ],
                [
                    'name' => 'Bad Response Filled from MMO2',
                    'behavior' => TestCase::BEHAVIOR_NEGATIVE,
                ],
            ],
        ];
    }
}
