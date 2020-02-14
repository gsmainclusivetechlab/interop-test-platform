<?php

use App\Models\Environment;
use Illuminate\Database\Seeder;
use Symfony\Component\Yaml\Yaml;

class EnvironmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->getData() as $key => $data) {
            Environment::create($data);
        }
    }

    /**
     * @return array
     */
    protected function getData()
    {
        return [
            [
                'name' => 'Justcoded Servers',
                'variables' => Yaml::dump([
                    'MM_API_HOST' => 'http://gsma-itp-mmo-api.develop.s8.jc',
                    'MOJALOOP_API_HOST' => 'http://quoting-service.mojaloop.staging.s4.justcoded.com',
                ]),
            ],
        ];
    }
}
