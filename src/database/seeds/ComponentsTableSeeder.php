<?php

use App\Models\Component;
use Illuminate\Database\Seeder;

class ComponentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = $this->getData();

        foreach ($data as $row) {
            factory(Component::class)->create($row);
        }
    }

    /**
     * @return array
     */
    public function getData()
    {
        return [
            ['name' => 'Payer'],
            ['name' => 'Service Provider'],
            ['name' => 'Mobile Money Operator 1 (Payee)'],
            ['name' => 'Mojaloop'],
            ['name' => 'Mobile Money Operator 2 (Payer)'],
        ];
    }
}
