@php $site = site(); @endphp

<x-layouts.app title="Buscar" description="Busque áreas de práctica, artículos y abogados del despacho." :image="$site->image('pages.buscar')" robots="noindex,follow">
    <x-page-hero eyebrow="Buscar" title="¿Qué está *buscando?*" :image="$site->image('pages.buscar')" :crumbs="[['Buscar', null]]">
        <form class="searchbox searchbox--hero" role="search" action="{{ route('search') }}" method="get">
            <label class="sr-only" for="q-search">Buscar en el sitio</label>
            <input id="q-search" type="search" name="q" value="{{ $term }}" placeholder="Áreas, artículos, abogados…" maxlength="80" autocomplete="off" @if(mb_strlen($term) < 2) autofocus @endif>
            <button type="submit" aria-label="Buscar"><x-symbol name="search" :size="20" /></button>
        </form>
    </x-page-hero>

    <section class="section on-white" aria-live="polite">
        <div class="container">
            @if(mb_strlen($term) < 2)
                <p class="empty">Escriba al menos dos letras para buscar.</p>
            @elseif($total === 0)
                <div class="empty">
                    <p>No encontramos resultados para «{{ $term }}».</p>
                    <x-button label="Contactar al despacho" :href="route('contact.form')" variant="outline" />
                </div>
            @else
                <p class="lead">{{ $total }} {{ $total === 1 ? 'resultado' : 'resultados' }} para «{{ $term }}»</p>

                @if($results['areas']->isNotEmpty())
                    <h2 class="h3 search__h">Áreas de práctica</h2>
                    <div class="areas-grid areas-grid--light">@foreach($results['areas'] as $i => $area)<x-area-card :area="$area" :index="$i" />@endforeach</div>
                @endif
                @if($results['articles']->isNotEmpty())
                    <h2 class="h3 search__h">Insights</h2>
                    <div class="blog-grid blog-grid--2">@foreach($results['articles'] as $i => $article)<x-blog-card :article="$article" :index="$i % 3" />@endforeach</div>
                @endif
                @if($results['attorneys']->isNotEmpty())
                    <h2 class="h3 search__h">Equipo</h2>
                    <div class="team-grid">@foreach($results['attorneys'] as $i => $attorney)<x-attorney-card :attorney="$attorney" :index="$i" />@endforeach</div>
                @endif
            @endif
        </div>
    </section>
</x-layouts.app>
