@props(['areas'])

@php $site = site(); @endphp

<footer class="footer on-dark">
    <div class="container footer__grid">
        <div class="footer__brand">
            <x-logo />
            <p>{{ $site->description() }}</p>
            @if($site->social())
                <ul class="footer__social" aria-label="Redes sociales">
                    @foreach($site->social() as $network)
                        <li><a class="footer__soc" href="{{ $network['url'] }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $network['name'] }}"><x-symbol :name="$network['icon']" :size="18" /></a></li>
                    @endforeach
                </ul>
            @endif
        </div>
        <nav aria-label="Navegación del pie">
            <h2 class="footer__h">Navegación</h2>
            <ul>
                @foreach($site->navigation() as $item)
                    <li><a class="u-link" href="{{ route($item['route']) }}">{{ $item['label'] }}</a></li>
                @endforeach
            </ul>
        </nav>
        @if($areas->isNotEmpty())
            <div>
                <h2 class="footer__h">Áreas de práctica</h2>
                <ul>
                    @foreach($areas as $area)
                        <li><a class="u-link" href="{{ route('areas.show', $area) }}">{{ $area->title }}</a></li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div>
            <h2 class="footer__h">Contacto</h2>
            <ul>
                @if($site->phone())<li><a class="u-link" href="{{ $site->phoneHref() }}">{{ $site->phone() }}</a></li>@endif
                @if($site->email())<li><a class="u-link" href="mailto:{{ $site->email() }}">{{ $site->email() }}</a></li>@endif
                <li>{!! implode('<br>', array_map('e', $site->addressLines())) !!}</li>
                <li>{{ $site->hours() }}</li>
            </ul>
        </div>
    </div>
    <div class="container footer__bottom">
        <p class="footer__disc">La información de este sitio tiene carácter informativo y publicitario y no constituye asesoría legal. La relación abogado-cliente se establece únicamente mediante un acuerdo formal de servicios.</p>
        <div class="footer__legal">
            <p>© {{ now()->year }} {{ $site->name() }}. Todos los derechos reservados.</p>
            <ul>
                <li><a class="u-link" href="{{ route('legal.privacidad') }}">Aviso de Privacidad</a></li>
                <li><a class="u-link" href="{{ route('legal.terminos') }}">Términos y condiciones</a></li>
                <li><a class="u-link" href="{{ route('legal.disclaimer') }}">Disclaimer legal</a></li>
            </ul>
        </div>
    </div>
</footer>
