@props(['items'])

@if($items->isNotEmpty())
    <div class="carousel reveal" data-carousel role="region" aria-roledescription="carrusel" aria-label="Testimonios">
        <div class="carousel__stage" aria-live="off">
            @foreach($items as $i => $testimonial)
                <figure class="testimonial {{ $i === 0 ? 'is-active' : '' }}" role="group" aria-roledescription="diapositiva" aria-label="{{ $i + 1 }} de {{ $items->count() }}" @if($i) aria-hidden="true" @endif>
                    <span class="testimonial__mark" aria-hidden="true">“</span>
                    <blockquote class="testimonial__quote">{{ $testimonial->quote }}</blockquote>
                    <figcaption class="testimonial__by"><strong>— {{ $testimonial->author }}</strong>@if($testimonial->area)<span>{{ $testimonial->area }}</span>@endif</figcaption>
                </figure>
            @endforeach
        </div>
        @if($items->count() > 1)
            <div class="carousel__ctrl">
                <div class="carousel__dots" role="tablist" aria-label="Seleccionar testimonio">
                    @foreach($items as $i => $testimonial)
                        <button type="button" role="tab" data-dot="{{ $i }}" aria-label="Testimonio {{ $i + 1 }}" aria-selected="{{ $i === 0 ? 'true' : 'false' }}"></button>
                    @endforeach
                </div>
                <div class="carousel__btns">
                    <button type="button" class="round" data-prev aria-label="Anterior"><x-symbol name="left" :size="18" /></button>
                    <button type="button" class="round" data-next aria-label="Siguiente"><x-symbol name="arrow" :size="18" /></button>
                    <button type="button" class="round round--txt" data-toggle aria-label="Pausar rotación automática">Pausar</button>
                </div>
            </div>
        @endif
    </div>
    @if($items->contains('is_demo', true))
        <p class="disclaimer reveal">Testimonios de demostración: no corresponden a clientes reales y deben sustituirse por testimonios autorizados.</p>
    @endif
@endif
