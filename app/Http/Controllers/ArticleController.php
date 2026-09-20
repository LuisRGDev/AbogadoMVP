<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Services\HtmlSanitizer;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request): View
    {
        $category = $request->string('categoria')->trim()->value() ?: null;
        $term = $request->string('q')->trim()->limit(80, '')->value() ?: null;

        return view('pages.insights', [
            'articles' => Article::published()
                ->inCategory($category)
                ->matching($term)
                ->latest('date')
                ->paginate(9)
                ->withQueryString(),
            'categories' => Article::published()
                ->selectRaw('category, count(*) as total')
                ->whereNotNull('category')
                ->groupBy('category')
                ->orderBy('category')
                ->pluck('total', 'category'),
            'activeCategory' => $category,
            'term' => $term,
        ]);
    }

    public function show(Article $article, HtmlSanitizer $sanitizer): View
    {
        abort_unless($article->is_published && $article->date->lte(today()), 404);

        return view('pages.article-show', [
            'article' => $article,
            'content' => $sanitizer->withHeadingIds((string) $article->body),
            'others' => $article->related(3),
        ]);
    }
}
