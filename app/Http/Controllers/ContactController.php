<?php

namespace App\Http\Controllers;

use App\Enums\ContactType;
use App\Http\Requests\StoreContactRequest;
use App\Mail\LeadReceipt;
use App\Mail\NewLeadReceived;
use App\Models\Contact;
use App\Models\Faq;
use App\Models\PracticeArea;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;

class ContactController extends Controller
{
    /** Segundos mínimos entre la carga del formulario y su envío (los bots envían al instante). */
    private const MIN_FILL_SECONDS = 3;

    public function form(Request $request): View
    {
        $type = $request->query('tipo') === 'cita' ? ContactType::Appointment : ContactType::Contact;

        return view('pages.contact', [
            'areas' => PracticeArea::active()->ordered()->get(['id', 'title', 'slug']),
            'faqs' => Faq::active()->byGroup('general')->ordered()->get(),
            'initialType' => $type,
            'preselectedArea' => (string) $request->query('area', ''),
            'formToken' => Crypt::encryptString((string) now()->timestamp),
        ]);
    }

    public function store(StoreContactRequest $request): RedirectResponse|JsonResponse
    {
        $data = $request->validated();

        if ($this->looksLikeBot($request)) {
            return $this->succeeded($request);
        }

        $contact = Contact::create([
            ...Arr::except($data, ['consent', 'website', '_t']),
            'source' => Str::limit((string) $request->headers->get('referer'), 190, ''),
            'ip_hash' => hash('sha256', $request->ip().config('app.key')),
        ]);

        dispatch(fn () => $this->notify($contact))->afterResponse();

        return $this->succeeded($request);
    }

    private function looksLikeBot(StoreContactRequest $request): bool
    {
        if (filled($request->input('website'))) {
            return true;
        }

        try {
            $renderedAt = (int) Crypt::decryptString((string) $request->input('_t'));
        } catch (Throwable) {
            return false;
        }

        return now()->timestamp - $renderedAt < self::MIN_FILL_SECONDS;
    }

    private function succeeded(Request $request): RedirectResponse|JsonResponse
    {
        $message = 'Hemos recibido su solicitud. Nos pondremos en contacto en el horario de atención.';

        if ($request->wantsJson()) {
            return response()->json(['message' => $message]);
        }

        return redirect()->to(route('contact.form').'#formulario')->with('success', $message);
    }

    private function notify(Contact $contact): void
    {
        try {
            if ($to = site()->leadsEmail()) {
                Mail::to($to)->send(new NewLeadReceived($contact));
            }
            Mail::to($contact->email)->send(new LeadReceipt($contact));
        } catch (Throwable $exception) {
            report($exception);
        }
    }
}
