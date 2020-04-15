<?php declare(strict_types=1);

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run()
    {
        factory(User::class)->create([
            'first_name' => 'GSMA',
            'last_name' => 'Superadmin',
            'role' => User::ROLE_SUPERADMIN,
            'email' => 'superadmin@gsma.com',
            'company' => 'GSMA',
            'password' => Hash::make('qzRBHEzStdG8XWhy'),
        ]);
        factory(User::class, 50)->create();
    }
}
