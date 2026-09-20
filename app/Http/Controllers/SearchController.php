<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Attorney;
use App\Models\PracticeArea;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __invoke(Request $request): View
    {
        $term = $request->string('q')->trim()->limit(80, '')->value();
        $results = ['areas' => collect(), 'articles' => collect(), 'attorneys' => collect()];

        if (mb_strlen($term) >= 2) {
            $like = '%'.addcslashes($term, '%_\\').'%';

            $results = [
                'areas' => PracticeArea::active()->ordered()
                    ->where(fn (Builder $q) => $q->where('title', 'like', $like)->orWhere('short', 'like', $like)->orWhere('overview', 'like', $like))
                    ->limit(6)->get(),
                'articles' => Article::published()->matching($term)->latest('date')->limit(8)->get(),
                'attorneys' => Attorney::active()->ordered()
                    ->where(fn (Builder $q) => $q->where('name', 'like', $like)->orWhere('position', 'like', $like)->orWhere('bio_short', 'like', $like))
                    ->limit(6)->get(),
            ];
        }

        return view('pages.search', [
            'term' => $term,
            'results' => $results,
            'total' => collect($results)->sum(fn ($items): int => $items->count()),
        ]);
    }
}
