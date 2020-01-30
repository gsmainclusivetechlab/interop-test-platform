<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UsersTableSeeder::class,
            TestComponentsTableSeeder::class,
            TestScenariosTableSeeder::class,
            TestSuitesTableSeeder::class,
            TestCasesTableSeeder::class,
            TestSessionsTableSeeder::class,
        ]);
    }
}
