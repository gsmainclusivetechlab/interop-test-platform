<?php

use App\Models\TestComponent;
use Illuminate\Database\Seeder;

class TestComponentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(TestComponent::class)->createMany($this->getData());
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
                'name' => 'Mojaloop',
            ],
            [
                'name' => 'Mobile Money Operator 2',
            ],
        ];
    }
}
