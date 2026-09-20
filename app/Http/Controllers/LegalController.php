<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Contracts\View\View;

class LegalController extends Controller
{
    private const TITLES = [
        'privacidad' => 'Aviso de Privacidad',
        'terminos' => 'Términos y condiciones',
        'disclaimer' => 'Disclaimer legal',
    ];

    public function privacidad(): View
    {
        return $this->render('privacidad');
    }

    public function terminos(): View
    {
        return $this->render('terminos');
    }

    public function disclaimer(): View
    {
        return $this->render('disclaimer');
    }

    private function render(string $slug): View
    {
        $page = Page::where('slug', $slug)->where('is_active', true)->firstOrFail();

        return view('pages.legal', [
            'page' => $page,
            'title' => $page->title ?: self::TITLES[$slug],
            'slug' => $slug,
        ]);
    }
}
