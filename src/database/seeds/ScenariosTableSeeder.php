<?php

use App\Models\ApiService;
use App\Models\Component;
use App\Models\ComponentPath;
use App\Models\Scenario;
use App\Models\TestCase;
use App\Models\TestRequestScript;
use App\Models\TestResponseScript;
use App\Models\TestStep;
use App\Models\UseCase;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Symfony\Component\Yaml\Yaml;

class ScenariosTableSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run()
    {
        $data = Yaml::parseFile(database_path('seeds/data/scenarios.yaml'), Yaml::PARSE_CUSTOM_TAGS);

        foreach ($data as $item) {
            $scenario = Scenario::create(Arr::only($item, Scenario::make()->getFillable()));

            $componentsData = Arr::get($item, 'components', []);
            $scenario->components()->createMany(collect($componentsData)->map(function ($item) {
                return array_merge(Arr::only($item, Component::make()->getFillable()), [
                    'api_service_id' => ApiService::whereRaw('CONCAT(name, " ", version) = ?', [Arr::get($item, 'api_service_id')])->value('id'),
                ]);
            }))->each(function (Component  $component, $key) use ($componentsData) {
                $component->paths()->attach(collect(Arr::get($componentsData, "{$key}.paths", []))->map(function ($item) use ($component) {
                    return array_merge(Arr::only($item, ComponentPath::make()->getFillable()), [
                        'target_id' => $component->scenario->components()->where('name', Arr::get($item, 'target_id'))->value('id'),
                    ]);
                }));
            });

            $useCasesData = Arr::get($item, 'use_cases', []);
            $scenario->useCases()->createMany(collect($useCasesData)->map(function ($item) {
                return Arr::only($item, UseCase::make()->getFillable());
            }))->each(function (UseCase $useCase, $key) use ($useCasesData) {
                $testCasesData = Arr::get($useCasesData, "{$key}.test_cases", []);
                $useCase->testCases()
                    ->createMany(collect($testCasesData)->map(function ($item) {
                        return Arr::only($item, TestCase::make()->getFillable());
                    }))
                    ->each(function (TestCase $testCase, $key) use ($testCasesData) {
                        $testStepsData = Arr::get($testCasesData, "{$key}.test_steps", []);
                        $testCase->testSteps()
                            ->createMany(collect($testStepsData)->map(function ($item) use ($testCase) {
                                return array_merge(Arr::only($item, TestStep::make()->getFillable()), [
                                    'source_id' => $testCase->useCase->scenario->components()->where('name', Arr::get($item, 'source_id'))->value('id'),
                                    'target_id' => $testCase->useCase->scenario->components()->where('name', Arr::get($item, 'target_id'))->value('id'),
                                ]);
                            }))
                            ->each(function (TestStep $testStep, $key) use ($testStepsData) {
                                $testStep->testRequestScripts()
                                    ->createMany(collect(Arr::get($testStepsData, "{$key}.test_request_scripts", []))->map(function ($item) {
                                        return Arr::only($item, TestRequestScript::make()->getFillable());
                                    }));
                                $testStep->testResponseScripts()
                                    ->createMany(collect(Arr::get($testStepsData, "{$key}.test_response_scripts", []))->map(function ($item) {
                                        return Arr::only($item, TestResponseScript::make()->getFillable());
                                    }));
                            });
                    });
            });
        }

        dd($data);
    }

    /**
     * @return array
     */
    public function getStepRecords()
    {
        return [
            [
                'source_id' => TestScenario::offset(0)->first()->components()->offset(1)->value('id'),
                'target_id' => TestScenario::offset(0)->first()->components()->offset(2)->value('id'),
//                'expected_request' => [],
//                'expected_response' => [
//                    'status' => 'in:202',
//                ],
            ],
//            [
//                'path' => 'transactionRequests',
//                'method' => 'POST',
//                'source_id' => TestComponent::where('name', 'Mobile Money Operator 1')->value('id'),
//                'target_id' => TestComponent::where('name', 'Mojaloop System')->value('id'),
//                'expected_request' => [],
//                'expected_response' => [
//                    'status' => 'in:202',
//                ],
//            ],
//            [
//                'path' => 'transactionRequests',
//                'method' => 'POST',
//                'source_id' => TestComponent::where('name', 'Mojaloop System')->value('id'),
//                'target_id' => TestComponent::where('name', 'Mobile Money Operator 2')->value('id'),
//                'expected_request' => [],
//                'expected_response' => [
//                    'status' => 'in:202',
//                ],
//            ],
//            [
//                'path' => 'transactionRequests/%',
//                'method' => 'PUT',
//                'source_id' => TestComponent::where('name', 'Mobile Money Operator 2')->value('id'),
//                'target_id' => TestComponent::where('name', 'Mojaloop System')->value('id'),
//                'expected_request' => [],
//                'expected_response' => [
//                    'status' => 'in:200',
//                ],
//            ],
//            [
//                'path' => 'transactionRequests/%',
//                'method' => 'PUT',
//                'source_id' => TestComponent::where('name', 'Mojaloop System')->value('id'),
//                'target_id' => TestComponent::where('name', 'Mobile Money Operator 1')->value('id'),
//                'expected_request' => [],
//                'expected_response' => [
//                    'status' => 'in:200',
//                ],
//            ],
//            [
//                'path' => 'quotes',
//                'method' => 'POST',
//                'source_id' => TestComponent::where('name', 'Mobile Money Operator 2')->value('id'),
//                'target_id' => TestComponent::where('name', 'Mojaloop System')->value('id'),
//                'expected_request' => [],
//                'expected_response' => [
//                    'status' => 'in:202',
//                ],
//            ],
//            [
//                'path' => 'quotes',
//                'method' => 'POST',
//                'source_id' => TestComponent::where('name', 'Mojaloop System')->value('id'),
//                'target_id' => TestComponent::where('name', 'Mobile Money Operator 1')->value('id'),
//                'expected_request' => [],
//                'expected_response' => [
//                    'status' => 'in:202',
//                ],
//            ],
//            [
//                'path' => 'quotes/%',
//                'method' => 'PUT',
//                'source_id' => TestComponent::where('name', 'Mobile Money Operator 1')->value('id'),
//                'target_id' => TestComponent::where('name', 'Mojaloop System')->value('id'),
//                'expected_request' => [],
//                'expected_response' => [
//                    'status' => 'in:200',
//                ],
//            ],
//            [
//                'path' => '%/quotes/%',
//                'method' => 'PUT',
//                'source_id' => TestComponent::where('name', 'Mojaloop System')->value('id'),
//                'target_id' => TestComponent::where('name', 'Mobile Money Operator 2')->value('id'),
//                'expected_request' => [],
//                'expected_response' => [
//                    'status' => 'in:200',
//                ],
//            ],
//            [
//                'path' => 'transfers',
//                'method' => 'POST',
//                'source_id' => TestComponent::where('name', 'Mobile Money Operator 2')->value('id'),
//                'target_id' => TestComponent::where('name', 'Mojaloop System')->value('id'),
//                'expected_request' => [],
//                'expected_response' => [
//                    'status' => 'in:202',
//                ],
//            ],
//            [
//                'path' => 'transfers',
//                'method' => 'POST',
//                'source_id' => TestComponent::where('name', 'Mojaloop System')->value('id'),
//                'target_id' => TestComponent::where('name', 'Mobile Money Operator 1')->value('id'),
//                'expected_request' => [],
//                'expected_response' => [
//                    'status' => 'in:202',
//                ],
//            ],
//            [
//                'path' => 'transfers/%',
//                'method' => 'PUT',
//                'source_id' => TestComponent::where('name', 'Mobile Money Operator 1')->value('id'),
//                'target_id' => TestComponent::where('name', 'Mojaloop System')->value('id'),
//                'expected_request' => [],
//                'expected_response' => [
//                    'status' => 'in:200',
//                ],
//            ],
//            [
//                'path' => '%/transfers/%',
//                'method' => 'PUT',
//                'source_id' => TestComponent::where('name', 'Mojaloop System')->value('id'),
//                'target_id' => TestComponent::where('name', 'Mobile Money Operator 2')->value('id'),
//                'expected_request' => [],
//                'expected_response' => [
//                    'status' => 'in:200',
//                ],
//            ],


//            [
//                [
//                    'path' => 'transactions',
//                    'method' => 'POST',
//                    'source_id' => TestComponent::where('name', 'Service Provider')->value('id'),
//                    'target_id' => TestComponent::where('name', 'Mobile Money Operator 1')->value('id'),
//                    'expected_request' => [],
//                    'expected_response' => [
//                        'status' => 'in:400',
//                    ],
//                ],
//            ],
//            [
//                [
//                    'path' => 'transactions',
//                    'method' => 'POST',
//                    'source_id' => TestComponent::where('name', 'Service Provider')->value('id'),
//                    'target_id' => TestComponent::where('name', 'Mobile Money Operator 1')->value('id'),
//                    'expected_request' => [],
//                    'expected_response' => [
//                        'status' => 'in:202',
//                    ],
//                ],
//                [
//                    'path' => 'transactionRequests',
//                    'method' => 'POST',
//                    'source_id' => TestComponent::where('name', 'Mobile Money Operator 1')->value('id'),
//                    'target_id' => TestComponent::where('name', 'Mojaloop System')->value('id'),
//                    'expected_request' => [],
//                    'expected_response' => [
//                        'status' => 'in:500',
//                    ],
//                ],
//            ],
//            [
//                [
//                    'path' => 'transactions',
//                    'method' => 'POST',
//                    'source_id' => TestComponent::where('name', 'Service Provider')->value('id'),
//                    'target_id' => TestComponent::where('name', 'Mobile Money Operator 1')->value('id'),
//                    'expected_request' => [],
//                    'expected_response' => [
//                        'status' => 'in:202',
//                    ],
//                ],
//                [
//                    'path' => 'transactionRequests',
//                    'method' => 'POST',
//                    'source_id' => TestComponent::where('name', 'Mobile Money Operator 1')->value('id'),
//                    'target_id' => TestComponent::where('name', 'Mojaloop System')->value('id'),
//                    'expected_request' => [],
//                    'expected_response' => [
//                        'status' => 'in:202',
//                    ],
//                ],
//                [
//                    'path' => 'transactionRequests',
//                    'method' => 'POST',
//                    'source_id' => TestComponent::where('name', 'Mojaloop System')->value('id'),
//                    'target_id' => TestComponent::where('name', 'Mobile Money Operator 2')->value('id'),
//                    'expected_request' => [],
//                    'expected_response' => [
//                        'status' => 'in:202',
//                    ],
//                ],
//                [
//                    'path' => 'transactionRequests/%',
//                    'method' => 'PUT',
//                    'source_id' => TestComponent::where('name', 'Mobile Money Operator 2')->value('id'),
//                    'target_id' => TestComponent::where('name', 'Mojaloop System')->value('id'),
//                    'expected_request' => [],
//                    'expected_response' => [
//                        'status' => 'in:200',
//                    ],
//                ],
//                [
//                    'path' => 'transactionRequests/%',
//                    'method' => 'PUT',
//                    'source_id' => TestComponent::where('name', 'Mojaloop System')->value('id'),
//                    'target_id' => TestComponent::where('name', 'Mobile Money Operator 1')->value('id'),
//                    'expected_request' => [],
//                    'expected_response' => [
//                        'status' => 'in:200',
//                    ],
//                ],
//                [
//                    'path' => 'quotes',
//                    'method' => 'POST',
//                    'source_id' => TestComponent::where('name', 'Mobile Money Operator 2')->value('id'),
//                    'target_id' => TestComponent::where('name', 'Mojaloop System')->value('id'),
//                    'expected_request' => [],
//                    'expected_response' => [
//                        'status' => 'in:202',
//                    ],
//                ],
//                [
//                    'path' => 'quotes',
//                    'method' => 'POST',
//                    'source_id' => TestComponent::where('name', 'Mojaloop System')->value('id'),
//                    'target_id' => TestComponent::where('name', 'Mobile Money Operator 1')->value('id'),
//                    'expected_request' => [],
//                    'expected_response' => [
//                        'status' => 'in:202',
//                    ],
//                ],
//                [
//                    'path' => 'quotes/%',
//                    'method' => 'PUT',
//                    'source_id' => TestComponent::where('name', 'Mobile Money Operator 1')->value('id'),
//                    'target_id' => TestComponent::where('name', 'Mojaloop System')->value('id'),
//                    'expected_request' => [],
//                    'expected_response' => [
//                        'status' => 'in:200',
//                    ],
//                ],
//                [
//                    'path' => '%/quotes/%',
//                    'method' => 'PUT',
//                    'source_id' => TestComponent::where('name', 'Mojaloop System')->value('id'),
//                    'target_id' => TestComponent::where('name', 'Mobile Money Operator 2')->value('id'),
//                    'expected_request' => [],
//                    'expected_response' => [
//                        'status' => 'in:200',
//                    ],
//                ],
//                [
//                    'path' => 'transfers',
//                    'method' => 'POST',
//                    'source_id' => TestComponent::where('name', 'Mobile Money Operator 2')->value('id'),
//                    'target_id' => TestComponent::where('name', 'Mojaloop System')->value('id'),
//                    'expected_request' => [
//                        'body.amount.amount' => 'in:70'
//                    ],
//                    'expected_response' => [],
//                ],
//            ],
        ];
    }
}
