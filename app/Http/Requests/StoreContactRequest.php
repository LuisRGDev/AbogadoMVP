<?php

namespace App\Http\Requests;

use App\Enums\ContactType;
use App\Models\PracticeArea;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreContactRequest extends FormRequest
{
    public const OTHER_AREA = 'Otra / No estoy seguro(a)';

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'type' => $this->input('type', ContactType::Contact->value),
            'email' => Str::lower(trim((string) $this->input('email'))),
            'name' => trim((string) $this->input('name')),
        ]);
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $areas = PracticeArea::active()->pluck('title')->push(self::OTHER_AREA)->all();
        $isAppointment = Rule::requiredIf(fn (): bool => $this->input('type') === ContactType::Appointment->value);

        return [
            'type' => ['required', Rule::enum(ContactType::class)],
            'name' => ['required', 'string', 'min:2', 'max:120'],
            'email' => ['required', 'email:rfc', 'max:190'],
            'phone' => ['nullable', 'string', 'max:40', 'regex:/^[0-9+()\-\s.]{7,40}$/'],
            'area' => ['nullable', 'string', Rule::in($areas)],
            'message' => ['required', 'string', 'min:10', 'max:3000'],
            'preferred_date' => [$isAppointment, 'nullable', 'date', 'after_or_equal:today', 'before_or_equal:'.now()->addDays((int) config('despacho.appointment.max_days_ahead'))->toDateString()],
            'preferred_slot' => [$isAppointment, 'nullable', Rule::in(array_keys(config('despacho.appointment.slots')))],
            'meeting_mode' => [$isAppointment, 'nullable', Rule::in(array_keys(config('despacho.appointment.modes')))],
            'consent' => ['accepted'],
            'website' => ['nullable', 'string'],
            '_t' => ['nullable', 'string'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'nombre',
            'email' => 'correo electrónico',
            'phone' => 'teléfono',
            'area' => 'área de interés',
            'message' => 'mensaje',
            'preferred_date' => 'fecha preferida',
            'preferred_slot' => 'horario preferido',
            'meeting_mode' => 'modalidad',
            'consent' => 'aviso de privacidad',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'consent.accepted' => 'Debe confirmar que ha leído el Aviso de Privacidad.',
            'phone.regex' => 'Ingrese un teléfono válido o déjelo en blanco.',
            'message.min' => 'Describa brevemente su consulta (mínimo :min caracteres).',
            'preferred_date.after_or_equal' => 'Elija una fecha a partir de hoy.',
            'preferred_date.before_or_equal' => 'Elija una fecha dentro de los próximos '.config('despacho.appointment.max_days_ahead').' días.',
        ];
    }
}
