<?php

use App\Models\ApiService;
use App\Models\Component;
use App\Models\ComponentPath;
use App\Models\Scenario;
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
        $data = Yaml::parseFile(database_path('seeds/data/test-scenarios.yaml'), Yaml::PARSE_CUSTOM_TAGS);

        foreach ($data as $item) {
            $scenario = Scenario::create(Arr::only($item, Scenario::make()->getFillable()));

            $componentsData = Arr::get($item, 'test_components', []);
            $scenario->components()->createMany(collect($componentsData)->map(function ($item) {
                return array_merge(Arr::only($item, Component::make()->getFillable()), [
                    'api_service_id' => ApiService::whereRaw('CONCAT(name, " ", version) = ?', [Arr::get($item, 'api_service_id')])->value('id'),
                ]);
            }))->each(function (Component  $component, $key) use ($componentsData) {
                $component->paths()->attach(collect(Arr::get($componentsData, "{$key}.component_paths", []))->map(function ($item) use ($component) {
                    return array_merge(Arr::only($item, ComponentPath::make()->getFillable()), [
                        'target_id' => $component->scenario->components()->where('name', Arr::get($item, 'target_id'))->value('id'),
                    ]);
                }));
            });

//            $useCasesData = Arr::get($item, 'use_cases', []);
//            $scenario->useCases()->createMany(collect($suitesData)->map(function ($item) {
//                return Arr::only($item, TestSuite::make()->getFillable());
//            }))->each(function (TestSuite $suite, $key) use ($suitesData) {
//                $casesData = Arr::get($suitesData, "{$key}.cases", []);
//                $suite->cases()->createMany(collect($casesData)->map(function ($item) {
//                    return Arr::only($item, TestCase::make()->getFillable());
//                }))->each(function (TestCase $case, $key) use ($casesData) {
//                    $stepsData = Arr::get($casesData, "{$key}.steps", []);
//                    $case->steps()->createMany(collect($stepsData)->map(function ($item) use ($case) {
//                        return array_merge(Arr::only($item, TestStep::make()->getFillable()), [
//                            'source_id' => $case->suite->scenario->components()->where('name', Arr::get($item, 'source_id'))->value('id'),
//                            'target_id' => $case->suite->scenario->components()->where('name', Arr::get($item, 'target_id'))->value('id'),
//                        ]);
//                    }));
//                });
//            });
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
