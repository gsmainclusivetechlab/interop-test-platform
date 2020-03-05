<?php

use App\Models\TestCase;
use App\Models\TestComponent;
use App\Models\TestConnection;
use App\Models\TestScenario;
use App\Models\TestService;
use App\Models\TestStep;
use App\Models\TestSuite;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Symfony\Component\Yaml\Yaml;

class TestScenariosTableSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run()
    {
        $data = Yaml::parseFile(database_path('seeds/data/test-scenarios.yaml'), Yaml::PARSE_CUSTOM_TAGS);

        foreach ($data as $item) {
            $scenario = TestScenario::create(Arr::only($item, ['name']));

            $scenario->components()->createMany(collect(Arr::get($item, 'components', []))->map(function ($item) {
                return Arr::only($item, ['name']);
            }))->each(function (TestComponent $component, $key) use ($scenario, $item) {
                $component->connections()->attach(collect(Arr::get($item, "components.{$key}.connections", []))->map(function ($item) use ($scenario) {
                    return [
                        'target_id' => $scenario->components()->where(Arr::get($item, 'target_id')->getValue())->value('id'),
                        'simulated' => Arr::get($item, 'simulated', false),
                    ];
                }));
            });
        }

        dd($data);
    }

    /**
     * @return array
     */
    public function getCaseRecords()
    {
        return [
            [
                'name' => 'Refused Transaction by Mojaloop',
                'description' => '**Description**

The Payer would like to buy goods or services worth {amount} USD from a Merchant (the Payee) in the Payee MMO system. Payee\'s MMO misses to transfer partyIdentifier to Mojaloop and gets an error.

---

**Pre-conditions**

- Send "creditParty":"16135551213" within the initial quesry to MMO1.
- MMO1 misses to send Payee\'s "partyIdentifier":"16135551213" to Mojaloop.

**Test data headers**

	{
		"Accept": "application/json",
		"Content-Type": "application/json",
		"X-Callback-URL": "http://example.com/example",
		"X-Date": "2020-02-20T10:28:44.466Z"
	}

---

**Test data body**

	{
		"amount":"50.00",
		"currency":"USD",
		"type":"merchantpay",
		"debitParty": [{"key":"msisdn", "value":"33555123456"}],
		"creditParty": [{"key":"msisdn", "value":"16135551213"}]
	}',
                'behavior' => TestCase::BEHAVIOR_NEGATIVE,
            ],
            [
                'name' => 'Transaction amount matches requested amount',
                'description' => '**Description**

The Payer would like to buy goods or services worth 70 USD from a Merchant (the Payee) in the Payee MMO system. Payer\'s MMO runs transaction for 70.00 USD as it was requested.

---

**Pre-conditions**

- Payee and Payer MMOs in Mojaloop as Participants.
- Payee and Payer exist in Mojaloop as Parties.

**Test data headers**

	{
		"Accept": "application/json",
		"Content-Type": "application/json",
		"X-Callback-URL": "http://example.com/example",
		"X-Date": "2020-02-20T10:28:44.466Z"
	}

---

**Test data body**

	{
		"amount":"70.00",
		"currency":"USD",
		"type":"merchantpay",
		"debitParty": [{"key":"msisdn", "value":"+33555123456"}],
		"creditParty": [{ "key":"msisdn", "value":"+33555123457"}]
	}',
                'behavior' => TestCase::BEHAVIOR_POSITIVE,
            ],
        ];
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
