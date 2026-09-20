<?php

namespace Tests\Feature;

use App\Filament\Pages\SiteSettings;
use App\Filament\Resources\ArticleResource;
use App\Filament\Resources\AttorneyResource;
use App\Filament\Resources\ContactResource;
use App\Filament\Resources\PageResource;
use App\Filament\Resources\PracticeAreaResource;
use App\Models\Article;
use App\Models\Attorney;
use App\Models\Contact;
use App\Models\Page;
use App\Models\PracticeArea;
use App\Models\Setting;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DatabaseSeeder::class);
        $this->actingAs(User::factory()->create());
    }

    /**
     * @return array<string, array{0: string}>
     */
    public static function adminPages(): array
    {
        return [
            'panel' => ['/admin'],
            'solicitudes' => ['/admin/contacts'],
            'areas' => ['/admin/practice-areas'],
            'crear area' => ['/admin/practice-areas/create'],
            'abogados' => ['/admin/attorneys'],
            'crear abogado' => ['/admin/attorneys/create'],
            'articulos' => ['/admin/articles'],
            'crear articulo' => ['/admin/articles/create'],
            'casos' => ['/admin/experience-cases'],
            'testimonios' => ['/admin/testimonials'],
            'faqs' => ['/admin/faqs'],
            'paginas' => ['/admin/pages'],
            'configuracion' => ['/admin/site-settings'],
        ];
    }

    #[DataProvider('adminPages')]
    public function test_admin_page_renders(string $uri): void
    {
        $this->get($uri)->assertOk();
    }

    public function test_viewing_a_request_marks_it_as_read(): void
    {
        $contact = Contact::factory()->appointment()->create();
        $this->assertNull($contact->read_at);

        $this->get(ContactResource::getUrl('view', ['record' => $contact]))->assertOk()->assertSee($contact->name);

        $this->assertNotNull($contact->fresh()->read_at);
    }

    public function test_editing_records_renders_the_form(): void
    {
        $this->get(PracticeAreaResource::getUrl('edit', ['record' => PracticeArea::first()]))->assertOk();
        $this->get(AttorneyResource::getUrl('edit', ['record' => Attorney::first()]))->assertOk();
        $this->get(ArticleResource::getUrl('edit', ['record' => Article::first()]))->assertOk();
        $this->get(PageResource::getUrl('edit', ['record' => Page::first()]))->assertOk();
    }

    public function test_site_settings_are_saved_and_reflected_on_the_site(): void
    {
        Livewire::test(SiteSettings::class)
            ->fillForm(['firm_name' => 'García y Asociados', 'phone' => '+52 55 9876 5432', 'stats' => [['value' => '25', 'label' => 'años de trayectoria']]])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame('García y Asociados', Setting::get('firm_name'));

        $this->get('/')->assertSee('García y Asociados')->assertSee('años de trayectoria');
    }

    public function test_export_neutralizes_spreadsheet_formulas(): void
    {
        $contact = Contact::factory()->create(['name' => '=HYPERLINK("http://evil.example")']);

        ob_start();
        ContactResource::exportCsv(collect([$contact]))->sendContent();
        $csv = ob_get_clean();

        $this->assertStringContainsString("\"'=HYPERLINK", $csv);
    }
}
