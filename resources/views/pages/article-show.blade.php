@php
    $site = site();
    $url = route('articles.show', $article);
    $schema = [
        breadcrumb_schema([['Inicio', url('/')], ['Insights', route('articles.index')], [$article->title, $url]]),
        [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $article->title,
            'description' => $article->seo_description ?: $article->excerpt,
            'image' => $article->coverUrl(),
            'datePublished' => $article->date->toDateString(),
            'dateModified' => $article->updated_at->toDateString(),
            'inLanguage' => 'es-MX',
            'mainEntityOfPage' => $url,
            'author' => ['@id' => url('/').'#organization'],
            'publisher' => ['@id' => url('/').'#organization'],
        ],
    ];
    $shareText = rawurlencode($article->title.' — '.$url);
@endphp

<x-layouts.app :title="$article->seo_title ?: $article->title" :description="$article->seo_description ?: $article->excerpt" :image="$article->coverUrl()" type="article" :schema="$schema">
    <div class="read-progress" data-progress aria-hidden="true"></div>

    <x-page-hero :title="$article->title" :text="$article->excerpt" :image="$site->image('pages.insights')" class="page-hero--article" :crumbs="[['Insights', route('articles.index')], [$article->category, route('articles.index', ['categoria' => $article->category])]]">
        <x-slot:meta>
            <span>{{ $article->category }}</span>
            <span>{{ format_date($article->date) }}</span>
            <span>{{ $article->readTimeLabel() }} de lectura</span>
        </x-slot:meta>
    </x-page-hero>

    <section class="section on-light section--tight">
        <div class="container article-layout">
            <article class="article-main">
                <figure class="article-cover"><img src="{{ $article->coverUrl() }}" alt="Ilustración editorial del artículo «{{ $article->title }}»" width="1200" height="800" decoding="async"></figure>

                <div class="prose">{!! $content['html'] !!}</div>

                <aside class="note">Este artículo tiene fines exclusivamente informativos y no constituye asesoría legal ni crea una relación abogado-cliente. Cada situación requiere un análisis individual.</aside>

                <div class="article-foot">
                    <div class="share" data-share>
                        <span>Compartir</span>
                        <a href="https://wa.me/?text={{ $shareText }}" target="_blank" rel="noopener noreferrer" aria-label="Compartir por WhatsApp"><x-symbol name="whatsapp" :size="18" /></a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ rawurlencode($url) }}" target="_blank" rel="noopener noreferrer" aria-label="Compartir en LinkedIn"><x-symbol name="linkedin" :size="18" /></a>
                        <a href="mailto:?subject={{ rawurlencode($article->title) }}&body={{ rawurlencode($url) }}" aria-label="Compartir por correo"><x-symbol name="mail" :size="18" /></a>
                        <button type="button" data-copy="{{ $url }}" aria-label="Copiar enlace"><x-symbol name="link" :size="18" /></button>
                        <span class="share__ok" data-copy-ok role="status" aria-live="polite"></span>
                    </div>
                    <a class="link-arrow" href="{{ route('articles.index') }}"><x-symbol name="left" :size="16" /> Volver a Insights</a>
                </div>
            </article>

            <aside class="article-aside" aria-label="Complementos del artículo">
                @if(count($content['toc']) > 1)
                    <nav class="toc" aria-label="Contenido del artículo">
                        <p class="eyebrow">En este artículo</p>
                        <ol>
                            @foreach($content['toc'] as $item)
                                <li class="toc--l{{ $item['level'] }}"><a href="#{{ $item['id'] }}">{{ $item['title'] }}</a></li>
                            @endforeach
                        </ol>
                    </nav>
                @endif
                <div class="aside-card">
                    <p class="eyebrow">¿Tiene una duda?</p>
                    <h2 class="h4">Conversemos sobre su caso.</h2>
                    <p>Cuéntenos de forma general su situación y le orientaremos sobre las alternativas disponibles.</p>
                    <x-button label="Agendar consulta" :href="route('contact.form', ['tipo' => 'cita'])" variant="primary" class="btn--block" />
                </div>
            </aside>
        </div>
    </section>

    @if($others->isNotEmpty())
        <section class="section on-white">
            <div class="container">
                <x-section-header eyebrow="Continuar leyendo" title="Más *insights.*" />
                <div class="blog-grid blog-grid--2">
                    @foreach($others as $i => $other)<x-blog-card :article="$other" :index="$i" />@endforeach
                </div>
            </div>
        </section>
    @endif
</x-layouts.app>
