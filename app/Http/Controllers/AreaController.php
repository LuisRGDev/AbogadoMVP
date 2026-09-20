<?php

namespace App\Http\Controllers;

use App\Models\Attorney;
use App\Models\PracticeArea;
use Illuminate\Contracts\View\View;

class AreaController extends Controller
{
    public function index(): View
    {
        return view('pages.areas', [
            'areas' => PracticeArea::active()->ordered()->get(),
        ]);
    }

    public function show(PracticeArea $area): View
    {
        abort_unless($area->is_active, 404);

        return view('pages.area-show', [
            'area' => $area,
            'others' => PracticeArea::active()->ordered()->whereKeyNot($area->getKey())->limit(3)->get(),
            'attorneys' => Attorney::active()->ordered()->get()
                ->filter(fn (Attorney $attorney): bool => in_array($area->title, $attorney->areas ?? [], true))
                ->values(),
        ]);
    }
}
