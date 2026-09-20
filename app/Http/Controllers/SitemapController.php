<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Attorney;
use App\Models\Page;
use App\Models\PracticeArea;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $xml = Cache::remember('sitemap.xml', 3600, fn (): string => $this->build());

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=utf-8']);
    }

    private function build(): string
    {
        $urls = [
            [route('home'), '1.0', null],
            [route('nosotros'), '0.8', null],
            [route('areas.index'), '0.9', null],
            [route('team'), '0.7', null],
            [route('experience'), '0.7', null],
            [route('articles.index'), '0.8', null],
            [route('tools.severance'), '0.7', null],
            [route('contact.form'), '0.9', null],
        ];

        foreach (PracticeArea::active()->get(['slug', 'updated_at']) as $area) {
            $urls[] = [route('areas.show', $area), '0.8', $area->updated_at];
        }
        foreach (Attorney::active()->get(['slug', 'updated_at']) as $attorney) {
            $urls[] = [route('team.show', $attorney), '0.6', $attorney->updated_at];
        }
        foreach (Article::published()->get(['slug', 'updated_at']) as $article) {
            $urls[] = [route('articles.show', $article), '0.6', $article->updated_at];
        }
        foreach (Page::where('is_active', true)->whereIn('slug', ['privacidad', 'terminos', 'disclaimer'])->get(['slug', 'updated_at']) as $page) {
            $urls[] = [route('legal.'.$page->slug), '0.2', $page->updated_at];
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n".'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
        foreach ($urls as [$loc, $priority, $updatedAt]) {
            $xml .= '  <url><loc>'.e($loc).'</loc>';
            if ($updatedAt) {
                $xml .= '<lastmod>'.$updatedAt->toAtomString().'</lastmod>';
            }
            $xml .= '<priority>'.$priority.'</priority></url>'."\n";
        }

        return $xml.'</urlset>';
    }
}
