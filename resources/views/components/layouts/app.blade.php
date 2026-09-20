@props([
    'title' => null,
    'description' => null,
    'image' => null,
    'type' => 'website',
    'robots' => null,
    'canonical' => null,
    'schema' => [],
])

@php
    $site = site();
    $pageTitle = $title ? plain($title) : null;
    $fullTitle = $pageTitle ? $pageTitle.' | '.$site->name() : $site->name().' | '.setting('seo_title', 'Despacho jurídico en Ciudad de México');
    $metaDescription = \Illuminate\Support\Str::limit(plain($description ?: setting('seo_description', $site->description())), 165, '');
    $canonicalUrl = $canonical ?? url()->current();
    $ogImage = $image ?: asset(config('despacho.images.og'));

    $organization = array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'LegalService',
        '@id' => url('/').'#organization',
        'name' => $site->name(),
        'description' => $site->description(),
        'url' => url('/'),
        'image' => asset(config('despacho.images.og')),
        'telephone' => $site->isPlaceholder($site->phone()) ? null : $site->phone(),
        'email' => $site->isPlaceholder($site->email()) ? null : $site->email(),
        'address' => $site->isPlaceholder(implode(' ', $site->addressLines())) ? null : [
            '@type' => 'PostalAddress',
            'streetAddress' => setting('address_line1'),
            'addressLocality' => setting('city', 'Ciudad de México'),
            'addressRegion' => setting('state', 'CDMX'),
            'postalCode' => setting('postal_code'),
            'addressCountry' => 'MX',
        ],
        'areaServed' => ['@type' => 'Country', 'name' => 'México'],
        'sameAs' => array_column($site->social(), 'url') ?: null,
    ]);
@endphp
<!DOCTYPE html>
<html lang="es-MX">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>{{ $fullTitle }}</title>
    <meta name="description" content="{{ $metaDescription }}">
    <link rel="canonical" href="{{ $canonicalUrl }}">
    @if($robots)
        <meta name="robots" content="{{ $robots }}">
    @endif
    <meta name="theme-color" content="#0B1220">
    <meta name="color-scheme" content="light">

    <meta property="og:type" content="{{ $type }}">
    <meta property="og:locale" content="es_MX">
    <meta property="og:site_name" content="{{ $site->name() }}">
    <meta property="og:title" content="{{ $pageTitle ?? $site->name() }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle ?? $site->name() }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
    <meta name="twitter:image" content="{{ $ogImage }}">

    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="32x32">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    @if(request()->routeIs('home'))
        <link rel="preload" as="image" href="{{ $site->image('hero') }}" fetchpriority="high">
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <a class="skip-link" href="#main">Saltar al contenido</a>

    <x-navbar />

    <main id="main" tabindex="-1">
        {{ $slot }}
    </main>

    <x-footer :areas="$footerAreas ?? collect()" />
    <x-call-button />
    <x-whatsapp />

    @foreach(array_merge([$organization], $schema) as $node)
        <script type="application/ld+json">{!! json_encode($node, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP) !!}</script>
    @endforeach
</body>
</html>
