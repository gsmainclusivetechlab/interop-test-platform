<?php

use App\Http\Client\Request;
use App\Models\Session;
use App\Models\TestStep;
use App\Models\TestScript;
use App\Testing\Tests\RequestScriptValidationTest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\AssertionFailedError;
use Tests\TestCase;

class ClientRequestTest extends TestCase
{
    use RefreshDatabase;

    public function testRequestWithSubstitution()
    {
        Carbon::setTestNow(Carbon::create(2020, 6, 30));

        $this->seed(ComponentsTableSeeder::class);

        /** @var Session $session */
        $session = factory(Session::class)->make();

        /** @var TestStep $step */
        $step = factory(TestStep::class)->make([
            'request' => <<<'body'
             {
                "uri": "/other-urls",
                "body": {
                    "mojaloop": "${MOJALOOP_BASE_URI}",
                    "mmos": {
                        "mmo2": "${MMO2_BASE_URI}",
                        "mmo1": "${MMO1_BASE_URI}"
                    },
                    "template_string": "${UNASSIGNED_TOKEN}"
                },
                "method": "POST",
                "headers": {
                    "x-iso-date": "${CURRENT_TIMESTAMP_ISO8601}",
                    "x-rfc-date": "${CURRENT_TIMESTAMP_RFC2822}",
                    "x-callback-url": "${SP_BASE_URI}/callback"
                 }
              }
body
        ,
        ]);

        /** @var Request $request */
        $request = $step->request;

        $mapped = $request->withSubstitutions($session);

        $this->assertEquals(
            [
                'method' => 'POST',
                'uri' => '/other-urls',
                'path' => '/other-urls',
                'headers' => [
                    'x-iso-date' => ['2020-06-30T00:00:00+00:00'],
                    'x-rfc-date' => ['Tue, 30 Jun 2020 00:00:00 +0000'],
                    'x-callback-url' => [
                        env('API_SERVICE_SP_SIMULATOR_URL') . '/callback',
                    ],
                ],
                'body' => [
                    'mojaloop' => env('API_SERVICE_MOJALOOP_HUB_URL'),
                    'mmos' => [
                        'mmo1' => env('API_SERVICE_MM_SIMULATOR_URL'),
                        'mmo2' => env('API_SERVICE_MOJALOOP_SIMULATOR_URL'),
                    ],
                    'template_string' => "\${UNASSIGNED_TOKEN}",
                ],
            ],
            $mapped->toArray()
        );
    }

    public function testRequestValidationPass()
    {
        /** @var TestStep $step */
        $step = factory(TestStep::class)->make([
            'request' => <<<'body'
             {
                "uri": "/other-urls",
                "body": {},
                "method": "POST",
                "headers": {
                    "x-callback-url": "http://sp.staging.interop.gsmainclusivelab.io/callback"
                 }
              }
body
        ,
        ]);

        /** @var Request $request */
        $request = $step->request;

        $testscript = factory(TestScript::class)->make();
        $testscript->rules = [
            'headers.x-callback-url.*' => 'required|url',
        ];

        $result = new RequestScriptValidationTest($request, $testscript);
        $this->assertNull($result->test()); // no errors = null
    }

    public function testRequestValidationFail()
    {
        /** @var TestStep $step */
        $step = factory(TestStep::class)->make([
            'request' => <<<'body'
             {
                "uri": "/other-urls",
                "body": {},
                "method": "POST",
                "headers": {
                    "x-callback-url": "invalid url"
                 }
              }
body
        ,
        ]);

        /** @var Request $request */
        $request = $step->request;

        $testscript = factory(TestScript::class)->make();
        $testscript->rules = [
            'headers.x-callback-url.*' => 'required|url',
        ];

        $result = new RequestScriptValidationTest($request, $testscript);

        $this->expectException(AssertionFailedError::class);
        $result->test();
    }
}
