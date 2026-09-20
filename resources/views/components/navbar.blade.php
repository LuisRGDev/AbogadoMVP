@php
    $site = site();
    $current = fn (array $item): bool => request()->routeIs($item['match']);
@endphp

<header class="nav" id="nav">
    <div class="nav__inner container">
        <x-logo />
        <nav class="nav__links" aria-label="Principal">
            <ul>
                @foreach($site->navigation() as $item)
                    <li><a class="nav__link {{ $current($item) ? 'is-active' : '' }}" href="{{ route($item['route']) }}" @if($current($item)) aria-current="page" @endif>{{ $item['label'] }}</a></li>
                @endforeach
            </ul>
        </nav>
        <div class="nav__actions">
            <a class="nav__search" href="{{ route('search') }}" aria-label="Buscar en el sitio"><x-symbol name="search" :size="20" /></a>
            <x-button label="Agendar consulta" :href="route('contact.form', ['tipo' => 'cita'])" variant="gold" :arrow="false" class="btn--sm nav__cta" />
            <button class="burger" id="burger" type="button" aria-expanded="false" aria-controls="menu" aria-label="Abrir menú"><span></span><span></span></button>
        </div>
    </div>
    <div class="menu" id="menu" inert>
        <nav aria-label="Menú móvil">
            <ul>
                @foreach($site->navigation() as $i => $item)
                    <li style="--i:{{ $i }}"><a href="{{ route($item['route']) }}" @if($current($item)) aria-current="page" @endif>{{ $item['label'] }}</a></li>
                @endforeach
                <li style="--i:{{ count($site->navigation()) }}"><a href="{{ route('search') }}">Buscar</a></li>
            </ul>
        </nav>
        <div class="menu__foot">
            <x-button label="Agendar consulta" :href="route('contact.form', ['tipo' => 'cita'])" variant="gold" />
            <p>
                @if($site->phone())<a href="{{ $site->phoneHref() }}">{{ $site->phone() }}</a><br>@endif
                @if($site->email())<a href="mailto:{{ $site->email() }}">{{ $site->email() }}</a>@endif
            </p>
        </div>
    </div>
</header>
