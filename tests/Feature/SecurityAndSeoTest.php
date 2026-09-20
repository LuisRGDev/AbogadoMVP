<?php

namespace Tests\Feature;

use App\Models\Setting;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityAndSeoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DatabaseSeeder::class);
    }

    public function test_public_pages_send_security_headers_and_a_strict_csp(): void
    {
        $response = $this->get('/');

        $response->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('X-Frame-Options', 'SAMEORIGIN')
            ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');

        $csp = $response->headers->get('Content-Security-Policy');
        $this->assertStringContainsString("script-src 'self'", $csp);
        $this->assertStringNotContainsString("script-src 'self' 'unsafe", $csp);
        $this->assertStringContainsString("object-src 'none'", $csp);
        $this->assertStringContainsString("frame-ancestors 'self'", $csp);
    }

    public function test_the_admin_panel_is_not_restricted_by_the_public_csp(): void
    {
        $this->get('/admin/login')->assertOk()->assertHeaderMissing('Content-Security-Policy');
    }

    public function test_the_admin_panel_requires_authentication(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
        $this->get('/admin/contacts')->assertRedirect('/admin/login');
    }

    public function test_pages_have_canonical_open_graph_and_structured_data(): void
    {
        $this->get('/')
            ->assertSee('<link rel="canonical" href="'.url('/').'">', false)
            ->assertSee('property="og:image"', false)
            ->assertSee('"@type":"LegalService"', false)
            ->assertSee('"@type":"WebSite"', false);

        $this->get('/insights/que-revisar-antes-de-firmar-un-contrato')
            ->assertSee('"@type":"Article"', false)
            ->assertSee('"@type":"BreadcrumbList"', false);

        $this->get('/areas/corporativo')->assertSee('"@type":"FAQPage"', false);
    }

    public function test_titles_use_the_firm_name_once(): void
    {
        $html = $this->get('/areas')->getContent();

        preg_match('#<title>(.*?)</title>#s', $html, $match);
        $this->assertSame(1, substr_count($match[1], '[NOMBRE DEL DESPACHO]'));
        $this->assertStringStartsWith('Áreas de práctica', html_entity_decode($match[1]));
    }

    public function test_search_and_filtered_listings_are_not_indexed(): void
    {
        $this->get('/buscar?q=contrato')->assertSee('<meta name="robots" content="noindex,follow">', false);
        $this->get('/insights?categoria=Contratos')->assertSee('noindex,follow', false);
        $this->get('/insights')->assertDontSee('noindex', false);
    }

    public function test_user_content_in_settings_is_escaped(): void
    {
        Setting::set('firm_name', '<script>alert(1)</script>Despacho');

        $this->get('/')->assertDontSee('<script>alert(1)</script>Despacho', false)
            ->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;Despacho', false);
    }

    public function test_settings_cache_is_flushed_when_a_setting_changes(): void
    {
        $this->get('/')->assertSee('[NOMBRE DEL DESPACHO]');

        Setting::set('firm_name', 'García y Asociados');

        $this->get('/')->assertSee('García y Asociados')->assertDontSee('[NOMBRE DEL DESPACHO]');
    }
}
