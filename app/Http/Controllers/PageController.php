<?php

namespace App\Http\Controllers;

use App\Models\Attorney;
use App\Models\Faq;
use App\Models\Page;
use Illuminate\Contracts\View\View;

class PageController extends Controller
{
    public function nosotros(): View
    {
        return view('pages.nosotros', [
            'page' => Page::where('slug', 'nosotros')->where('is_active', true)->first(),
            'faqs' => Faq::active()->byGroup('general')->ordered()->get(),
            'attorneys' => Attorney::active()->ordered()->limit(3)->get(),
        ]);
    }
}
