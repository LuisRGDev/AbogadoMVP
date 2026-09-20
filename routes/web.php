<?php

use App\Http\Controllers\AreaController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AttorneyController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LegalController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\RobotsController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\ToolController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::get('/nosotros', [PageController::class, 'nosotros'])->name('nosotros');

Route::get('/areas', [AreaController::class, 'index'])->name('areas.index');
Route::get('/areas/{area:slug}', [AreaController::class, 'show'])->name('areas.show');

Route::get('/equipo', [TeamController::class, 'index'])->name('team');
Route::get('/equipo/{attorney:slug}', [AttorneyController::class, 'show'])->name('team.show');
Route::get('/equipo/{attorney:slug}/vcard', [AttorneyController::class, 'vcard'])->name('team.vcard');

Route::get('/experiencia', [ExperienceController::class, 'index'])->name('experience');

Route::get('/insights', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/insights/{article:slug}', [ArticleController::class, 'show'])->name('articles.show');

Route::get('/herramientas/calculadora-laboral', [ToolController::class, 'severance'])->name('tools.severance');
Route::post('/herramientas/calculadora-laboral', [ToolController::class, 'calculateSeverance'])
    ->middleware('throttle:30,1')
    ->name('tools.severance.calculate');

Route::get('/buscar', SearchController::class)->middleware('throttle:60,1')->name('search');

Route::get('/contacto', [ContactController::class, 'form'])->name('contact.form');
Route::post('/contacto', [ContactController::class, 'store'])->middleware('throttle:contact')->name('contact.store');
Route::redirect('/agendar', '/contacto?tipo=cita')->name('appointment');

Route::get('/privacidad', [LegalController::class, 'privacidad'])->name('legal.privacidad');
Route::get('/terminos', [LegalController::class, 'terminos'])->name('legal.terminos');
Route::get('/disclaimer', [LegalController::class, 'disclaimer'])->name('legal.disclaimer');

Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
Route::get('/robots.txt', RobotsController::class)->name('robots');
