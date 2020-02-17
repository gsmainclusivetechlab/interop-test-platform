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
        foreach ($this->getData() as $data) {
            Component::create($data);
        }
    }

    /**
     * @return array
     */
    protected function getData()
    {
        return [
            [
                'name' => 'Payer',
            ],
            [
                'name' => 'Service Provider',
            ],
            [
                'name' => 'Mobile Money Operator 1',
            ],
            [
                'name' => 'Mojaloop System',
            ],
            [
                'name' => 'Mobile Money Operator 2',
            ],
        ];
    }
}
