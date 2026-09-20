@props(['name', 'size' => 24, 'label' => null])
{{ \App\Support\Icons::render($name, (string) $attributes->get('class', ''), (int) $size, $label) }}
