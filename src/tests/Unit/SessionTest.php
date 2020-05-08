<?php

namespace Tests\Unit;

use App\Models\Session;
use Tests\TestCase;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class SessionTest extends TestCase
{
    /**
     * Test Session creating with valid data.
     *
     * @return void
     */
    public function testSessionStoreValidData()
    {
        $session = factory(Session::class)->create();
        $this->assertInstanceOf(Session::class, $session);
    }

    /**
     * Test Session creating with invalid data.
     *
     * @return void
     */
    public function testSessionStoreInvalidData()
    {
        $emptySession = factory(Session::class)->make(self::emptyData());
        $this->assertFalse(Validator::make($emptySession->attributesToArray(), self::rules())->passes());

        $validationFailedSession = factory(Session::class)->make(self::invalidData());
        $this->assertFalse(Validator::make($validationFailedSession->attributesToArray(), self::rules())->passes());
    }

    /**
     * Test Session updating with valid data.
     *
     * @return void
     */
    public function testSessionUpdateValidData()
    {
        $session = factory(Session::class)->create();
        $this->assertTrue($session->update(factory(Session::class)->make()->attributesToArray()));
    }

    /**
     * Test Session updating with invalid data.
     *
     * @return void
     */
    public function testSessionUpdateInvalidData()
    {
        $sessionWithEmptyData = factory(Session::class)->create();
        $sessionWithEmptyData->setRawAttributes(self::emptyData());
        $this->assertFalse(Validator::make($sessionWithEmptyData->attributesToArray(), self::rules())->passes());

        $sessionWithInvalidData = factory(Session::class)->create();
        $sessionWithInvalidData->setRawAttributes(self::invalidData());
        $this->assertFalse(Validator::make($sessionWithInvalidData->attributesToArray(), self::rules())->passes());
    }

    /**
     * Test Session delete.
     *
     * @return void
     * @throws \Exception
     */
    public function testSessionDelete()
    {
        $session = factory(Session::class)->create();
        $session->delete();
        $this->assertDeleted($session->getTable(), $session->attributesToArray());
    }

    /**
     * Database validation rules.
     *
     * @return array
     */
    protected static function rules()
    {
        return [
            'uuid' => ['required', 'string', 'max:36'],
            'owner_id' => ['required', 'exists:users,id'],
            'scenario_id' => ['required', 'exists:scenarios,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['string'],
        ];
    }

    /**
     * Session Empty Data.
     *
     * @return array
     */
    protected static function emptyData()
    {
        return [
            'uuid' => null,
            'owner_id' => null,
            'scenario_id' => null,
            'name' => null,
            'description' => null,
        ];
    }

    /**
     * Session Invalid Data.
     *
     * @return array
     */
    protected static function invalidData()
    {
        return [
            'uuid' => Str::random(500),
            'owner_id' => Str::random(500),
            'scenario_id' => Str::random(500),
            'name' => Str::random(500),
            'description' => 125,
        ];
    }
}
