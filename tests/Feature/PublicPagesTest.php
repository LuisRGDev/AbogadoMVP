<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Attorney;
use App\Models\PracticeArea;
use App\Models\Setting;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DatabaseSeeder::class);
    }

    /**
     * @return array<string, array{0: string}>
     */
    public static function publicRoutes(): array
    {
        return [
            'inicio' => ['/'],
            'nosotros' => ['/nosotros'],
            'areas' => ['/areas'],
            'area' => ['/areas/corporativo'],
            'equipo' => ['/equipo'],
            'perfil' => ['/equipo/socio-1'],
            'experiencia' => ['/experiencia'],
            'insights' => ['/insights'],
            'insights filtrado' => ['/insights?categoria=Contratos'],
            'articulo' => ['/insights/que-revisar-antes-de-firmar-un-contrato'],
            'calculadora' => ['/herramientas/calculadora-laboral'],
            'busqueda' => ['/buscar?q=contrato'],
            'contacto' => ['/contacto'],
            'cita' => ['/contacto?tipo=cita'],
            'privacidad' => ['/privacidad'],
            'terminos' => ['/terminos'],
            'disclaimer' => ['/disclaimer'],
        ];
    }

    #[DataProvider('publicRoutes')]
    public function test_public_page_renders(string $uri): void
    {
        $this->get($uri)->assertOk();
    }

    public function test_highlighted_words_in_titles_are_rendered_not_swallowed(): void
    {
        $this->get('/')
            ->assertSee('<em>importan.</em>', false)
            ->assertSee('<em>con criterio</em>', false);
    }

    public function test_home_lists_every_active_practice_area(): void
    {
        $inactive = PracticeArea::factory()->inactive()->create(['title' => 'Derecho Oculto de Prueba']);

        $this->get('/')
            ->assertSee('Derecho Corporativo')
            ->assertDontSee($inactive->title);
    }

    public function test_inactive_area_returns_not_found(): void
    {
        $area = PracticeArea::factory()->inactive()->create();

        $this->get(route('areas.show', $area))->assertNotFound();
    }

    public function test_inactive_attorney_returns_not_found(): void
    {
        $attorney = Attorney::factory()->inactive()->create();

        $this->get(route('team.show', $attorney))->assertNotFound();
        $this->get(route('team.vcard', $attorney))->assertNotFound();
    }

    public function test_draft_and_scheduled_articles_are_not_public(): void
    {
        $draft = Article::factory()->draft()->create();
        $scheduled = Article::factory()->scheduled()->create();

        $this->get(route('articles.show', $draft))->assertNotFound();
        $this->get(route('articles.show', $scheduled))->assertNotFound();
        $this->get(route('articles.index'))->assertDontSee($draft->title)->assertDontSee($scheduled->title);
    }

    public function test_insights_filter_by_category_and_search(): void
    {
        Article::factory()->create(['title' => 'Guía sobre marcas registradas', 'category' => 'Propiedad intelectual']);

        $this->get('/insights?categoria=Propiedad+intelectual')
            ->assertSee('Guía sobre marcas registradas')
            ->assertDontSee('Claves para estructurar correctamente');

        $this->get('/insights?q=marcas')
            ->assertSee('Guía sobre marcas registradas')
            ->assertDontSee('Claves para estructurar correctamente');
    }

    public function test_article_body_is_html_not_raw_json(): void
    {
        $this->get('/insights/que-revisar-antes-de-firmar-un-contrato')
            ->assertSee('<h2 id="partes-y-objeto">Partes y objeto</h2>', false)
            ->assertDontSee('"heading"', false);
    }

    public function test_article_body_is_sanitized(): void
    {
        $article = Article::factory()->create(['body' => '<p onclick="x()">Hola</p><script>alert(1)</script><a href="javascript:alert(1)">malo</a>']);

        $this->get(route('articles.show', $article))
            ->assertSee('<p>Hola</p>', false)
            ->assertDontSee('<script>alert(1)</script>', false)
            ->assertDontSee('javascript:alert', false)
            ->assertDontSee('onclick', false);
    }

    public function test_attorney_vcard_download(): void
    {
        $response = $this->get('/equipo/socio-1/vcard');

        $response->assertOk()->assertHeader('Content-Type', 'text/vcard; charset=utf-8');
        $this->assertStringContainsString('BEGIN:VCARD', $response->getContent());
    }

    public function test_every_practice_area_has_its_own_cover_image(): void
    {
        $covers = PracticeArea::all()->map(fn (PracticeArea $area): string => $area->coverUrl());

        $this->assertCount($covers->count(), $covers->unique(), 'Hay áreas que comparten la misma portada.');
    }

    public function test_sitemap_lists_content_and_robots_points_to_it(): void
    {
        $sitemap = $this->get('/sitemap.xml')->assertOk();
        $sitemap->assertSee(route('areas.show', 'corporativo'), false)
            ->assertSee(route('team.show', 'socio-1'), false)
            ->assertSee(route('tools.severance'), false);
        $this->assertNotFalse(simplexml_load_string($sitemap->getContent()));

        $this->get('/robots.txt')->assertOk()->assertSee('Sitemap: '.url('/sitemap.xml'), false)->assertDontSee('Disallow: /contacto', false);
    }

    public function test_custom_not_found_page(): void
    {
        $this->get('/pagina-que-no-existe')
            ->assertNotFound()
            ->assertSee('Esta página', false)
            ->assertSee('no existe.', false);
    }

    public function test_search_finds_areas_and_requires_two_characters(): void
    {
        $this->get('/buscar?q=corporativo')->assertSee('Derecho Corporativo');
        $this->get('/buscar?q=a')->assertSee('Escriba al menos dos letras');
    }

    public function test_call_buttons_use_the_configured_phone_and_hide_without_one(): void
    {
        $this->get('/')->assertSee('class="call-fab" href="tel:+525500000000"', false)->assertSee('Llamar ahora');

        Setting::set('phone', '');

        $this->get('/')->assertDontSee('call-fab', false);
    }
}
