@php
    $site = site();
    $about = config('despacho.about');
    $process = config('despacho.process');
    $websiteSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        '@id' => url('/').'#website',
        'name' => $site->name(),
        'url' => url('/'),
        'inLanguage' => 'es-MX',
        'potentialAction' => [
            '@type' => 'SearchAction',
            'target' => url('/buscar').'?q={search_term_string}',
            'query-input' => 'required name=search_term_string',
        ],
    ];
@endphp

<x-layouts.app :schema="[$websiteSchema]">
    <x-hero :image="$site->image('hero')" />

    <section class="stats on-dark2" id="confianza" aria-label="Datos del despacho">
        <ul class="container stats__list">
            @foreach($site->stats() as $i => $stat)
                <li class="reveal" style="--d:{{ $i * 0.08 }}s"><strong>{{ $stat['value'] }}</strong><span>{{ $stat['label'] }}</span></li>
            @endforeach
        </ul>
    </section>

    <section class="section on-light" id="nosotros" aria-labelledby="about-title">
        <div class="container about">
            <div class="about__media reveal-img">
                <span class="vline" aria-hidden="true"></span>
                <div class="about__frame"><img src="{{ $site->image('about') }}" alt="Composición arquitectónica en tonos marino y dorado" loading="lazy" decoding="async" width="1000" height="1250"></div>
                <p class="about__cap">{{ $site->get('city', 'Ciudad de México') }} · {{ $site->get('country', 'México') }}</p>
            </div>
            <div class="about__text">
                <x-section-header :eyebrow="$about['eyebrow']" :title="setting('about_title', $about['title'])" :text="setting('about_text', $about['text'])" id="about-title" />
                <div class="principles">
                    @foreach(array_slice(config('despacho.principles'), 0, 4) as $i => $principle)
                        <div class="principle reveal" style="--d:{{ 0.1 + $i * 0.08 }}s">
                            <x-symbol :name="$principle['icon']" class="principle__icon" :size="26" />
                            <h3>{{ $principle['title'] }}</h3>
                            <p>{{ $principle['text'] }}</p>
                        </div>
                    @endforeach
                </div>
                <div class="reveal" style="--d:.4s"><x-button label="Conocer al despacho" :href="route('nosotros')" variant="outline" /></div>
            </div>
        </div>
    </section>

    @if($areas->isNotEmpty())
        <section class="section on-dark has-bg" id="areas" aria-labelledby="areas-title">
            <div class="section-bg" data-parallax=".07"><img src="{{ $site->image('areas_bg') }}" alt="" loading="lazy" decoding="async" width="1920" height="1080"></div><div class="section-bg__shade"></div>
            <div class="container">
                <x-section-header eyebrow="Áreas de práctica" title="Asesoría especializada, *con criterio* propio." :text="'Áreas de práctica que cubren las necesidades jurídicas más frecuentes de personas y empresas.'" id="areas-title" />
                <div class="areas-grid">
                    @foreach($areas as $i => $area)<x-area-card :area="$area" :index="$i" />@endforeach
                </div>
                <div class="section-more reveal"><x-button label="Ver todas las áreas" :href="route('areas.index')" variant="ghost" /></div>
            </div>
        </section>
    @endif

    <x-band :image="$site->image('bands.home')" :quote="config('despacho.bands.home.quote')" :cite="config('despacho.bands.home.cite')" />

    <section class="section on-light" id="proceso" aria-labelledby="proc-title">
        <div class="container">
            <x-section-header :eyebrow="$process['eyebrow']" :title="$process['title']" id="proc-title" />
            <ol class="steps reveal-steps">
                @foreach($process['steps'] as $i => $step)
                    <li class="step" style="--i:{{ $i }}"><span class="step__num" aria-hidden="true">{{ $step['num'] }}</span>
                        <h3>{{ $step['title'] }}</h3><p>{{ $step['text'] }}</p></li>
                @endforeach
            </ol>
        </div>
    </section>

    @if($attorneys->isNotEmpty())
        <section class="section on-white" id="equipo" aria-labelledby="team-title">
            <div class="container">
                <x-section-header eyebrow="Equipo" title="El equipo detrás *de la estrategia.*" id="team-title" />
                <div class="team-grid">
                    @foreach($attorneys as $i => $attorney)<x-attorney-card :attorney="$attorney" :index="$i" />@endforeach
                </div>
                <div class="section-more reveal"><x-button label="Conocer al equipo" :href="route('team')" variant="outline" /></div>
            </div>
        </section>
    @endif

    <section class="section on-dark2 promo" aria-labelledby="promo-title">
        <div class="container promo__grid">
            <div>
                <p class="eyebrow eyebrow--gold reveal">Herramienta gratuita</p>
                <h2 class="h2 reveal" id="promo-title" style="--d:.08s">{{ em('¿Cuánto le corresponde *en un finiquito?*') }}</h2>
                <p class="lead reveal" style="--d:.16s">Calcule una estimación de su finiquito o liquidación laboral en menos de un minuto. Es orientativa, confidencial y no requiere registro.</p>
                <div class="reveal" style="--d:.24s"><x-button label="Abrir la calculadora" :href="route('tools.severance')" variant="gold" /></div>
            </div>
            <div class="promo__card reveal" style="--d:.2s" aria-hidden="true">
                <x-symbol name="calculator" :size="64" />
                <ul>
                    <li><span>Aguinaldo y vacaciones</span><i></i></li>
                    <li><span>Prima de antigüedad</span><i></i></li>
                    <li><span>Indemnización constitucional</span><i></i></li>
                </ul>
            </div>
        </div>
    </section>

    @if($testimonials->isNotEmpty())
        <section class="section on-light" id="testimonios" aria-labelledby="test-title">
            <div class="container">
                <x-section-header eyebrow="Testimonios" title="La experiencia *de quienes confían.*" id="test-title" />
                <x-testimonials :items="$testimonials" />
            </div>
        </section>
    @endif

    @if($articles->isNotEmpty())
        <section class="section on-white" id="insights" aria-labelledby="ins-title">
            <div class="container">
                <x-section-header eyebrow="Insights" title="Insights *jurídicos.*" text="Análisis y criterios prácticos para tomar decisiones informadas. Contenido de carácter informativo." id="ins-title" />
                <div class="blog-grid">
                    @foreach($articles as $i => $article)<x-blog-card :article="$article" :index="$i" />@endforeach
                </div>
                <div class="section-more reveal"><x-button label="Ver todos los insights" :href="route('articles.index')" variant="outline" /></div>
            </div>
        </section>
    @endif

    <x-cta />
</x-layouts.app>
