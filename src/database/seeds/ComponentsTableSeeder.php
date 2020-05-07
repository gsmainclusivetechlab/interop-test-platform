<?php declare(strict_types=1);

use App\Models\Component;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class ComponentsTableSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run()
    {
        factory(Component::class)
            ->createMany($this->getComponentsData());
//            ->each(function (Component $component, $key) {
//                $component->connections()->attach(Arr::get($this->getConnectionsData(), $key, []));
//            });
    }

    /**
     * @return array
     */
    protected function getComponentsData()
    {
        return [
            [
                'name' => 'Service Provider',
                'uuid' => '114511be-74e9-49d5-b93e-b4a461e01626',
            ],
            [
                'name' => 'Mobile Money Operator 1',
                'uuid' => 'e5f5e817-94d6-4a43-a7ec-f7274b6d85c2',
            ],
            [
                'name' => 'Mojaloop',
                'uuid' => 'b2a85076-b748-4d93-8df1-2b39844e6d4b',
            ],
            [
                'name' => 'Mobile Money Operator 2',
                'uuid' => 'e602a859-a25f-4d37-9abe-0ac09fb734af',
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getConnectionsData()
    {
        return [
            Component::whereIn('name', ['Mobile Money Operator 1'])->pluck('id'),
            Component::whereIn('name', ['Service Provider', 'Mojaloop'])->pluck('id'),
            Component::whereIn('name', ['Mobile Money Operator 1', 'Mobile Money Operator 2'])->pluck('id'),
            Component::whereIn('name', ['Mojaloop'])->pluck('id'),
        ];
    }
}
