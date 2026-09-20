@php $site = site(); @endphp

<x-layouts.app
    title="Áreas de práctica"
    description="Asesoría jurídica en derecho corporativo, litigio, laboral, inmobiliario, civil y mercantil, y propiedad intelectual en Ciudad de México."
    :image="$site->image('pages.areas')"
    :schema="[breadcrumb_schema([['Inicio', url('/')], ['Áreas de práctica', route('areas.index')]])]">
    <x-page-hero
        eyebrow="Áreas de práctica"
        title="Especialidades para *cada decisión.*"
        :text="'Áreas de práctica con un enfoque común: análisis riguroso, estrategia clara y comunicación constante.'"
        :image="$site->image('pages.areas')"
        alt="Fachada clásica de columnas de un edificio institucional"
        :crumbs="[['Áreas de práctica', null]]" />

    <section class="section on-dark" aria-labelledby="areas-title">
        <div class="container">
            <h2 class="sr-only" id="areas-title">Áreas de práctica</h2>
            @if($areas->isNotEmpty())
                <div class="areas-grid">
                    @foreach($areas as $i => $area)<x-area-card :area="$area" :index="$i" />@endforeach
                </div>
            @else
                <p class="empty">Muy pronto publicaremos nuestras áreas de práctica.</p>
            @endif
        </div>
    </section>

    <x-band :image="$site->image('bands.areas')" :quote="config('despacho.bands.areas.quote')" :cite="config('despacho.bands.areas.cite')" />

    <x-next-page :href="route('team')" title="Equipo" :image="$site->image('pages.equipo')" />
</x-layouts.app>
