@php
    $site = site();
    $type = old('type', $initialType->value);
    $selectedArea = old('area', $preselectedArea);
    $slots = config('despacho.appointment.slots');
    $modes = config('despacho.appointment.modes');
    $minDate = now()->addDay()->toDateString();
    $maxDate = now()->addDays((int) config('despacho.appointment.max_days_ahead'))->toDateString();
    $errorFor = fn (string $field) => $errors->first($field);
    $whatsapp = $site->whatsappUrl();
@endphp

<x-layouts.app
    title="Contacto"
    description="Solicite una primera conversación o agende una cita con el despacho. Le responderemos en el horario de atención."
    :image="$site->image('pages.contacto')"
    :schema="[breadcrumb_schema([['Inicio', url('/')], ['Contacto', route('contact.form')]])]">
    <x-page-hero
        eyebrow="Contacto"
        title="Iniciemos una *conversación.*"
        text="Cuéntenos de forma general su necesidad. Nos pondremos en contacto para coordinar una primera conversación."
        :image="$site->image('pages.contacto')"
        alt="Fachada de un rascacielos vista desde abajo"
        :crumbs="[['Contacto', null]]" />

    <section class="section on-white" aria-labelledby="contact-title">
        <div class="container contact">
            <div class="contact__info">
                <x-section-header eyebrow="Escríbanos" title="Hablemos de *lo que importa.*" id="contact-title" />
                <ul class="contact__list reveal" style="--d:.2s">
                    @if($site->phone())<li><x-symbol name="phone" :size="22" /><div><span>Teléfono</span><a class="u-link" href="{{ $site->phoneHref() }}">{{ $site->phone() }}</a></div></li>@endif
                    @if($site->email())<li><x-symbol name="mail" :size="22" /><div><span>Correo</span><a class="u-link" href="mailto:{{ $site->email() }}">{{ $site->email() }}</a></div></li>@endif
                    <li><x-symbol name="pin" :size="22" /><div><span>Oficina</span><address>{!! implode('<br>', array_map('e', $site->addressLines())) !!}</address></div></li>
                    <li><x-symbol name="clock" :size="22" /><div><span>Horario</span><span class="plain">{{ $site->hours() }}</span></div></li>
                </ul>
                @if($whatsapp)
                    <div class="contact__quick reveal" style="--d:.28s">
                        <x-button label="Escribir por WhatsApp" :href="$whatsapp" variant="outline" target="_blank" rel="noopener noreferrer" />
                    </div>
                @endif
                <ol class="expect reveal" style="--d:.34s">
                    <li><b>1</b><span>Recibimos su solicitud y la revisamos con confidencialidad.</span></li>
                    <li><b>2</b><span>Le respondemos dentro del horario de atención para coordinar una primera conversación.</span></li>
                    <li><b>3</b><span>Le explicamos alternativas y siguientes pasos, sin compromiso.</span></li>
                </ol>
            </div>

            <div class="contact__form reveal" style="--d:.15s" id="formulario">
                <form class="form" id="contact-form" method="post" action="{{ route('contact.store') }}" novalidate data-contact-form>
                    @csrf
                    <input type="hidden" name="_t" value="{{ $formToken }}">

                    @if(session('success'))
                        <p class="form__status is-ok" role="status" tabindex="-1" data-autofocus>{{ session('success') }}</p>
                    @endif
                    @if($errors->any())
                        <p class="form__status is-error" role="alert" tabindex="-1" data-autofocus>Revise los campos marcados para continuar.</p>
                    @endif

                    <fieldset class="seg" data-seg>
                        <legend class="sr-only">Tipo de solicitud</legend>
                        <label><input type="radio" name="type" value="contact" @checked($type === 'contact')><span><x-symbol name="mail" :size="18" /> Enviar consulta</span></label>
                        <label><input type="radio" name="type" value="appointment" @checked($type === 'appointment')><span><x-symbol name="calendar" :size="18" /> Agendar cita</span></label>
                    </fieldset>

                    <div class="field">
                        <label for="f-name">Nombre</label>
                        <input id="f-name" name="name" type="text" autocomplete="name" maxlength="120" required value="{{ old('name') }}" @if($errorFor('name')) aria-invalid="true" aria-describedby="e-name" @endif>
                        <p class="field__err" id="e-name">{{ $errorFor('name') }}</p>
                    </div>
                    <div class="field-row">
                        <div class="field">
                            <label for="f-email">Correo electrónico</label>
                            <input id="f-email" name="email" type="email" autocomplete="email" maxlength="190" required value="{{ old('email') }}" @if($errorFor('email')) aria-invalid="true" aria-describedby="e-email" @endif>
                            <p class="field__err" id="e-email">{{ $errorFor('email') }}</p>
                        </div>
                        <div class="field">
                            <label for="f-phone">Teléfono <small>(opcional)</small></label>
                            <input id="f-phone" name="phone" type="tel" autocomplete="tel" inputmode="tel" maxlength="40" value="{{ old('phone') }}" @if($errorFor('phone')) aria-invalid="true" aria-describedby="e-phone" @endif>
                            <p class="field__err" id="e-phone">{{ $errorFor('phone') }}</p>
                        </div>
                    </div>
                    <div class="field">
                        <label for="f-area">Área de interés <small>(opcional)</small></label>
                        <div class="select"><select id="f-area" name="area">
                            <option value="">Seleccione un área</option>
                            @foreach($areas as $area)<option value="{{ $area->title }}" @selected($selectedArea === $area->title)>{{ $area->title }}</option>@endforeach
                            <option value="{{ \App\Http\Requests\StoreContactRequest::OTHER_AREA }}" @selected($selectedArea === \App\Http\Requests\StoreContactRequest::OTHER_AREA)>Otra / No estoy seguro(a)</option>
                        </select></div>
                        <p class="field__err" id="e-area">{{ $errorFor('area') }}</p>
                    </div>

                    <div class="appointment" data-appointment>
                        <div class="field-row">
                            <div class="field">
                                <label for="f-date">Fecha preferida</label>
                                <input id="f-date" name="preferred_date" type="date" min="{{ $minDate }}" max="{{ $maxDate }}" value="{{ old('preferred_date') }}" @if($errorFor('preferred_date')) aria-invalid="true" aria-describedby="e-date" @endif>
                                <p class="field__err" id="e-date">{{ $errorFor('preferred_date') }}</p>
                            </div>
                            <div class="field">
                                <label for="f-slot">Horario preferido</label>
                                <div class="select"><select id="f-slot" name="preferred_slot">
                                    <option value="">Seleccione</option>
                                    @foreach($slots as $value => $label)<option value="{{ $value }}" @selected(old('preferred_slot') === $value)>{{ $label }}</option>@endforeach
                                </select></div>
                                <p class="field__err" id="e-slot">{{ $errorFor('preferred_slot') }}</p>
                            </div>
                        </div>
                        <div class="field">
                            <label for="f-mode">Modalidad</label>
                            <div class="select"><select id="f-mode" name="meeting_mode">
                                <option value="">Seleccione</option>
                                @foreach($modes as $value => $label)<option value="{{ $value }}" @selected(old('meeting_mode') === $value)>{{ $label }}</option>@endforeach
                            </select></div>
                            <p class="field__err" id="e-mode">{{ $errorFor('meeting_mode') }}</p>
                        </div>
                    </div>

                    <div class="field">
                        <label for="f-message">Mensaje <small>(descripción general, sin datos sensibles)</small></label>
                        <textarea id="f-message" name="message" rows="5" maxlength="3000" required @if($errorFor('message')) aria-invalid="true" aria-describedby="e-message" @endif>{{ old('message') }}</textarea>
                        <p class="field__err" id="e-message">{{ $errorFor('message') }}</p>
                    </div>

                    <div class="field field--hp" aria-hidden="true"><label>No llenar<input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>

                    <div class="field field--check">
                        <label class="check"><input type="checkbox" id="f-consent" name="consent" value="1" required @checked(old('consent'))>
                            <span class="check__box" aria-hidden="true"><x-symbol name="check" :size="14" /></span>
                            <span>He leído el <a class="u-link" href="{{ route('legal.privacidad') }}" target="_blank" rel="noopener">Aviso de Privacidad</a>.</span></label>
                        <p class="field__err" id="e-consent">{{ $errorFor('consent') }}</p>
                    </div>

                    <x-button type="submit" label="Enviar solicitud" variant="primary" class="btn--block" />
                    <p class="form__legal">Enviar este formulario no constituye por sí mismo una relación abogado-cliente. Por favor, no incluya información sensible ni detalles confidenciales de su caso.</p>
                    <p class="form__status" id="form-status" role="status" aria-live="polite"></p>
                </form>
            </div>
        </div>
    </section>

    <section class="map on-dark" aria-labelledby="map-title">
        @if($embed = $site->mapsEmbedUrl())
            <div class="map__canvas"><iframe src="{{ $embed }}" title="Mapa de la ubicación del despacho" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe></div>
        @else
            <div class="map__canvas" aria-hidden="true"><x-map /></div>
        @endif
        <div class="map__card">
            <p class="eyebrow eyebrow--gold">Oficina</p>
            <h2 id="map-title" class="map__title">{{ $site->get('city', 'Ciudad de México') }}, {{ $site->get('country', 'México') }}</h2>
            <address>{!! implode('<br>', array_map('e', $site->addressLines())) !!}</address>
            <x-button label="Cómo llegar" :href="$site->mapsUrl()" variant="gold" target="_blank" rel="noopener noreferrer" />
        </div>
    </section>

    @if($faqs->isNotEmpty())
        <section class="section on-light" id="faq" aria-labelledby="faq-title">
            <div class="container faq-wrap">
                <div class="faq-wrap__head">
                    <x-section-header eyebrow="Preguntas frecuentes" title="Respuestas *claras.*" text="Si no encuentra la respuesta que busca, escríbanos y con gusto le orientaremos." id="faq-title" />
                </div>
                <x-faq :items="$faqs" group="faq-contacto" />
            </div>
        </section>
    @endif
</x-layouts.app>
