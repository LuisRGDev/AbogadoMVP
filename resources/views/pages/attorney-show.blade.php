@php
    $site = site();
    $url = route('team.show', $attorney);
    $schema = [breadcrumb_schema([['Inicio', url('/')], ['Equipo', route('team')], [$attorney->name, $url]])];
    if (! $attorney->is_demo && ! $site->isPlaceholder($attorney->name)) {
        $schema[] = array_filter([
            '@context' => 'https://schema.org',
            '@type' => 'Person',
            'name' => $attorney->name,
            'jobTitle' => $attorney->position,
            'description' => $attorney->bio_short,
            'image' => $attorney->photoUrl(),
            'worksFor' => ['@id' => url('/').'#organization'],
            'knowsAbout' => $attorney->areas,
            'url' => $url,
            'sameAs' => $attorney->linkedin ? [$attorney->linkedin] : null,
        ]);
    }
    $sections = [
        ['title' => 'Formación', 'icon' => 'document', 'items' => $attorney->education],
        ['title' => 'Experiencia profesional', 'icon' => 'briefcase', 'items' => $attorney->experience],
        ['title' => 'Membresías', 'icon' => 'people', 'items' => $attorney->memberships],
        ['title' => 'Idiomas', 'icon' => 'mark', 'items' => $attorney->languages],
    ];
@endphp

<x-layouts.app :title="$attorney->name.' — '.$attorney->position" :description="$attorney->bio_short" :image="$attorney->photoUrl()" type="profile" :schema="$schema">
    <x-page-hero :eyebrow="$attorney->position" :title="$attorney->name" :image="$site->image('pages.equipo')" :crumbs="[['Equipo', route('team')], [$attorney->name, null]]" class="page-hero--compact">
        <div class="page-hero__actions">
            <x-button label="Agendar consulta" :href="route('contact.form', ['tipo' => 'cita'])" variant="gold" />
            <x-button label="Descargar contacto (vCard)" :href="route('team.vcard', $attorney)" variant="ghost" :arrow="false" />
        </div>
    </x-page-hero>

    <section class="section on-light">
        <div class="container profile">
            <aside class="profile__side">
                <figure class="profile__photo">
                    <img src="{{ $attorney->photoUrl() }}" alt="Retrato de {{ $attorney->name }}" width="800" height="1000" decoding="async">
                    @if($attorney->is_demo)<span class="tag tag--demo">Perfil de demostración</span>@endif
                </figure>
                <dl class="profile__facts">
                    @if($attorney->credentials)<div><dt><x-symbol name="id-card" :size="18" /> Credenciales</dt><dd>{{ $attorney->credentials }}</dd></div>@endif
                    @if($site->email())<div><dt><x-symbol name="mail" :size="18" /> Correo</dt><dd><a class="u-link" href="mailto:{{ $site->email() }}">{{ $site->email() }}</a></dd></div>@endif
                    @if($site->phone())<div><dt><x-symbol name="phone" :size="18" /> Teléfono</dt><dd><a class="u-link" href="{{ $site->phoneHref() }}">{{ $site->phone() }}</a></dd></div>@endif
                    @if($attorney->linkedin && \Illuminate\Support\Str::startsWith($attorney->linkedin, 'https://'))
                        <div><dt><x-symbol name="linkedin" :size="18" /> LinkedIn</dt><dd><a class="u-link" href="{{ $attorney->linkedin }}" target="_blank" rel="noopener noreferrer">Ver perfil</a></dd></div>
                    @endif
                </dl>
            </aside>

            <div class="profile__main">
                <section aria-labelledby="bio-title">
                    <h2 class="h3" id="bio-title">Trayectoria</h2>
                    <div class="prose">
                        @foreach($attorney->bio ?? [] as $paragraph)<p>{{ $paragraph }}</p>@endforeach
                    </div>
                </section>

                <div class="profile__grid">
                    @foreach($sections as $section)
                        @if(! empty($section['items']))
                            <section>
                                <h3><x-symbol :name="$section['icon']" :size="20" /> {{ $section['title'] }}</h3>
                                <ul class="ticks">@foreach($section['items'] as $item)<li>{{ $item }}</li>@endforeach</ul>
                            </section>
                        @endif
                    @endforeach
                </div>

                @if($areas->isNotEmpty())
                    <section aria-labelledby="areas-title">
                        <h3 class="h4" id="areas-title">Áreas de práctica</h3>
                        <ul class="chips chips--static">
                            @foreach($areas as $area)<li><a class="chip" href="{{ route('areas.show', $area) }}">{{ $area->title }}</a></li>@endforeach
                        </ul>
                    </section>
                @endif
            </div>
        </div>
    </section>

    @if($colleagues->isNotEmpty())
        <section class="section on-white">
            <div class="container">
                <x-section-header eyebrow="Equipo" title="Conozca también *a nuestro equipo.*" />
                <div class="team-grid">
                    @foreach($colleagues as $i => $colleague)<x-attorney-card :attorney="$colleague" :index="$i" />@endforeach
                </div>
            </div>
        </section>
    @endif

    <x-cta />
</x-layouts.app>
