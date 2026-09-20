@props([
    'label' => null,
    'href' => null,
    'variant' => 'primary',
    'arrow' => true,
    'type' => 'button',
])

@php
    $classes = 'btn btn--'.$variant;
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->class([$classes]) }}>
        <span class="btn__label">{{ $label ?? $slot }}</span>
        @if($arrow)<x-symbol name="arrow" class="btn__arrow" :size="18" />@endif
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->class([$classes]) }}>
        <span class="btn__label">{{ $label ?? $slot }}</span>
        @if($arrow)<x-symbol name="arrow" class="btn__arrow" :size="18" />@endif
    </button>
@endif
