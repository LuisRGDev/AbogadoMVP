@props([
    'eyebrow' => null,
    'title' => '',
    'text' => null,
    'id' => null,
    'tag' => 'h2',
    'align' => null,
])

<header {{ $attributes->class(['section-head', 'section-head--center' => $align === 'center']) }}>
    @if($eyebrow)<p class="eyebrow reveal">{{ $eyebrow }}</p>@endif
    <{{ $tag }} class="h2 reveal" @if($id) id="{{ $id }}" @endif style="--d:.08s">{{ em($title) }}</{{ $tag }}>
    @if($text)<p class="lead reveal" style="--d:.16s">{{ $text }}</p>@endif
</header>
