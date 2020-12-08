<?php declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run()
    {
        $this->call([
            UsersTableSeeder::class,
            ApiSpecsTableSeeder::class,
            ComponentsTableSeeder::class,
            TestCasesTableSeeder::class,
            QuestionnaireSeeder::class,
        ]);
    }
}
