@if(site()->phone() !== '')
    <a class="call-fab" href="{{ site()->phoneHref() }}" aria-label="Llamar al despacho: {{ site()->phone() }}" data-call>
        <span class="call-fab__tip" aria-hidden="true">Llamar ahora</span>
        <x-symbol name="phone" :size="26" />
    </a>
@endif
