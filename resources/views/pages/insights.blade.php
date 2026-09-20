@php
    $site = site();
    $filtered = $activeCategory || $term;
@endphp

<x-layouts.app
    :title="$activeCategory ? 'Insights: '.$activeCategory : 'Insights jurídicos'"
    description="Artículos y análisis de carácter informativo sobre temas jurídicos relevantes para personas y empresas."
    :image="$site->image('pages.insights')"
    :robots="$filtered || $articles->currentPage() > 1 ? 'noindex,follow' : null"
    :canonical="route('articles.index')"
    :schema="[breadcrumb_schema([['Inicio', url('/')], ['Insights', route('articles.index')]])]">
    <x-page-hero
        eyebrow="Insights"
        title="Criterio jurídico *para decidir mejor.*"
        text="Artículos y análisis de carácter informativo sobre temas relevantes para personas y empresas."
        :image="$site->image('pages.insights')"
        alt="Marcos arquitectónicos convergentes hacia una luz cálida"
        :crumbs="[['Insights', null]]" />

    <section class="section on-white" aria-labelledby="ins-title">
        <div class="container">
            <h2 class="sr-only" id="ins-title">Artículos</h2>

            <div class="filters reveal">
                <ul class="chips chips--static" aria-label="Filtrar por categoría">
                    <li><a class="chip" href="{{ route('articles.index', array_filter(['q' => $term])) }}" @if(! $activeCategory) aria-current="true" @endif>Todos</a></li>
                    @foreach($categories as $category => $total)
                        <li><a class="chip" href="{{ route('articles.index', array_filter(['categoria' => $category, 'q' => $term])) }}" @if($activeCategory === $category) aria-current="true" @endif>{{ $category }} <small>{{ $total }}</small></a></li>
                    @endforeach
                </ul>
                <form class="searchbox" role="search" action="{{ route('articles.index') }}" method="get">
                    @if($activeCategory)<input type="hidden" name="categoria" value="{{ $activeCategory }}">@endif
                    <label class="sr-only" for="q-insights">Buscar artículos</label>
                    <input id="q-insights" type="search" name="q" value="{{ $term }}" placeholder="Buscar artículos…" maxlength="80" autocomplete="off">
                    <button type="submit" aria-label="Buscar"><x-symbol name="search" :size="18" /></button>
                </form>
            </div>

            @if($articles->isNotEmpty())
                <div class="blog-grid" id="blog-grid">
                    @foreach($articles as $i => $article)<x-blog-card :article="$article" :index="$i % 3" />@endforeach
                </div>
                {{ $articles->onEachSide(1)->links() }}
            @else
                <div class="empty">
                    <p>No encontramos artículos{{ $term ? ' para «'.$term.'»' : '' }}{{ $activeCategory ? ' en la categoría «'.$activeCategory.'»' : '' }}.</p>
                    <x-button label="Ver todos los artículos" :href="route('articles.index')" variant="outline" />
                </div>
            @endif
        </div>
    </section>

    <x-next-page :href="route('contact.form')" title="Contacto" :image="$site->image('pages.contacto')" />
</x-layouts.app>
