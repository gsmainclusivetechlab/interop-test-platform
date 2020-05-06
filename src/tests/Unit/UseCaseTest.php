<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UseCaseTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test UseCase creating with valid data.
     *
     * @return void
     */
    public function testUseCaseStoreValidData()
    {
        $user = factory(User::class)->create();
        $this->assertInstanceOf(User::class, $user);
    }

    /**
     * Test UseCase creating with invalid data.
     *
     * @return void
     */
    public function testUseCaseStoreInvalidData()
    {
        $emptyUser = factory(User::class)->make(self::emptyData());
        $this->assertFalse(Validator::make($emptyUser->attributesToArray(), self::rules())->passes());

        $validationFailedUser = factory(User::class)->make(self::invalidData());
        $this->assertFalse(Validator::make($validationFailedUser->attributesToArray(), self::rules())->passes());

        $existUser = factory(User::class)->create();
        $userUniqueEmail = factory(User::class)->make([
            'email' => $existUser->email,
        ]);
        $this->assertFalse(Validator::make($userUniqueEmail->attributesToArray(), self::rules())->passes());
    }

    /**
     * Test UseCase updating with valid data.
     *
     * @return void
     */
    public function testUseCaseUpdateValidData()
    {
        $user = factory(User::class)->create();
        $this->assertTrue($user->update(factory(User::class)->make()->attributesToArray()));
    }

    /**
     * Test UseCase updating with invalid data.
     *
     * @return void
     */
    public function testUseCaseUpdateInvalidData()
    {
        $userWithEmptyData = factory(User::class)->create();

        $userWithEmptyData->setRawAttributes(self::emptyData());
        $this->assertFalse(Validator::make($userWithEmptyData->attributesToArray(), self::rules())->passes());

        $userWithInvalidData = factory(User::class)->create();
        $userWithInvalidData->setRawAttributes(self::invalidData());
        $this->assertFalse(Validator::make($userWithInvalidData->attributesToArray(), self::rules())->passes());

        $existUser = factory(User::class)->create();
        $userWithUniqueEmail = factory(User::class)->create();
        $userWithUniqueEmail->email = $existUser->email;
        $this->assertFalse(Validator::make($userWithUniqueEmail->attributesToArray(), self::rules())->passes());
    }

    /**
     * Test UseCase soft delete.
     *
     * @return void
     * @throws \Exception
     */
    public function testUseCaseSoftDelete()
    {
        $user = factory(User::class)->create();
        $user->delete();
        $this->assertSoftDeleted(
            $user->getTable(),
            $user->attributesToArray(),
            null,
            $user->getDeletedAtColumn()
        );
    }

    /**
     * Database validation rules.
     *
     * @return array
     */
    protected static function rules()
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'company' => ['string', 'max:255'],
            'role' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255', 'unique:users'],
            'remember_token' => ['string', 'max:100'],
            'password' => ['required', 'string', 'max:255'],
        ];
    }

    /**
     * User Empty Data.
     *
     * @return array
     */
    protected static function emptyData()
    {
        return [
            'first_name' => '',
            'last_name' => '',
            'role' => '',
            'email' => '',
            'company' => '',
            'password' => '',
        ];
    }

    /**
     * User Invalid Data.
     *
     * @return array
     */
    protected static function invalidData()
    {
        return [
            'first_name' => Str::random(500),
            'last_name' => Str::random(500),
            'company' => Str::random(500),
            'role' => 'test',
            'email' => 'test',
            'password' => Hash::make('password'),
            'remember_token' => Str::random(100),
        ];
    }
}
