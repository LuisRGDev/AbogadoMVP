@php
    $site = site();
    $hasPlaceholders = str_contains((string) $page->content, '[');
@endphp

<x-layouts.app :title="$title" :description="$page->seo_description ?: $title.' de '.$site->name()" :image="$site->image('pages.legal')" :schema="[breadcrumb_schema([['Inicio', url('/')], [$title, url()->current()]])]">
    <x-page-hero :eyebrow="$page->eyebrow ?: 'Legal'" :title="$title" :image="$site->image('pages.legal')" alt="" :crumbs="[[$title, null]]" class="page-hero--compact" />

    <section class="section on-light section--tight">
        <div class="container container--narrow">
            @if($hasPlaceholders)
                <aside class="note note--wine"><strong>Texto de ejemplo.</strong> Este contenido es un marcador de posición y debe ser redactado o revisado por el despacho y su asesor conforme a la normativa aplicable antes de su publicación.</aside>
            @endif
            <div class="prose">{{ clean_html($page->content) }}</div>
            <p class="legal__updated">Última actualización: {{ format_date($page->updated_at) }}</p>
        </div>
    </section>
</x-layouts.app>
