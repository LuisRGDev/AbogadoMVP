<a {{ $attributes->class(['logo']) }} href="{{ route('home') }}" aria-label="{{ site()->name() }} — inicio">
    <svg class="logo__mark" width="30" height="30" viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="1.3" aria-hidden="true"><rect x=".65" y=".65" width="30.7" height="30.7"/><path d="M10 24V8h7.5a4.5 4.5 0 0 1 0 9H10" stroke="#C5A46D"/></svg>
    <span class="logo__text">{{ site()->name() }}</span>
</a>
