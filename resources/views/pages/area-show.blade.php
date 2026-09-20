@php
    $site = site();
    $steps = $area->processSteps();
    $faqItems = $area->faqItems();
    $url = route('areas.show', $area);
    $schema = [
        breadcrumb_schema([['Inicio', url('/')], ['Áreas de práctica', route('areas.index')], [$area->title, $url]]),
        [
            '@context' => 'https://schema.org',
            '@type' => 'Service',
            'name' => $area->title,
            'serviceType' => $area->title,
            'description' => $area->short,
            'provider' => ['@id' => url('/').'#organization'],
            'areaServed' => ['@type' => 'Country', 'name' => 'México'],
            'url' => $url,
        ],
    ];
    if ($faqItems) {
        $schema[] = [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => array_map(fn (array $faq): array => [
                '@type' => 'Question',
                'name' => $faq['question'],
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq['answer']],
            ], $faqItems),
        ];
    }
@endphp

<x-layouts.app :title="$area->seo_title ?: $area->title" :description="$area->seo_description ?: $area->short" :image="$area->coverUrl()" :schema="$schema">
    <x-page-hero :eyebrow="'Área '.$area->num" :title="$area->title" :text="$area->short" :image="$area->coverUrl()" :crumbs="[['Áreas de práctica', route('areas.index')], [$area->title, null]]">
        <div class="page-hero__actions">
            <x-button label="Agendar consulta" :href="route('contact.form', ['tipo' => 'cita', 'area' => $area->title])" variant="gold" />
        </div>
    </x-page-hero>

    <section class="section on-light">
        <div class="container area-layout">
            <div class="area-main">
                <section aria-labelledby="ov"><h2 class="h3" id="ov">Resumen</h2><p class="lead">{{ $area->overview }}</p></section>

                @if($area->matters)
                    <section aria-labelledby="mt"><h2 class="h3" id="mt">Asuntos que atendemos</h2>
                        <ul class="ticks">@foreach($area->matters as $matter)<li>{{ $matter }}</li>@endforeach</ul></section>
                @endif

                @if($area->needs)
                    <section aria-labelledby="nd"><h2 class="h3" id="nd">Necesidades típicas de nuestros clientes</h2>
                        <ul class="ticks">@foreach($area->needs as $need)<li>{{ $need }}</li>@endforeach</ul></section>
                @endif

                @if($steps)
                    <section aria-labelledby="pr"><h2 class="h3" id="pr">Proceso</h2>
                        <ol class="mini-steps">
                            @foreach($steps as $k => $step)
                                <li><span>{{ str_pad((string) ($k + 1), 2, '0', STR_PAD_LEFT) }}</span><div><h3>{{ $step['title'] }}</h3><p>{{ $step['description'] }}</p></div></li>
                            @endforeach
                        </ol></section>
                @endif

                @if($faqItems)
                    <section aria-labelledby="fq"><h2 class="h3" id="fq">Preguntas frecuentes</h2><x-faq :items="$faqItems" :group="'faq-'.$area->slug" /></section>
                @endif
            </div>

            <aside class="area-aside" aria-label="Contacto">
                <div class="aside-card">
                    <p class="eyebrow">¿Requiere orientación?</p>
                    <h2 class="h4">Conversemos sobre su situación.</h2>
                    <p>Solicite una primera conversación sobre {{ \Illuminate\Support\Str::lower($area->title) }}. Le responderemos en el horario de atención.</p>
                    <x-button label="Agendar consulta" :href="route('contact.form', ['tipo' => 'cita', 'area' => $area->title])" variant="primary" class="btn--block" />
                    @if($whatsapp = $site->whatsappUrl('Hola, me gustaría solicitar información sobre '.$area->title.'.'))
                        <x-button label="WhatsApp" :href="$whatsapp" variant="outline" class="btn--block" target="_blank" rel="noopener noreferrer" />
                    @endif
                    <p class="aside-card__small">
                        @if($site->phone())<a class="u-link" href="{{ $site->phoneHref() }}">{{ $site->phone() }}</a><br>@endif
                        @if($site->email())<a class="u-link" href="mailto:{{ $site->email() }}">{{ $site->email() }}</a>@endif
                    </p>
                </div>
            </aside>
        </div>
    </section>

    @if($attorneys->isNotEmpty())
        <section class="section on-white" aria-labelledby="who-title">
            <div class="container">
                <x-section-header eyebrow="Quién atiende" :title="'Especialistas en *'.\Illuminate\Support\Str::lower($area->title).'.*'" id="who-title" />
                <div class="team-grid">
                    @foreach($attorneys as $i => $attorney)<x-attorney-card :attorney="$attorney" :index="$i" />@endforeach
                </div>
            </div>
        </section>
    @endif

    @if($others->isNotEmpty())
        <section class="section on-dark">
            <div class="container">
                <x-section-header eyebrow="Otras áreas" title="Explore más *áreas de práctica.*" />
                <div class="areas-grid">
                    @foreach($others as $i => $other)<x-area-card :area="$other" :index="$i" />@endforeach
                </div>
            </div>
        </section>
    @endif
</x-layouts.app>
