<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Validation\Rule;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * Test User store.
     *
     * @return void
     */
    public function testUserStore()
    {
        $user = factory(User::class)->make();
        $this->assertValidationPasses($user->getAttributes(), [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'company' => ['string', 'max:255'],
            'role' => [
                'required',
                Rule::in([
                    User::ROLE_USER,
                    User::ROLE_ADMIN,
                    User::ROLE_SUPERADMIN,
                ]),
            ],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
        ]);
        $this->assertTrue($user->save());
    }

    /**
     * Test User delete.
     *
     * @return void
     * @throws \Exception
     */
    public function testUserDelete()
    {
        $user = factory(User::class)->create();
        $user->delete();
        $this->assertSoftDeleted($user);
    }

    /**
     * Test User force delete.
     *
     * @return void
     * @throws \Exception
     */
    public function testUserForceDelete()
    {
        $user = factory(User::class)->create();
        $user->forceDelete();
        $this->assertDeleted($user);
    }

    /**
     * Test User can admin.
     *
     * @return void
     */
    public function testUserCanAdmin()
    {
        $userNormal = factory(User::class)->create(['role' => User::ROLE_USER]);
        $userAdmin = factory(User::class)->create(['role' => User::ROLE_ADMIN]);
        $userSuperAdmin = factory(User::class)->create([
            'role' => User::ROLE_SUPERADMIN,
        ]);
        $this->assertFalse($userNormal->canAdmin());
        $this->assertTrue($userAdmin->canAdmin());
        $this->assertTrue($userSuperAdmin->canAdmin());
    }

    /**
     * Test User can super admin.
     *
     * @return void
     */
    public function testUserCanSuperadmin()
    {
        $userNormal = factory(User::class)->create(['role' => User::ROLE_USER]);
        $userAdmin = factory(User::class)->create(['role' => User::ROLE_ADMIN]);
        $userSuperAdmin = factory(User::class)->create([
            'role' => User::ROLE_SUPERADMIN,
        ]);
        $this->assertFalse($userNormal->canSuperadmin());
        $this->assertFalse($userAdmin->canSuperadmin());
        $this->assertTrue($userSuperAdmin->canSuperadmin());
    }
}
