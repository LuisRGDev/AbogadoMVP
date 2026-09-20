@props(['attorney', 'index' => 0])

<article {{ $attributes->class(['attorney', 'reveal']) }} style="--d:{{ $index * 0.1 }}s">
    <a class="attorney__photo" href="{{ route('team.show', $attorney) }}" aria-label="Ver perfil de {{ $attorney->name }}">
        <img src="{{ $attorney->photoUrl() }}" alt="Retrato de {{ $attorney->name }}, {{ $attorney->position }}" loading="lazy" decoding="async" width="800" height="1000">
        @if($attorney->is_demo)<span class="tag tag--demo">Demo</span>@endif
    </a>
    <div class="attorney__body">
        <p class="attorney__role">{{ $attorney->position }}</p>
        <h3 class="attorney__name"><a href="{{ route('team.show', $attorney) }}">{{ $attorney->name }}</a></h3>
        @if($attorney->areas)<p class="attorney__areas">{{ implode(' · ', $attorney->areas) }}</p>@endif
        @if($attorney->bio_short)<p class="attorney__bio">{{ $attorney->bio_short }}</p>@endif
        @if(! empty($attorney->education[0]))
            <p class="attorney__edu"><span>Formación</span> {{ $attorney->education[0] }}</p>
        @endif
        <a class="link-arrow" href="{{ route('team.show', $attorney) }}">Ver perfil <x-symbol name="arrow" :size="16" /></a>
    </div>
</article>
