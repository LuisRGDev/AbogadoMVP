@php
    $site = site();
    $money = fn (float $value): string => '$'.number_format($value, 2);
    $errorFor = fn (string $field) => $errors->first($field);
    $type = old('separation_type', 'injustificado');
    $schema = [
        breadcrumb_schema([['Inicio', url('/')], ['Calculadora laboral', route('tools.severance')]]),
        [
            '@context' => 'https://schema.org',
            '@type' => 'WebApplication',
            'name' => 'Calculadora de finiquito y liquidación laboral',
            'applicationCategory' => 'BusinessApplication',
            'operatingSystem' => 'Web',
            'inLanguage' => 'es-MX',
            'offers' => ['@type' => 'Offer', 'price' => '0', 'priceCurrency' => 'MXN'],
            'url' => route('tools.severance'),
        ],
    ];
@endphp

<x-layouts.app
    title="Calculadora de finiquito y liquidación laboral"
    description="Estime su finiquito o liquidación laboral en México: aguinaldo, vacaciones, prima vacacional, prima de antigüedad e indemnización. Herramienta gratuita y confidencial."
    :image="$site->image('pages.herramientas')"
    :schema="$schema">
    <x-page-hero
        eyebrow="Herramientas"
        title="Calculadora de *finiquito y liquidación.*"
        text="Obtenga una estimación orientativa de lo que le podría corresponder al terminar una relación laboral en México. Sin registro y sin guardar sus datos."
        :image="$site->image('pages.herramientas')"
        :crumbs="[['Calculadora laboral', null]]" />

    <section class="section on-light" aria-labelledby="calc-title">
        <div class="container calc">
            <div class="calc__form">
                <h2 class="h3" id="calc-title">Sus datos</h2>
                <form class="form" method="post" action="{{ route('tools.severance.calculate') }}" novalidate>
                    @csrf
                    @if($errors->any())<p class="form__status is-error" role="alert">Revise los campos marcados para calcular.</p>@endif

                    <div class="field">
                        <label for="c-type">¿Cómo terminó la relación de trabajo?</label>
                        <div class="select"><select id="c-type" name="separation_type">
                            <option value="injustificado" @selected($type === 'injustificado')>Despido injustificado</option>
                            <option value="justificado" @selected($type === 'justificado')>Despido justificado</option>
                            <option value="renuncia" @selected($type === 'renuncia')>Renuncia voluntaria</option>
                        </select></div>
                        <p class="field__err">{{ $errorFor('separation_type') }}</p>
                    </div>

                    <div class="field-row">
                        <div class="field">
                            <label for="c-salary">Sueldo (bruto)</label>
                            <input id="c-salary" name="salary_amount" type="number" inputmode="decimal" min="1" step="0.01" required value="{{ old('salary_amount') }}" placeholder="Ej. 18000" @if($errorFor('salary_amount')) aria-invalid="true" @endif>
                            <p class="field__err">{{ $errorFor('salary_amount') }}</p>
                        </div>
                        <div class="field">
                            <label for="c-period">Periodicidad</label>
                            <div class="select"><select id="c-period" name="salary_period">
                                @foreach(['monthly' => 'Mensual', 'biweekly' => 'Quincenal', 'weekly' => 'Semanal', 'daily' => 'Diario'] as $value => $label)
                                    <option value="{{ $value }}" @selected(old('salary_period', 'monthly') === $value)>{{ $label }}</option>
                                @endforeach
                            </select></div>
                            <p class="field__err">{{ $errorFor('salary_period') }}</p>
                        </div>
                    </div>

                    <div class="field-row">
                        <div class="field">
                            <label for="c-start">Fecha de ingreso</label>
                            <input id="c-start" name="start_date" type="date" required value="{{ old('start_date') }}" max="{{ now()->toDateString() }}" @if($errorFor('start_date')) aria-invalid="true" @endif>
                            <p class="field__err">{{ $errorFor('start_date') }}</p>
                        </div>
                        <div class="field">
                            <label for="c-end">Fecha de separación</label>
                            <input id="c-end" name="end_date" type="date" required value="{{ old('end_date', now()->toDateString()) }}" @if($errorFor('end_date')) aria-invalid="true" @endif>
                            <p class="field__err">{{ $errorFor('end_date') }}</p>
                        </div>
                    </div>

                    <details class="calc__more">
                        <summary>Opciones avanzadas</summary>
                        <div class="field-row">
                            <div class="field">
                                <label for="c-zone">Zona salarial</label>
                                <div class="select"><select id="c-zone" name="zone">
                                    <option value="general" @selected(old('zone', 'general') === 'general')>Resto del país</option>
                                    <option value="frontera" @selected(old('zone') === 'frontera')>Zona libre de la frontera norte</option>
                                </select></div>
                                <p class="field__err">{{ $errorFor('zone') }}</p>
                            </div>
                            <div class="field">
                                <label for="c-aguinaldo">Días de aguinaldo al año</label>
                                <input id="c-aguinaldo" name="aguinaldo_days" type="number" min="15" max="90" value="{{ old('aguinaldo_days', 15) }}">
                                <p class="field__err">{{ $errorFor('aguinaldo_days') }}</p>
                            </div>
                        </div>
                        <div class="field">
                            <label for="c-vac">Días de vacaciones ya disfrutados en el último año de servicio</label>
                            <input id="c-vac" name="vacation_days_taken" type="number" min="0" max="60" value="{{ old('vacation_days_taken', 0) }}">
                            <p class="field__err">{{ $errorFor('vacation_days_taken') }}</p>
                        </div>
                    </details>

                    <x-button type="submit" label="Calcular estimación" variant="primary" class="btn--block" />
                    <p class="form__legal">Sus datos se usan únicamente para el cálculo y no se almacenan. Salario mínimo de referencia: {{ $money($minimumWage) }} diarios.</p>
                </form>
            </div>

            <div class="calc__result" id="resultado" tabindex="-1">
                @if($result)
                    <div class="result">
                        <p class="eyebrow">Estimación</p>
                        <p class="result__total"><span>Total estimado</span><strong>{{ $money($result['total']) }} <small>MXN</small></strong></p>
                        <p class="result__meta">Antigüedad: {{ $result['service_label'] }} · Salario diario: {{ $money($result['daily_salary']) }} · Salario diario integrado: {{ $money($result['integrated_daily_salary']) }}</p>

                        <div class="result__cards">
                            <div><span>Finiquito</span><strong>{{ $money($result['subtotal_finiquito']) }}</strong></div>
                            <div><span>Liquidación</span><strong>{{ $money($result['subtotal_liquidacion']) }}</strong></div>
                        </div>

                        <table class="result__table">
                            <caption class="sr-only">Desglose de conceptos</caption>
                            <thead><tr><th scope="col">Concepto</th><th scope="col">Importe</th></tr></thead>
                            <tbody>
                                @foreach($result['items'] as $item)
                                    <tr>
                                        <th scope="row"><span>{{ $item['label'] }}</span><small>{{ $item['detail'] }}</small></th>
                                        <td>{{ $money($item['amount']) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot><tr><th scope="row">Total</th><td>{{ $money($result['total']) }}</td></tr></tfoot>
                        </table>

                        <ul class="result__notes">
                            @foreach($result['notes'] as $note)<li>{{ $note }}</li>@endforeach
                        </ul>

                        <div class="result__cta">
                            <p>¿Le ofrecieron una cantidad distinta? Antes de firmar un finiquito o convenio, conviene que un abogado lo revise.</p>
                            <x-button label="Solicitar revisión de mi caso" :href="route('contact.form', ['tipo' => 'cita', 'area' => 'Derecho Laboral'])" variant="gold" />
                        </div>
                    </div>
                @else
                    <div class="calc__empty">
                        <x-symbol name="calculator" :size="56" />
                        <h2 class="h4">Su estimación aparecerá aquí</h2>
                        <p>Complete sus datos y calcule. Verá el desglose de cada concepto con su fundamento.</p>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <section class="section on-white" aria-labelledby="how-title">
        <div class="container container--narrow prose">
            <h2 id="how-title">¿Cómo se calcula?</h2>
            <p>La calculadora aplica reglas generales de la Ley Federal del Trabajo. Distingue entre el <strong>finiquito</strong>, que corresponde en cualquier terminación de la relación laboral (aguinaldo, vacaciones y prima vacacional proporcionales), y la <strong>liquidación</strong>, que se suma en un despido injustificado (indemnización constitucional de tres meses, 20 días por año de servicios y prima de antigüedad).</p>
            <h3>Importante</h3>
            <ul>
                <li>Es una <strong>estimación</strong>: no descuenta impuestos ni incluye salarios vencidos, horas extra, bonos, PTU o prestaciones superiores a la ley.</li>
                <li>El salario mínimo de referencia se actualiza cada año; el despacho lo mantiene al día en su configuración.</li>
                <li>Existen plazos legales breves para reclamar. Si considera que su despido fue injustificado, consulte cuanto antes.</li>
            </ul>
        </div>
    </section>

    <x-cta title="Antes de firmar, *conozca sus derechos.*" text="Revisamos su finiquito o convenio y le explicamos las alternativas disponibles." :primary-href="route('contact.form', ['tipo' => 'cita', 'area' => 'Derecho Laboral'])" />
</x-layouts.app>
