<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Attorney;
use App\Models\PracticeArea;
use App\Models\Testimonial;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        return view('pages.home', [
            'areas' => PracticeArea::active()->ordered()->get(),
            'attorneys' => Attorney::active()->ordered()->limit(3)->get(),
            'articles' => Article::published()->latest('date')->limit(3)->get(),
            'testimonials' => Testimonial::active()->ordered()->get(),
        ]);
    }
}
