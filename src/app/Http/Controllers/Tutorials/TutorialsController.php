<?php declare(strict_types=1);

namespace App\Http\Controllers\Tutorials;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TutorialsController extends Controller
{
    /**
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('tutorials.index');
    }
}
