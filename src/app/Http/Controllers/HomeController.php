<?php

namespace App\Http\Controllers;

use App\Models\Environment;
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
        dd(Yaml::dump(Environment::first()->variables));

//        $schema = \cebe\openapi\Reader::readFromYamlFile('https://raw.githubusercontent.com/OAI/OpenAPI-Specification/master/examples/v3.0/petstore.yaml');
//        $validator = (new \League\OpenAPIValidation\PSR7\ValidatorBuilder)->fromSchema($schema)->getRequestValidator();
//        $match = $validator->validate(request()->convertToPsr());
//
//        dd($schema->getSerializableData());

        return view('home');
    }
}
