<?php declare(strict_types=1);

use Illuminate\Database\Seeder;

class TestDatabaseSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run()
    {
        factory(\App\Models\User::class, 20)->create();
    }
}
