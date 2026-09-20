<?php

namespace App\Http\Requests;

use App\Services\SeveranceCalculator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SeveranceCalculationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'start_date' => ['required', 'date', 'after_or_equal:1970-01-01', 'before_or_equal:end_date'],
            'end_date' => ['required', 'date', 'before_or_equal:'.now()->addYear()->toDateString()],
            'salary_amount' => ['required', 'numeric', 'min:1', 'max:10000000'],
            'salary_period' => ['required', Rule::in(['daily', 'weekly', 'biweekly', 'monthly'])],
            'separation_type' => ['required', Rule::in([SeveranceCalculator::DISMISSAL_UNJUSTIFIED, SeveranceCalculator::DISMISSAL_JUSTIFIED, SeveranceCalculator::RESIGNATION])],
            'zone' => ['required', Rule::in(['general', 'frontera'])],
            'vacation_days_taken' => ['nullable', 'integer', 'min:0', 'max:60'],
            'aguinaldo_days' => ['nullable', 'integer', 'min:15', 'max:90'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'start_date' => 'fecha de ingreso',
            'end_date' => 'fecha de separación',
            'salary_amount' => 'sueldo',
            'salary_period' => 'periodicidad del sueldo',
            'separation_type' => 'tipo de separación',
            'zone' => 'zona salarial',
            'vacation_days_taken' => 'vacaciones disfrutadas',
            'aguinaldo_days' => 'días de aguinaldo',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'start_date.before_or_equal' => 'La fecha de ingreso no puede ser posterior a la de separación.',
            'end_date.before_or_equal' => 'La fecha de separación es demasiado lejana.',
        ];
    }
}
