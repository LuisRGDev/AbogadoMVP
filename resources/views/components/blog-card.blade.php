@props(['article', 'index' => 0])

<article {{ $attributes->class(['blog-card', 'reveal']) }} style="--d:{{ $index * 0.1 }}s">
    <a class="blog-card__media" href="{{ route('articles.show', $article) }}" tabindex="-1" aria-hidden="true">
        <img src="{{ $article->coverUrl() }}" alt="" loading="lazy" decoding="async" width="1200" height="800">
    </a>
    <div class="blog-card__meta">
        <span>{{ $article->category }}</span>
        <span>{{ format_date($article->date, 'D MMM YYYY') }}</span>
        <span>{{ $article->readTimeLabel() }} de lectura</span>
    </div>
    <h3 class="blog-card__title"><a href="{{ route('articles.show', $article) }}">{{ $article->title }}</a></h3>
    <p class="blog-card__excerpt">{{ $article->excerpt }}</p>
    <a class="link-arrow" href="{{ route('articles.show', $article) }}">Leer artículo <x-symbol name="arrow" :size="16" /></a>
</article>
