@props([
    'title' => null,
    'text' => null,
    'primaryLabel' => 'Agendar consulta',
    'primaryHref' => null,
    'secondary' => true,
])

@php
    $primaryHref ??= route('contact.form', ['tipo' => 'cita']);
    $whatsapp = site()->whatsappUrl();
@endphp

<section class="cta on-dark" id="cta" aria-labelledby="cta-title">
    <div class="cta__media" data-parallax=".12"><img src="{{ site()->image('cta') }}" alt="" loading="lazy" decoding="async" width="1920" height="800"></div>
    <div class="cta__shade"></div>
    <div class="container cta__inner">
        <span class="gline" aria-hidden="true"></span>
        <h2 class="cta__title reveal" id="cta-title">{{ em($title ?? setting('cta_title', config('despacho.cta.title'))) }}</h2>
        <p class="cta__text reveal" style="--d:.1s">{{ $text ?? setting('cta_text', config('despacho.cta.text')) }}</p>
        <div class="cta__btns reveal" style="--d:.2s">
            <x-button :label="$primaryLabel" :href="$primaryHref" variant="gold" />
            @if($secondary && site()->phone())
                <x-button label="Llamar ahora" :href="site()->phoneHref()" variant="ghost" :arrow="false" />
            @endif
            @if($secondary && $whatsapp)
                <x-button label="Escribir por WhatsApp" :href="$whatsapp" variant="ghost" target="_blank" rel="noopener noreferrer" />
            @endif
        </div>
    </div>
</section>
