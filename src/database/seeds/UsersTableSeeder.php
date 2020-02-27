<?php declare(strict_types=1);

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
        factory(User::class)->create($this->getAdminData());
        factory(User::class)->create($this->getSuperadminData());
        factory(User::class, 20)->create();
    }

    /**
     * @return array
     */
    protected function getAdminData()
    {
        return [
            'first_name' => 'GSMA',
            'last_name' => 'Admin',
            'role' => User::ROLE_ADMIN,
            'email' => 'admin@gsma.com',
            'company' => 'GSMA',
        ];
    }

    /**
     * @return array
     */
    protected function getSuperadminData()
    {
        return [
            'first_name' => 'GSMA',
            'last_name' => 'Superadmin',
            'role' => User::ROLE_SUPERADMIN,
            'email' => 'superadmin@gsma.com',
            'company' => 'GSMA',
        ];
    }
}
