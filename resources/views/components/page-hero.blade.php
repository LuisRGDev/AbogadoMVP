@props([
    'eyebrow' => null,
    'title' => '',
    'text' => null,
    'image' => null,
    'alt' => '',
    'crumbs' => [],
])

<section {{ $attributes->class(['page-hero', 'on-dark']) }}>
    @if($image)
        <div class="page-hero__media" data-parallax=".12"><img src="{{ $image }}" alt="{{ $alt }}" width="1920" height="1080" fetchpriority="high" decoding="async"></div>
    @endif
    <div class="page-hero__shade"></div><div class="grain" aria-hidden="true"></div>
    <div class="container page-hero__inner">
        @if(count($crumbs))
            <nav class="crumbs" aria-label="Migas de pan">
                <ol>
                    <li><a href="{{ route('home') }}">Inicio</a></li>
                    @foreach($crumbs as [$label, $url])
                        <li>@if($url)<a href="{{ $url }}">{{ $label }}</a>@else<span aria-current="page">{{ $label }}</span>@endif</li>
                    @endforeach
                </ol>
            </nav>
        @endif
        @isset($meta)<p class="article-meta">{{ $meta }}</p>@endisset
        @if($eyebrow)<p class="eyebrow eyebrow--gold page-hero__eyebrow">{{ $eyebrow }}</p>@endif
        <h1 class="page-hero__title">{{ em($title) }}</h1>
        @if($text)<p class="page-hero__text">{{ $text }}</p>@endif
        {{ $slot }}
    </div>
</section>
