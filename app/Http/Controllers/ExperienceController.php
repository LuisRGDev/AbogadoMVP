<?php

namespace App\Http\Controllers;

use App\Models\ExperienceCase;
use App\Models\Testimonial;
use Illuminate\Contracts\View\View;

class ExperienceController extends Controller
{
    public function index(): View
    {
        return view('pages.experience', [
            'cases' => ExperienceCase::active()->ordered()->get(),
            'testimonials' => Testimonial::active()->ordered()->get(),
        ]);
    }
}
