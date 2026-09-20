<?php

namespace App\Providers;

use App\Models\PracticeArea;
use App\Support\Site;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Site::class);
    }

    public function boot(): void
    {
        if (str_starts_with((string) config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }

        Paginator::defaultView('pagination.despacho');

        RateLimiter::for('contact', fn (Request $request): array => [
            Limit::perMinute(3)->by($request->ip()),
            Limit::perHour(12)->by($request->ip()),
        ]);

        View::composer('components.layouts.app', function ($view): void {
            $view->with('footerAreas', PracticeArea::active()->ordered()->limit(6)->get(['id', 'slug', 'title']));
        });
    }
}
