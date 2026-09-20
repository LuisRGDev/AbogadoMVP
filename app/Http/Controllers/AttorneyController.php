<?php

namespace App\Http\Controllers;

use App\Models\Attorney;
use App\Models\PracticeArea;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;

class AttorneyController extends Controller
{
    public function show(Attorney $attorney): View
    {
        abort_unless($attorney->is_active, 404);

        return view('pages.attorney-show', [
            'attorney' => $attorney,
            'areas' => PracticeArea::active()->ordered()->whereIn('title', $attorney->areas ?? [])->get(),
            'colleagues' => Attorney::active()->ordered()->whereKeyNot($attorney->getKey())->limit(3)->get(),
        ]);
    }

    public function vcard(Attorney $attorney): Response
    {
        abort_unless($attorney->is_active, 404);

        return response($attorney->vcard(), 200, [
            'Content-Type' => 'text/vcard; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="'.$attorney->slug.'.vcf"',
        ]);
    }
}
