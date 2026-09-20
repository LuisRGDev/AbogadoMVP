@php $url = site()->whatsappUrl(); @endphp

@if($url)
    <div class="wa" data-wa>
        <span class="wa__tip" id="wa-tip" role="tooltip">¿Necesita orientación? Escríbanos.</span>
        <a class="wa__btn" href="{{ $url }}" target="_blank" rel="noopener noreferrer" aria-label="Escribir por WhatsApp" aria-describedby="wa-tip">
            <x-symbol name="whatsapp" :size="28" />
        </a>
    </div>
@endif
