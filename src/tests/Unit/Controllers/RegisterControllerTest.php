<?php

namespace Tests\Unit\Controllers;

use App\Models\Component;
use App\Models\GroupEnvironment;
use App\Models\Session;
use App\Models\User;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{

    public function testRegisterControllerShowTypeForm()
    {
        $user = factory(User::class)->create(['role' => User::ROLE_USER]);

        $response = $this->actingAs($user)->get(route('sessions.register.type'));

        // TODO - add inertia tests
        $response->assertStatus(200);
    }

    public function testRegisterControllerStoreType()
    {
        $user = factory(User::class)->create(['role' => User::ROLE_USER]);
        $response = $this->actingAs($user)->post(route('sessions.register.type.store', [
            'type' => 'test',
        ]));

        $response->assertStatus(302);
        $response->assertSessionHas('session.type');
        $response->assertRedirect(route('sessions.register.sut'));
    }

    public function testRegisterControllerShowSutForm()
    {
        $user = factory(User::class)->create(['role' => User::ROLE_USER]);

        $response = $this->actingAs($user)->get(route('sessions.register.sut'));

        // TODO - add inertia tests
        $response->assertStatus(200);
    }

    public function testRegisterControllerStoreSut()
    {
        $user = factory(User::class)->create(['role' => User::ROLE_USER]);
        $component = factory(Component::class)->create()->getKey();
        $this->assertIsInt($component);
        $response = $this->actingAs($user)->post(route('sessions.register.sut.store'), [
            'base_url' => 'http://test.com',
            'component_id' => $component
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('session.sut');
        $response->assertRedirect(route('sessions.register.info'));
    }

    public function testRegisterControllerShowInfoForm()
    {
        $user = factory(User::class)->create(['role' => User::ROLE_USER]);

        $response = $this->actingAs($user)->get(route('sessions.register.info'));

        // TODO - add inertia tests
        $response->assertStatus(200);
    }

    public function testRegisterControllerStoreInfo()
    {
        $testcase = factory(\App\Models\TestCase::class)->create()->getKey();
        $this->assertIsInt($testcase);
        $user = factory(User::class)->create(['role' => User::ROLE_USER]);
        $response = $this->actingAs($user)->post(route('sessions.register.info'), [
            'name' => 'http://test.com',
            'description' => "description",
            'test_cases' => [$testcase]
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('session.info');
        $response->assertRedirect(route('sessions.register.config'));
    }

    public function testRegisterControllerShowConfigForm()
    {
        $user = factory(User::class)->create(['role' => User::ROLE_USER]);

        $response = $this->actingAs($user)->get(route('sessions.register.config'));

        // TODO - add inertia tests
        $response->assertStatus(200);
    }

    public function testRegisterControllerStoreConfig()
    {
        $group = factory(GroupEnvironment::class)->create()->getKey();
        $testcase = factory(\App\Models\TestCase::class)->create()->getKey();
        $component = factory(Component::class)->create()->getKey();
        $session = factory(Session::class)->create();
        $user = factory(User::class)->create(['role' => User::ROLE_USER]);
        $response = $this->actingAs($user)
                         ->withSession([
                             'session.sut' => [
                                 'base_url' => 'http://test.com',
                                 'component_id' => $component
                             ],
                             'session.info' => [
                                 'name' => 'name',
                                 'test_cases' => [$testcase]
                             ],
                             'session.type' => [
                                 'type' => 'test'
                             ]
                         ])
                         ->post(route('sessions.register.config.store'), [
            'group_environment_id' => $group,
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);
        $response->assertRedirect(route('sessions.show', $session));
    }

}
