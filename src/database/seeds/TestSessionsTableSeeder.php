<?php

use App\Models\TestSession;
use App\Models\User;
use Illuminate\Database\Seeder;

class TestSessionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::whereRole(User::ROLE_USER)->limit(20)->get();

        foreach ($users as $user) {
            $user->sessions()->saveMany(factory(TestSession::class, 5)->make());
        }
    }
}
