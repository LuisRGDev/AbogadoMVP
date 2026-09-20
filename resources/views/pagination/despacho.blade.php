@if ($paginator->hasPages())
    <nav class="pager" role="navigation" aria-label="Paginación">
        <ul>
            <li>
                @if ($paginator->onFirstPage())
                    <span class="pager__btn is-disabled" aria-disabled="true"><x-symbol name="left" :size="16" /> Anterior</span>
                @else
                    <a class="pager__btn" href="{{ $paginator->previousPageUrl() }}" rel="prev"><x-symbol name="left" :size="16" /> Anterior</a>
                @endif
            </li>

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="pager__gap" aria-hidden="true">…</li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        <li>
                            @if ($page == $paginator->currentPage())
                                <span class="pager__num is-current" aria-current="page">{{ $page }}</span>
                            @else
                                <a class="pager__num" href="{{ $url }}" aria-label="Ir a la página {{ $page }}">{{ $page }}</a>
                            @endif
                        </li>
                    @endforeach
                @endif
            @endforeach

            <li>
                @if ($paginator->hasMorePages())
                    <a class="pager__btn" href="{{ $paginator->nextPageUrl() }}" rel="next">Siguiente <x-symbol name="arrow" :size="16" /></a>
                @else
                    <span class="pager__btn is-disabled" aria-disabled="true">Siguiente <x-symbol name="arrow" :size="16" /></span>
                @endif
            </li>
        </ul>
    </nav>
@endif
