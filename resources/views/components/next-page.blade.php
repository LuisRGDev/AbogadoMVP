@props(['href', 'title', 'image', 'label' => 'Siguiente'])

<a class="next-page on-dark" href="{{ $href }}">
    <div class="next-page__media"><img src="{{ $image }}" alt="" loading="lazy" decoding="async" width="1920" height="1080"></div>
    <div class="next-page__shade"></div>
    <div class="container next-page__inner">
        <span class="next-page__label">{{ $label }}</span>
        <span class="next-page__title">{{ $title }}</span>
        <span class="next-page__arrow"><x-symbol name="arrow" :size="44" /></span>
    </div>
</a>
