<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Testing\TestRunner;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Yaml\Yaml;

class HomeController extends Controller
{
    /**
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        dd(\App\Models\ApiVersion::first()->openapi);

        $data = Yaml::parseFile(database_path('seeds/data/test-scenarios.yaml'));

        foreach ($data as $key => $item) {
            $scenario = \App\Models\TestScenario::create($item);

            if (!empty($componentsData = Arr::get($item, 'components'))) {
                $scenario->components()->createMany($componentsData)->each(function (\App\Models\TestComponent $component, $key) use ($scenario, $componentsData) {
                    if (!empty($platformData = Arr::get($componentsData, "{$key}.platform"))) {
                        dd($platformData);
                    }

                    if (!empty($connectionsData = Arr::get($componentsData, "{$key}.connections"))) {
                        foreach ($connectionsData as $connectionKey => $connectionItem) {
                            $component->connections()->attach($scenario->components()->offset($connectionKey - 1)->value('id'), $connectionItem);
                        }
                    }
//                    $component->platform()->createMany(Arr::get($this->getPlatformsData(), $key, []));

                });
            }
        }

        dd(Yaml::parseFile(database_path('seeds/data/test-scenarios.yaml')));

//        $runner = new TestRunner();
//        $suite = $runner->getLoader()->load();
//        dd($suite);

        $sessions = auth()->user()->sessions()
            ->latest()
            ->paginate(12);

        return view('home', compact('sessions'));
    }
}
