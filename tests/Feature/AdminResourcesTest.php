<?php

namespace Tests\Feature;

use App\Enums\ContactStatus;
use App\Filament\Pages\SiteSettings;
use App\Filament\Resources\ArticleResource\Pages\CreateArticle;
use App\Filament\Resources\ArticleResource\Pages\EditArticle;
use App\Filament\Resources\ArticleResource\Pages\ListArticles;
use App\Filament\Resources\AttorneyResource\Pages\CreateAttorney;
use App\Filament\Resources\AttorneyResource\Pages\EditAttorney;
use App\Filament\Resources\AttorneyResource\Pages\ListAttorneys;
use App\Filament\Resources\ContactResource;
use App\Filament\Resources\ContactResource\Pages\EditContact;
use App\Filament\Resources\ContactResource\Pages\ListContacts;
use App\Filament\Resources\ContactResource\Pages\ViewContact;
use App\Filament\Resources\ExperienceCaseResource\Pages\CreateExperienceCase;
use App\Filament\Resources\FaqResource\Pages\CreateFaq;
use App\Filament\Resources\PageResource;
use App\Filament\Resources\PageResource\Pages\EditPage;
use App\Filament\Resources\PracticeAreaResource\Pages\CreatePracticeArea;
use App\Filament\Resources\PracticeAreaResource\Pages\EditPracticeArea;
use App\Filament\Resources\PracticeAreaResource\Pages\ListPracticeAreas;
use App\Filament\Resources\TestimonialResource\Pages\CreateTestimonial;
use App\Filament\Resources\TestimonialResource\Pages\ListTestimonials;
use App\Filament\Widgets\LatestLeads;
use App\Filament\Widgets\LaunchChecklist;
use App\Filament\Widgets\LeadsChart;
use App\Filament\Widgets\LeadStatsOverview;
use App\Models\Article;
use App\Models\Attorney;
use App\Models\Contact;
use App\Models\ExperienceCase;
use App\Models\Faq;
use App\Models\Page;
use App\Models\PracticeArea;
use App\Models\Setting;
use App\Models\Testimonial;
use App\Models\User;
use App\Services\SeveranceCalculator;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class AdminResourcesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DatabaseSeeder::class);
        $this->actingAs(User::factory()->create());
    }

    private function png(string $name = 'foto.png'): UploadedFile
    {
        $pixel = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==');

        return UploadedFile::fake()->createWithContent($name, $pixel);
    }

    /* ------------------------------ Artículos ------------------------------ */

    public function test_it_creates_and_edits_an_article(): void
    {
        Livewire::test(CreateArticle::class)
            ->fillForm([
                'title' => 'Nuevo artículo de prueba',
                'slug' => 'nuevo-articulo-de-prueba',
                'excerpt' => 'Resumen corto.',
                'body' => '<h2>Sección</h2><p>Contenido.</p>',
                'category' => 'Contratos',
                'date' => now()->subDay()->toDateString(),
                'is_published' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $article = Article::where('slug', 'nuevo-articulo-de-prueba')->firstOrFail();
        $this->get(route('articles.show', $article))->assertOk()->assertSee('Nuevo artículo de prueba');

        Livewire::test(EditArticle::class, ['record' => $article->getRouteKey()])
            ->fillForm(['title' => 'Título editado'])
            ->call('save')
            ->assertHasNoFormErrors();
        $this->assertSame('Título editado', $article->fresh()->title);
    }

    public function test_article_validation_and_unique_slug(): void
    {
        Livewire::test(CreateArticle::class)
            ->fillForm(['title' => null, 'slug' => 'que-revisar-antes-de-firmar-un-contrato', 'excerpt' => null, 'body' => null])
            ->call('create')
            ->assertHasFormErrors(['title' => 'required', 'excerpt' => 'required', 'body' => 'required', 'slug' => 'unique']);
    }

    public function test_article_bulk_publish_and_unpublish(): void
    {
        $articles = Article::factory()->count(2)->draft()->create();

        Livewire::test(ListArticles::class)
            ->callTableBulkAction('publish', $articles)
            ->callTableBulkAction('unpublish', $articles->take(1));

        $this->assertTrue($articles[1]->fresh()->is_published);
        $this->assertFalse($articles[0]->fresh()->is_published);
    }

    public function test_article_cover_image_can_be_uploaded(): void
    {
        Storage::fake('public');
        $article = Article::first();

        Livewire::test(EditArticle::class, ['record' => $article->getRouteKey()])
            ->fillForm(['featured' => $this->png()])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertTrue($article->fresh()->hasMedia('featured'));
        $this->assertTrue($article->fresh()->hasCustomCover());
    }

    /* ------------------------------ Abogados ------------------------------ */

    public function test_it_creates_an_attorney_with_lists_and_photo(): void
    {
        Storage::fake('public');

        Livewire::test(CreateAttorney::class)
            ->fillForm([
                'name' => 'Ana Torres Ruiz',
                'slug' => 'ana-torres-ruiz',
                'position' => 'Socia',
                'credentials' => 'Cédula 1234567',
                'bio_short' => 'Especialista en litigio.',
                'bio' => [['paragraph' => 'Primer párrafo.'], ['paragraph' => 'Segundo párrafo.']],
                'education' => [['item' => 'Licenciatura en Derecho — UNAM']],
                'experience' => [['item' => 'Socia — Despacho X']],
                'memberships' => [['item' => 'Barra Mexicana']],
                'languages' => [['item' => 'Español'], ['item' => 'Inglés']],
                'areas' => ['Derecho Corporativo'],
                'avatar' => $this->png('ana.png'),
                'is_active' => true,
                'is_demo' => false,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $attorney = Attorney::where('slug', 'ana-torres-ruiz')->firstOrFail();
        $this->assertSame(['Primer párrafo.', 'Segundo párrafo.'], $attorney->bio);
        $this->assertSame(['Español', 'Inglés'], $attorney->languages);
        $this->assertTrue($attorney->hasMedia('avatar'));

        $this->get(route('team.show', $attorney))->assertOk()->assertSee('Ana Torres Ruiz')->assertSee('Segundo párrafo.');
        $this->get(route('areas.show', 'corporativo'))->assertSee('Ana Torres Ruiz');
    }

    public function test_attorney_can_be_edited_hidden_and_reordered(): void
    {
        $attorney = Attorney::first();

        Livewire::test(EditAttorney::class, ['record' => $attorney->getRouteKey()])
            ->fillForm(['position' => 'Socio fundador', 'is_demo' => false])
            ->call('save')
            ->assertHasNoFormErrors();
        $this->assertSame('Socio fundador', $attorney->fresh()->position);

        Livewire::test(ListAttorneys::class)
            ->call('updateTableColumnState', 'is_active', $attorney->getKey(), false);
        $this->get(route('team.show', $attorney->fresh()))->assertNotFound();

        $ids = Attorney::orderByDesc('id')->pluck('id')->all();
        Livewire::test(ListAttorneys::class)->call('reorderTable', $ids);
        $this->assertSame($ids, Attorney::orderBy('sort_order')->pluck('id')->all());
    }

    /* ------------------------------ Áreas ------------------------------ */

    public function test_it_creates_and_edits_a_practice_area_with_process_and_faqs(): void
    {
        Livewire::test(CreatePracticeArea::class)
            ->fillForm([
                'title' => 'Derecho Fiscal',
                'slug' => 'fiscal',
                'short' => 'Planeación y defensa fiscal.',
                'overview' => 'Resumen del área fiscal.',
                'num' => '07',
                'icon' => 'scale',
                'matters' => [['item' => 'Auditorías'], ['item' => 'Devoluciones']],
                'needs' => [['item' => 'Defenderse ante el SAT']],
                'process' => [['title' => 'Diagnóstico', 'description' => 'Revisamos su caso.']],
                'faqs' => [['question' => '¿Cuánto tarda?', 'answer' => 'Depende del caso.']],
                'is_active' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $area = PracticeArea::where('slug', 'fiscal')->firstOrFail();
        $this->assertSame('Diagnóstico', $area->processSteps()[0]['title']);
        $this->assertSame('¿Cuánto tarda?', $area->faqItems()[0]['question']);
        $this->get(route('areas.show', $area))->assertOk()->assertSee('Auditorías')->assertSee('Diagnóstico');
        $this->get('/')->assertSee('Derecho Fiscal');

        Livewire::test(EditPracticeArea::class, ['record' => $area->getRouteKey()])
            ->fillForm(['short' => 'Descripción actualizada.'])
            ->call('save')
            ->assertHasNoFormErrors();
        $this->assertSame('Descripción actualizada.', $area->fresh()->short);
    }

    public function test_practice_area_cover_upload_replaces_the_illustration(): void
    {
        Storage::fake('public');
        $area = PracticeArea::first();
        $before = $area->coverUrl();

        Livewire::test(EditPracticeArea::class, ['record' => $area->getRouteKey()])
            ->fillForm(['cover' => $this->png('portada.png')])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertNotSame($before, $area->fresh()->coverUrl());
    }

    public function test_practice_area_can_be_hidden_from_the_list(): void
    {
        $area = PracticeArea::first();

        Livewire::test(ListPracticeAreas::class)->call('updateTableColumnState', 'is_active', $area->getKey(), false);

        $this->get(route('areas.show', $area))->assertNotFound();
        $this->get('/areas')->assertDontSee($area->title);
    }

    /* ------------------------ Testimonios, casos, FAQ ------------------------ */

    public function test_it_creates_testimonials_cases_and_faqs(): void
    {
        Livewire::test(CreateTestimonial::class)
            ->fillForm(['quote' => 'Excelente atención.', 'author' => 'Cliente Reservado', 'area' => 'Derecho Corporativo', 'is_active' => true, 'is_demo' => false])
            ->call('create')->assertHasNoFormErrors();
        $this->assertDatabaseHas('testimonials', ['author' => 'Cliente Reservado']);

        Livewire::test(CreateExperienceCase::class)
            ->fillForm(['matter' => 'Reestructura societaria', 'area' => 'Derecho Corporativo', 'challenge' => 'Reto', 'strategy' => 'Estrategia', 'result' => 'Resultado', 'is_active' => true, 'is_demo' => false])
            ->call('create')->assertHasNoFormErrors();
        $this->assertDatabaseHas('experience_cases', ['matter' => 'Reestructura societaria']);

        Livewire::test(CreateFaq::class)
            ->fillForm(['question' => '¿Atienden en fines de semana?', 'answer' => 'Solo con cita previa.', 'is_active' => true])
            ->call('create')->assertHasNoFormErrors();
        $this->assertSame('general', Faq::where('question', '¿Atienden en fines de semana?')->value('group'));

        $this->get('/experiencia')->assertSee('Reestructura societaria')->assertSee('Cliente Reservado');
        $this->get('/contacto')->assertSee('¿Atienden en fines de semana?');
    }

    public function test_testimonial_validation_and_toggle(): void
    {
        Livewire::test(CreateTestimonial::class)->fillForm(['quote' => null, 'author' => null])->call('create')
            ->assertHasFormErrors(['quote' => 'required', 'author' => 'required']);

        $testimonial = Testimonial::first();
        Livewire::test(ListTestimonials::class)->call('updateTableColumnState', 'is_active', $testimonial->getKey(), false);
        $this->assertFalse($testimonial->fresh()->is_active);
    }

    /* ------------------------------ Páginas ------------------------------ */

    public function test_legal_page_content_can_be_edited_but_not_created_or_deleted(): void
    {
        $page = Page::where('slug', 'privacidad')->firstOrFail();

        Livewire::test(EditPage::class, ['record' => $page->getRouteKey()])
            ->fillForm(['content' => '<h2>Responsable</h2><p>Texto definitivo sin marcadores.</p>'])
            ->call('save')->assertHasNoFormErrors();

        $this->get('/privacidad')->assertSee('Texto definitivo sin marcadores.')->assertDontSee('Texto de ejemplo');
        $this->assertFalse(PageResource::canCreate());
        $this->assertFalse(PageResource::canDelete($page));
    }

    /* ------------------------------ Solicitudes ------------------------------ */

    public function test_contact_table_filters_and_row_action(): void
    {
        $pending = Contact::factory()->create();
        $appointment = Contact::factory()->appointment()->create();
        $closed = Contact::factory()->create(['status' => ContactStatus::Closed]);

        Livewire::test(ListContacts::class)
            ->assertCanSeeTableRecords([$pending, $appointment, $closed])
            ->filterTable('status', 'closed')->assertCanSeeTableRecords([$closed])->assertCanNotSeeTableRecords([$pending])
            ->resetTableFilters()
            ->filterTable('upcoming')->assertCanSeeTableRecords([$appointment])->assertCanNotSeeTableRecords([$pending, $closed])
            ->resetTableFilters()
            ->callTableAction('mark_contacted', $pending);

        $this->assertSame(ContactStatus::Contacted, $pending->fresh()->status);
    }

    public function test_contact_bulk_actions_and_csv_download(): void
    {
        $contacts = Contact::factory()->count(3)->create();

        Livewire::test(ListContacts::class)
            ->callTableBulkAction('mark_contacted', $contacts)
            ->callTableBulkAction('export', $contacts)
            ->assertFileDownloaded();
        $this->assertSame(3, Contact::where('status', ContactStatus::Contacted)->count());

        Livewire::test(ListContacts::class)->callTableBulkAction('mark_closed', $contacts);
        $this->assertSame(3, Contact::where('status', ContactStatus::Closed)->count());
    }

    public function test_contact_detail_actions_and_notes(): void
    {
        $contact = Contact::factory()->appointment()->create();

        Livewire::test(ViewContact::class, ['record' => $contact->getKey()])
            ->assertSee($contact->email)
            ->assertSee('Videollamada')
            ->callAction('contacted');
        $this->assertSame(ContactStatus::Contacted, $contact->fresh()->status);

        Livewire::test(ViewContact::class, ['record' => $contact->getKey()])->callAction('close');
        $this->assertSame(ContactStatus::Closed, $contact->fresh()->status);

        Livewire::test(EditContact::class, ['record' => $contact->getKey()])
            ->fillForm(['status' => 'pending', 'notes' => 'Llamar mañana a las 10.'])
            ->call('save')->assertHasNoFormErrors();
        $this->assertSame('Llamar mañana a las 10.', $contact->fresh()->notes);
        $this->assertSame(ContactStatus::Pending, $contact->fresh()->status);
    }

    public function test_navigation_badge_counts_pending_requests(): void
    {
        Contact::factory()->count(2)->create();
        Contact::factory()->create(['status' => ContactStatus::Closed]);

        $this->assertSame('2', ContactResource::getNavigationBadge());
    }

    /* ------------------------------ Widgets del escritorio ------------------------------ */

    public function test_dashboard_widgets_render_with_data(): void
    {
        $lead = Contact::factory()->appointment()->create();

        Livewire::test(LeadStatsOverview::class)->assertOk()->assertSee('Pendientes de atender')->assertSee('Citas próximas');
        Livewire::test(LeadsChart::class)->assertOk();
        Livewire::test(LatestLeads::class)->assertCanSeeTableRecords([$lead]);
        Livewire::test(LaunchChecklist::class)->assertSee('Antes de publicar');
    }

    public function test_launch_checklist_reflects_real_configuration(): void
    {
        $widget = new LaunchChecklist;
        $done = fn () => collect($widget->getChecklist())->firstWhere('label', 'Nombre del despacho')['done'];
        $this->assertFalse($done());
        $before = $widget->getProgress();

        Setting::set('firm_name', 'García y Asociados');

        $this->assertTrue($done());
        $this->assertGreaterThan($before, $widget->getProgress());
    }

    /* ------------------------------ Configuración del sitio ------------------------------ */

    public function test_site_settings_validate_urls_and_fall_back_to_defaults_when_cleared(): void
    {
        Livewire::test(SiteSettings::class)
            ->fillForm(['linkedin' => 'no-es-una-url', 'firm_name' => 'Despacho Uno'])
            ->call('save')
            ->assertHasFormErrors(['linkedin' => 'url']);

        Livewire::test(SiteSettings::class)
            ->fillForm(['firm_name' => 'Despacho Uno', 'linkedin' => 'https://www.linkedin.com/company/uno', 'hero_title' => "Línea uno\nLínea *dos*", 'whatsapp_number' => '', 'calculator_min_wage' => '400'])
            ->call('save')->assertHasNoFormErrors();

        $home = $this->get('/');
        $home->assertSee('Despacho Uno')->assertSee('linkedin.com/company/uno', false)->assertSee('<em>dos</em>', false);
        $home->assertDontSee('wa.me', false);
        $this->assertEqualsWithDelta(400.0, app(SeveranceCalculator::class)->minimumWage('general'), 0.001);

        Livewire::test(SiteSettings::class)->fillForm(['hero_title' => ''])->call('save');
        $this->get('/')->assertSee('<em>importan.</em>', false);
    }

    public function test_site_settings_page_restores_saved_values_in_the_form(): void
    {
        Setting::set('email', 'hola@bufete.mx');

        Livewire::test(SiteSettings::class)->assertFormSet(['email' => 'hola@bufete.mx']);
    }

    public function test_deleted_records_disappear_from_the_public_site(): void
    {
        $testimonial = Testimonial::first();
        $case = ExperienceCase::first();
        $testimonial->delete();
        $case->delete();

        $this->get('/experiencia')->assertDontSee($testimonial->quote)->assertDontSee($case->matter);
    }
}
