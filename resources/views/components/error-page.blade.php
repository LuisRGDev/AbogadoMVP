@props(['code', 'title', 'text'])

<x-layouts.app :title="$code.' — '.$title" robots="noindex">
    <section class="page-hero on-dark error-hero">
        <div class="page-hero__media"><img src="{{ site()->image('pages.error') }}" alt="" width="1920" height="1080"></div>
        <div class="page-hero__shade"></div><div class="grain" aria-hidden="true"></div>
        <div class="container page-hero__inner">
            <p class="error-hero__code" aria-hidden="true">{{ $code }}</p>
            <h1 class="page-hero__title">{{ em($title) }}</h1>
            <p class="page-hero__text">{{ $text }}</p>
            <div class="page-hero__actions">
                <x-button label="Volver al inicio" :href="route('home')" variant="gold" />
                <x-button label="Contactarnos" :href="route('contact.form')" variant="ghost" />
            </div>
        </div>
    </section>
</x-layouts.app>
