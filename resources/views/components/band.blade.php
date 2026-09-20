@props(['image', 'quote', 'cite' => null])

<section class="band on-dark" aria-label="{{ $cite ?? 'Cita' }}">
    <div class="band__media" data-parallax=".1"><img src="{{ $image }}" alt="" loading="lazy" decoding="async" width="1920" height="1080"></div>
    <div class="band__shade"></div>
    <div class="container band__inner">
        <span class="gline gline--h" aria-hidden="true"></span>
        <blockquote class="band__quote reveal">{{ $quote }}</blockquote>
        @if($cite)<p class="band__cite reveal" style="--d:.12s">{{ $cite }}</p>@endif
    </div>
</section>
