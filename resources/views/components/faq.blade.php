@props(['items', 'group' => 'faq'])

{{-- $items: colección/arreglo de ['question' => ..., 'answer' => ...] u objetos con esas propiedades --}}
<div {{ $attributes->class(['faq']) }}>
    @foreach($items as $i => $item)
        @php
            $question = is_array($item) ? $item['question'] : $item->question;
            $answer = is_array($item) ? $item['answer'] : $item->answer;
        @endphp
        <details class="faq__item reveal" name="{{ $group }}" style="--d:{{ min($i, 5) * 0.05 }}s">
            <summary class="faq__q">
                <span>{{ $question }}</span><i class="faq__icon" aria-hidden="true"></i>
            </summary>
            <div class="faq__panel"><p>{{ $answer }}</p></div>
        </details>
    @endforeach
</div>
