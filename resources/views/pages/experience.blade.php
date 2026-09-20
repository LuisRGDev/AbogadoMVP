@php $site = site(); @endphp

<x-layouts.app
    title="Experiencia"
    description="Asuntos representativos y testimonios de quienes han trabajado con el despacho."
    :image="$site->image('pages.experiencia')"
    :schema="[breadcrumb_schema([['Inicio', url('/')], ['Experiencia', route('experience')]])]">
    <x-page-hero
        eyebrow="Experiencia"
        title="Trayectoria y *voz de nuestros clientes.*"
        text="Una muestra de cómo presentamos asuntos representativos y la opinión de quienes han trabajado con nosotros."
        :image="$site->image('pages.experiencia')"
        alt="Horizonte urbano al atardecer"
        :crumbs="[['Experiencia', null]]" />

    <section class="section on-dark2" aria-labelledby="exp-title">
        <div class="container">
            <x-section-header eyebrow="Casos" title="Asuntos atendidos con *rigor y método.*" id="exp-title" />
            @if($cases->isNotEmpty())
                <div class="cases">
                    @foreach($cases as $i => $case)
                        <article class="case reveal" style="--d:{{ $i * 0.1 }}s">
                            @if($case->is_demo)<p class="case__demo">Caso de demostración — reemplazar con información real</p>@endif
                            <p class="case__area">{{ $case->area }}</p>
                            <h3 class="case__title">{{ $case->matter }}</h3>
                            <dl>
                                <div><dt>Reto</dt><dd>{{ $case->challenge }}</dd></div>
                                <div><dt>Estrategia</dt><dd>{{ $case->strategy }}</dd></div>
                                <div><dt>Resultado</dt><dd>{{ $case->result }}</dd></div>
                            </dl>
                        </article>
                    @endforeach
                </div>
                <p class="disclaimer reveal">Los resultados dependen de las circunstancias particulares de cada asunto. La información se presenta de forma anonimizada y no constituye una promesa ni garantía de resultados.</p>
            @else
                <p class="empty">Muy pronto compartiremos asuntos representativos.</p>
            @endif
        </div>
    </section>

    @if($testimonials->isNotEmpty())
        <section class="section on-light" aria-labelledby="test-title">
            <div class="container">
                <x-section-header eyebrow="Testimonios" title="La experiencia *de quienes confían.*" id="test-title" />
                <x-testimonials :items="$testimonials" />
            </div>
        </section>
    @endif

    <x-next-page :href="route('articles.index')" title="Insights" :image="$site->image('pages.insights')" />
</x-layouts.app>
