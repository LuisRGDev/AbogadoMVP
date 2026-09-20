@php
    $site = site();
    $process = config('despacho.process');
    $crumbs = [['Nosotros', null]];
@endphp

<x-layouts.app
    :title="$page?->seo_title ?: 'Nosotros'"
    :description="$page?->seo_description"
    :image="$site->image('pages.nosotros')"
    :schema="[breadcrumb_schema([['Inicio', url('/')], ['Nosotros', route('nosotros')]])]">
    <x-page-hero
        :eyebrow="$page?->eyebrow ?: 'Nosotros'"
        :title="$page?->title ?: 'Un despacho construido sobre *criterio y confianza.*'"
        :text="$page?->text ?: 'Conozca nuestra forma de trabajo, nuestros principios y lo que puede esperar al colaborar con nosotros.'"
        :image="$site->image('pages.nosotros')"
        alt="Biblioteca jurídica con estantes de libros bajo luz cálida"
        :crumbs="$crumbs" />

    <section class="section on-light" aria-labelledby="about-title">
        <div class="container about">
            <div class="about__media reveal-img">
                <span class="vline" aria-hidden="true"></span>
                <div class="about__frame"><img src="{{ $site->image('about') }}" alt="Composición arquitectónica en tonos marino y dorado" loading="lazy" decoding="async" width="1000" height="1250"></div>
            </div>
            <div class="about__text">
                <x-section-header :eyebrow="config('despacho.about.eyebrow')" :title="setting('about_title', config('despacho.about.title'))" :text="setting('about_text', config('despacho.about.text'))" id="about-title" />
                <div class="principles">
                    @foreach(config('despacho.principles') as $i => $principle)
                        <div class="principle reveal" style="--d:{{ 0.1 + $i * 0.08 }}s">
                            <x-symbol :name="$principle['icon']" class="principle__icon" :size="26" />
                            <h3>{{ $principle['title'] }}</h3>
                            <p>{{ $principle['text'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <x-band :image="$site->image('bands.nosotros')" :quote="config('despacho.bands.nosotros.quote')" :cite="config('despacho.bands.nosotros.cite')" />

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
        <section class="section on-white" aria-labelledby="team-title">
            <div class="container">
                <x-section-header eyebrow="Equipo" title="Personas que *respaldan cada asunto.*" id="team-title" />
                <div class="team-grid">
                    @foreach($attorneys as $i => $attorney)<x-attorney-card :attorney="$attorney" :index="$i" />@endforeach
                </div>
            </div>
        </section>
    @endif

    @if($faqs->isNotEmpty())
        <section class="section on-light" id="faq" aria-labelledby="faq-title">
            <div class="container faq-wrap">
                <div class="faq-wrap__head">
                    <x-section-header eyebrow="Preguntas frecuentes" title="Respuestas *claras.*" text="Si no encuentra la respuesta que busca, escríbanos y con gusto le orientaremos." id="faq-title" />
                </div>
                <x-faq :items="$faqs" group="faq-nosotros" />
            </div>
        </section>
    @endif

    <x-next-page :href="route('areas.index')" title="Áreas de práctica" :image="$site->image('pages.areas')" />
</x-layouts.app>
