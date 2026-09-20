<?php

namespace Tests\Feature;

use App\Enums\ContactStatus;
use App\Enums\ContactType;
use App\Mail\LeadReceipt;
use App\Mail\NewLeadReceived;
use App\Models\Contact;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DatabaseSeeder::class);
        Mail::fake();
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function payload(array $overrides = []): array
    {
        return array_merge([
            'type' => 'contact',
            'name' => 'María López',
            'email' => 'maria@example.com',
            'phone' => '55 1234 5678',
            'area' => 'Derecho Corporativo',
            'message' => 'Necesito asesoría para constituir una sociedad.',
            'consent' => '1',
            '_t' => Crypt::encryptString((string) now()->subMinute()->timestamp),
        ], $overrides);
    }

    public function test_it_stores_the_request_and_sends_both_emails(): void
    {
        $this->post(route('contact.store'), $this->payload())
            ->assertRedirect(route('contact.form').'#formulario')
            ->assertSessionHas('success');

        $this->assertDatabaseHas('contacts', ['email' => 'maria@example.com', 'type' => 'contact', 'status' => 'pending']);
        $this->assertSame(ContactStatus::Pending, Contact::first()->status);

        Mail::assertSent(NewLeadReceived::class, fn (NewLeadReceived $mail): bool => $mail->hasTo('contacto@despacho.example') && $mail->hasReplyTo('maria@example.com'));
        Mail::assertSent(LeadReceipt::class, fn (LeadReceipt $mail): bool => $mail->hasTo('maria@example.com'));
    }

    public function test_it_validates_required_fields_in_spanish(): void
    {
        $this->from(route('contact.form'))
            ->post(route('contact.store'), ['type' => 'contact'])
            ->assertSessionHasErrors(['name', 'email', 'message', 'consent']);

        $this->assertDatabaseCount('contacts', 0);
        Mail::assertNothingSent();

        $this->get(route('contact.form'))->assertOk();
    }

    public function test_privacy_consent_is_required(): void
    {
        $this->post(route('contact.store'), $this->payload(['consent' => null]))
            ->assertSessionHasErrors(['consent' => 'Debe confirmar que ha leído el Aviso de Privacidad.']);
    }

    public function test_an_appointment_requires_date_slot_and_mode(): void
    {
        $this->post(route('contact.store'), $this->payload(['type' => 'appointment']))
            ->assertSessionHasErrors(['preferred_date', 'preferred_slot', 'meeting_mode']);

        $this->post(route('contact.store'), $this->payload([
            'type' => 'appointment',
            'preferred_date' => now()->addDays(3)->toDateString(),
            'preferred_slot' => 'tarde',
            'meeting_mode' => 'videollamada',
        ]))->assertSessionHasNoErrors();

        $contact = Contact::first();
        $this->assertSame(ContactType::Appointment, $contact->type);
        $this->assertSame('Tarde (14:00 – 18:00)', $contact->slotLabel());
    }

    public function test_an_appointment_cannot_be_in_the_past_or_too_far_ahead(): void
    {
        $base = ['type' => 'appointment', 'preferred_slot' => 'manana', 'meeting_mode' => 'presencial'];

        $this->post(route('contact.store'), $this->payload($base + ['preferred_date' => now()->subDay()->toDateString()]))
            ->assertSessionHasErrors('preferred_date');
        $this->post(route('contact.store'), $this->payload($base + ['preferred_date' => now()->addDays(400)->toDateString()]))
            ->assertSessionHasErrors('preferred_date');
    }

    public function test_honeypot_submissions_are_silently_discarded(): void
    {
        $this->post(route('contact.store'), $this->payload(['website' => 'http://spam.example']))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseCount('contacts', 0);
        Mail::assertNothingSent();
    }

    public function test_instant_submissions_are_discarded_as_bots(): void
    {
        $this->post(route('contact.store'), $this->payload(['_t' => Crypt::encryptString((string) now()->timestamp)]))
            ->assertRedirect();

        $this->assertDatabaseCount('contacts', 0);
    }

    public function test_it_responds_with_json_for_fetch_requests(): void
    {
        $this->postJson(route('contact.store'), $this->payload())
            ->assertOk()
            ->assertJsonStructure(['message']);

        $this->postJson(route('contact.store'), ['type' => 'contact'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'email', 'message', 'consent']);
    }

    public function test_the_form_is_rate_limited(): void
    {
        for ($i = 0; $i < 3; $i++) {
            $this->post(route('contact.store'), $this->payload())->assertRedirect();
        }

        $this->post(route('contact.store'), $this->payload())->assertTooManyRequests();
    }

    public function test_the_form_does_not_store_the_raw_ip_address(): void
    {
        $this->post(route('contact.store'), $this->payload());

        $hash = Contact::first()->ip_hash;
        $this->assertSame(64, strlen($hash));
        $this->assertNotSame('127.0.0.1', $hash);
    }

    public function test_appointment_link_preselects_the_appointment_type(): void
    {
        $this->get('/agendar')->assertRedirect('/contacto?tipo=cita');
        $this->get('/contacto?tipo=cita&area=Derecho+Laboral')
            ->assertSee('value="appointment" checked', false)
            ->assertSee('value="Derecho Laboral" selected', false);
    }
}
