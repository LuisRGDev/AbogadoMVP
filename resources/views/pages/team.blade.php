@php $site = site(); @endphp

<x-layouts.app
    title="Equipo"
    description="Conozca al equipo de abogados del despacho: formación, experiencia y áreas de práctica."
    :image="$site->image('pages.equipo')"
    :schema="[breadcrumb_schema([['Inicio', url('/')], ['Equipo', route('team')]])]">
    <x-page-hero
        eyebrow="Equipo"
        title="Personas que *respaldan cada asunto.*"
        text="Profesionales con formación sólida y un compromiso claro con la atención personalizada."
        :image="$site->image('pages.equipo')"
        alt="Sala de juntas con vista a la ciudad al atardecer"
        :crumbs="[['Equipo', null]]" />

    <section class="section on-white" aria-labelledby="team-title">
        <div class="container">
            <h2 class="sr-only" id="team-title">Equipo</h2>
            @if($attorneys->isNotEmpty())
                @if($attorneys->contains('is_demo', true))
                    <p class="lead reveal team-note">Perfiles de ejemplo: sustituya nombres, fotografías y datos por información real y verificable desde el panel de administración.</p>
                @endif
                <div class="team-grid">
                    @foreach($attorneys as $i => $attorney)<x-attorney-card :attorney="$attorney" :index="$i" />@endforeach
                </div>
            @else
                <p class="empty">Muy pronto presentaremos a nuestro equipo.</p>
            @endif
        </div>
    </section>

    <x-band :image="$site->image('bands.equipo')" :quote="config('despacho.bands.equipo.quote')" :cite="config('despacho.bands.equipo.cite')" />

    <x-cta />
    <x-next-page :href="route('experience')" title="Experiencia" :image="$site->image('pages.experiencia')" />
</x-layouts.app>
