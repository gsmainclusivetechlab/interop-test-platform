<?php declare(strict_types=1);

use App\Models\Specification;
use Illuminate\Database\Seeder;

class SpecificationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->getData() as $key => $data) {
            Specification::create($data);
        }
    }

    /**
     * @return array
     */
    protected function getData()
    {
        return [
            [
                'uuid' => '015f918d-9020-4b3a-8233-b043a1a8d5a0',
                'name' => 'Mobile Money API v1.0',
                'server' => '{MM_API_HOST}',
                'schema' => file_get_contents(database_path('schemas/mm.api.yaml')),
            ],
            [
                'uuid' => '475f55d4-765d-45cf-9354-9e5a3be70276',
                'name' => 'Mojaloop Hub API v1.0',
                'server' => '{MOJALOOP_API_HOST}',
                'schema' => file_get_contents(database_path('schemas/mojaloop.api.yaml')),
            ],
        ];
    }
}
