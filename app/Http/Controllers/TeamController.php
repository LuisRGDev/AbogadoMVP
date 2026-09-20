<?php

namespace App\Http\Controllers;

use App\Models\Attorney;
use Illuminate\Contracts\View\View;

class TeamController extends Controller
{
    public function index(): View
    {
        return view('pages.team', [
            'attorneys' => Attorney::active()->ordered()->get(),
        ]);
    }
}
