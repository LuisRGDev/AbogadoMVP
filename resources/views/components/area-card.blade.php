@props(['area', 'index' => 0])

<a {{ $attributes->class(['area-card', 'reveal']) }} style="--d:{{ ($index % 3) * 0.09 }}s" href="{{ route('areas.show', $area) }}" aria-label="{{ $area->title }}: conocer área">
    <span class="area-card__top">
        <span class="area-card__num" aria-hidden="true">{{ $area->num }}</span>
        <x-symbol :name="$area->icon ?: 'scale'" class="area-card__icon" :size="34" />
    </span>
    <h3 class="area-card__title">{{ $area->title }}</h3>
    <p class="area-card__text">{{ $area->short }}</p>
    <span class="area-card__more">Conocer área <span class="area-card__arrow"><x-symbol name="arrow" :size="18" /></span></span>
</a>
