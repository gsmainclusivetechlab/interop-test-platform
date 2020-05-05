<?php declare(strict_types=1);

use App\Models\ApiService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class ApisTableSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run()
    {
        foreach ($this->getApiServicesData() as $key => $attributes) {
            $apiService = ApiService::create($attributes);
            $apiService->apiEndpoints()->createMany(Arr::get($this->getApiEndpointsData(), $key));
        }
    }

    /**
     * @return array
     */
    protected function getApiServicesData()
    {
        return [
            [
                'name' => 'Service Provider',
            ],
            [
                'name' => 'Mobile Money',
            ],
            [
                'name' => 'Mojaloop Hub',
            ],
            [
                'name' => 'Mojaloop FSP',
            ],
        ];
    }

    /**
     * @return array[]
     */
    protected function getApiEndpointsData()
    {
        return [
            [
//                [
//                    'name' => 'Return transaction request information',
//                    'route' => 'transactionRequests/{ID}',
//                    'pattern' => '^transactionRequests/[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$',
//                    'method' => 'PUT',
//                ],
            ],
        ];
    }
}
