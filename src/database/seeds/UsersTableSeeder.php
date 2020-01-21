<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class)->create([
            'name' => 'GSMA Admin',
            'role' => User::ROLE_ADMIN,
            'email' => 'admin@gsma.com',
        ]);
        factory(User::class, 20)->create();
    }
}
