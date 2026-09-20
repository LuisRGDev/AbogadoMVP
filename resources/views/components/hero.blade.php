@props(['image'])

@php
    $lines = preg_split('/\R/u', trim((string) setting('hero_title', config('despacho.hero.title'))));
    $trust = config('despacho.hero.trust');
@endphp

<section class="hero on-dark" id="inicio" aria-labelledby="hero-title">
    <div class="hero__media" data-parallax=".16"><img class="hero__img" src="{{ $image }}" alt="Estatua de la Justicia con los ojos vendados y una balanza" fetchpriority="high" width="1920" height="1080"></div>
    <div class="hero__shade"></div><div class="hero__flare"></div><div class="grain" aria-hidden="true"></div>
    <div class="container hero__inner">
        <p class="eyebrow eyebrow--gold hero__eyebrow">{{ setting('hero_eyebrow', config('despacho.hero.eyebrow')) }}</p>
        <h1 class="hero__title" id="hero-title">
            @foreach($lines as $i => $line)<span class="line"><span style="--i:{{ $i }}">{{ em($line) }}</span></span>@endforeach
        </h1>
        <p class="hero__text">{{ setting('hero_text', config('despacho.hero.text')) }}</p>
        <div class="hero__cta">
            <x-button :label="setting('hero_primary_cta', config('despacho.hero.primary_cta'))" :href="route('contact.form', ['tipo' => 'cita'])" variant="gold" />
            <x-button :label="setting('hero_secondary_cta', config('despacho.hero.secondary_cta'))" :href="route('areas.index')" variant="ghost" />
        </div>
        <ul class="hero__trust" aria-label="Principios">
            @foreach($trust as $item)<li>{{ $item }}</li>@endforeach
        </ul>
    </div>
    <a class="hero__scroll" href="#confianza" aria-label="Desplazarse hacia abajo"><span>Desplazar</span><i></i></a>
</section>
