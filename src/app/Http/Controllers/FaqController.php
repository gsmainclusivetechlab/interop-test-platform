<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Faq;
use Inertia\Inertia;
use Inertia\Response;

class FaqController extends Controller
{
    /**
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * @return Response
     */
    public function __invoke()
    {
        $this->authorize('viewContent', Faq::class);
        $faqData = Faq::where(['active' => true])->firstOrFail()->content;
        return Inertia::render('faq', ['faqData' => $faqData]);
    }
}
