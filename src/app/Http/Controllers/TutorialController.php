<?php declare(strict_types=1);

namespace App\Http\Controllers;

use Inertia\Inertia;

class TutorialController extends Controller
{
    /**
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * @return \Inertia\Response
     */
    public function __invoke()
    {
        return Inertia::render('tutorials');
    }
}
