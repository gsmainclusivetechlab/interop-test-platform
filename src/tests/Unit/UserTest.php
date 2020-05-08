<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserTest extends TestCase
{
    /**
     * Test User creating with valid data.
     *
     * @return void
     */
    public function testUserStoreValidData()
    {
        $user = factory(User::class)->create();
        $this->assertInstanceOf(User::class, $user);
    }

    /**
     * Test User creating with invalid data.
     *
     * @return void
     */
    public function testUserStoreInvalidData()
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
     * Test User updating with valid data.
     *
     * @return void
     */
    public function testUserUpdateValidData()
    {
        $user = factory(User::class)->create();
        $this->assertTrue($user->update(factory(User::class)->make()->attributesToArray()));
    }

    /**
     * Test User updating with invalid data.
     *
     * @return void
     */
    public function testUserUpdateInvalidData()
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
     * Test User soft delete.
     *
     * @return void
     * @throws \Exception
     */
    public function testUserSoftDelete()
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
     * Test User isAdmin.
     *
     * @return void
     */
    public function testUserIsAdmin()
    {
        $userNormal = factory(User::class)->create(['role' => User::ROLE_USER]);
        $userAdmin = factory(User::class)->create(['role' => User::ROLE_ADMIN]);
        $userSuperAdmin = factory(User::class)->create(['role' => User::ROLE_SUPERADMIN]);
        $this->assertFalse($userNormal->isAdmin());
        $this->assertTrue($userAdmin->isAdmin());
        $this->assertTrue($userSuperAdmin->isAdmin());
    }

    /**
     * Test User isSuperadmin.
     *
     * @return void
     */
    public function testUserIsSuperadmin()
    {
        $userNormal = factory(User::class)->create(['role' => User::ROLE_USER]);
        $userAdmin = factory(User::class)->create(['role' => User::ROLE_ADMIN]);
        $userSuperAdmin = factory(User::class)->create(['role' => User::ROLE_SUPERADMIN]);
        $this->assertFalse($userNormal->isSuperadmin());
        $this->assertFalse($userAdmin->isSuperadmin());
        $this->assertTrue($userSuperAdmin->isSuperadmin());
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
            'first_name' => null,
            'last_name' => null,
            'role' => null,
            'email' => null,
            'company' => null,
            'password' => null,
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
