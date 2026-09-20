/* =========================================================================
   APLICACIÓN DE VISTAS (SPA ligera, sin dependencias)
   Cada elemento del menú es una VISTA independiente con su propia cabecera,
   transición y metadatos SEO. Rutas por hash (listas para URLs limpias):
     #/                     Inicio
     #/nosotros             Nosotros
     #/areas                Áreas de práctica      #/areas/<slug>      Detalle
     #/equipo               Equipo
     #/experiencia          Experiencia y testimonios
     #/insights             Insights (con filtro)  #/insights/<slug>   Artículo
     #/contacto             Contacto, mapa y FAQ
     #/privacidad | #/terminos | #/disclaimer
   ========================================================================= */
(function () {
  const S = window.SITE, U = window.UI;
  const { icon, Button, SectionHeader, PageHero, Band, NextPage } = U;
  const $ = (s, r = document) => r.querySelector(s);
  const $$ = (s, r = document) => [...r.querySelectorAll(s)];
  const reduced = matchMedia('(prefers-reduced-motion: reduce)').matches;
  const state = { view: null, key: null, prefArea: '' };
  const strip = (h) => String(h).replace(/<[^>]+>/g, '');
  const AREA_IMGS = ['assets/bg-facade.svg', 'assets/bg-tower.svg', 'assets/bg-library.svg', 'assets/bg-aerial.svg', 'assets/bg-boardroom.svg', 'assets/bg-stair.svg'];
  const head = (h, o) => (o === false ? '' : h);

  /* ============================== SECCIONES ============================== */
  const sHero = () => {
    const h = S.hero;
    return `<section class="hero on-dark" id="inicio" aria-labelledby="hero-title">
      <div class="hero__media" data-parallax=".16"><img class="hero__img" src="${S.images.hero}" alt="${h.imageAlt}" fetchpriority="high" width="1920" height="1080"></div>
      <div class="hero__shade"></div><div class="hero__flare"></div><div class="grain" aria-hidden="true"></div>
      <div class="container hero__inner">
        <p class="eyebrow eyebrow--gold hero__eyebrow">${h.eyebrow}</p>
        <h1 class="hero__title" id="hero-title">${h.headlineLines.map((l, i) => `<span class="line"><span style="--i:${i}">${l}</span></span>`).join('')}</h1>
        <p class="hero__text">${h.text}</p>
        <div class="hero__cta">
          ${Button({ label: h.primaryCta, href: '#/contacto', variant: 'gold' })}
          ${Button({ label: h.secondaryCta, href: '#/areas', variant: 'ghost' })}
        </div>
        <ul class="hero__trust" aria-label="Principios">${h.trust.map((t) => `<li>${t}</li>`).join('')}</ul>
      </div>
      <a class="hero__scroll" href="#trust" aria-label="Desplazarse hacia abajo"><span>Desplazar</span><i></i></a>
    </section>`;
  };

  const sTrust = () => `<section class="trust on-dark2" id="trust" aria-label="Credibilidad">
    <ul class="container trust__list">${S.trust.map((t, i) => `
      <li class="reveal" style="--d:${i * .08}s"><span>${t.label}</span><strong>${t.text}</strong></li>`).join('')}</ul></section>`;

  const sAbout = (cta = { label: S.about.cta, href: '#/nosotros' }) => {
    const a = S.about;
    return `<section class="section on-light" id="nosotros" aria-labelledby="about-title">
      <div class="container about">
        <div class="about__media reveal-img">
          <span class="vline" aria-hidden="true"></span>
          <div class="about__frame"><img src="${S.images.about}" alt="${a.imageAlt}" loading="lazy" width="1000" height="1250"></div>
          <p class="about__cap">${a.caption}</p>
        </div>
        <div class="about__text">
          ${SectionHeader({ eyebrow: a.eyebrow, title: a.title, text: a.lead, id: 'about-title' })}
          <div class="principles">${a.principles.map((p, i) => `
            <div class="principle reveal" style="--d:${.1 + i * .08}s"><h3>${p.title}</h3><p>${p.text}</p></div>`).join('')}</div>
          <div class="reveal" style="--d:.4s">${Button({ label: cta.label, href: cta.href, variant: 'outline' })}</div>
        </div>
      </div></section>`;
  };

  const sAreas = ({ header = true, all = false } = {}) => `<section class="section on-dark has-bg" id="areas" aria-labelledby="areas-title">
    <div class="section-bg" data-parallax=".07"><img src="${S.images.areasBg}" alt="" loading="lazy" width="1920" height="1080"></div><div class="section-bg__shade"></div>
    <div class="container">
      ${header ? SectionHeader({ ...S.areasIntro, id: 'areas-title' }) : '<h2 class="sr-only" id="areas-title">Áreas de práctica</h2>'}
      <div class="areas-grid">${S.areas.map(U.PracticeAreaCard).join('')}</div>
      ${all ? `<div class="section-more reveal">${Button({ label: 'Ver todas las áreas', href: '#/areas', variant: 'ghost' })}</div>` : ''}
    </div></section>`;

  const sProcess = () => {
    const p = S.process;
    return `<section class="section on-light" id="proceso" aria-labelledby="proc-title">
      <div class="container">
        ${SectionHeader({ eyebrow: p.eyebrow, title: p.title, id: 'proc-title' })}
        <ol class="steps reveal-steps">${p.steps.map((s, i) => `
          <li class="step" style="--i:${i}"><span class="step__num" aria-hidden="true">${s.num}</span>
            <h3>${s.title}</h3><p>${s.text}</p></li>`).join('')}</ol>
      </div></section>`;
  };

  const sTeam = () => `<section class="section on-white" id="equipo" aria-labelledby="team-title">
    <div class="container">
      <h2 class="sr-only" id="team-title">Equipo</h2>
      <p class="lead reveal team-note">${S.team.text}</p>
      <div class="team-grid">${S.attorneys.map(U.AttorneyCard).join('')}</div>
    </div></section>`;

  const sExperience = () => {
    const e = S.experience;
    return `<section class="section on-dark2" id="experiencia" aria-labelledby="exp-title">
      <div class="container">
        ${SectionHeader({ eyebrow: e.eyebrow, title: e.title, text: e.text, id: 'exp-title' })}
        <div class="cases">${S.cases.map((c, i) => `
          <article class="case reveal" style="--d:${i * .1}s">
            ${c.demo ? `<p class="case__demo">${e.demoLabel}</p>` : ''}
            <p class="case__area">${c.area}</p>
            <h3 class="case__title">${c.matter}</h3>
            <dl>
              <div><dt>Reto</dt><dd>${c.challenge}</dd></div>
              <div><dt>Estrategia</dt><dd>${c.strategy}</dd></div>
              <div><dt>Resultado</dt><dd>${c.result}</dd></div>
            </dl>
          </article>`).join('')}</div>
        <p class="disclaimer reveal">${e.disclaimer}</p>
      </div></section>`;
  };

  const sTestimonials = () => {
    const t = S.testimonials;
    return `<section class="section on-light" id="testimonios" aria-labelledby="test-title">
      <div class="container">
        ${SectionHeader({ ...S.testimonialsIntro, id: 'test-title' })}
        <div class="carousel reveal" data-carousel role="region" aria-roledescription="carrusel" aria-label="Testimonios de demostración">
          <div class="carousel__stage" aria-live="off">${t.map((x, i) => U.TestimonialCard(x, i, t.length)).join('')}</div>
          <div class="carousel__ctrl">
            <div class="carousel__dots" role="tablist" aria-label="Seleccionar testimonio">
              ${t.map((_, i) => `<button type="button" role="tab" data-dot="${i}" aria-label="Testimonio ${i + 1}" aria-selected="${i === 0}"></button>`).join('')}
            </div>
            <div class="carousel__btns">
              <button type="button" class="round" data-prev aria-label="Anterior">${icon('left', '', 18)}</button>
              <button type="button" class="round" data-next aria-label="Siguiente">${icon('arrow', '', 18)}</button>
              <button type="button" class="round round--txt" data-toggle aria-label="Pausar rotación automática">Pausar</button>
            </div>
          </div>
        </div>
        <p class="disclaimer reveal">Testimonios de demostración: no corresponden a clientes reales y deben sustituirse por testimonios autorizados.</p>
      </div></section>`;
  };

  const sInsights = ({ header = true, filter = false, all = false } = {}) => {
    const cats = [...new Set(S.articles.map((a) => a.category))];
    return `<section class="section on-white" id="insights" aria-labelledby="ins-title">
    <div class="container">
      ${header ? SectionHeader({ ...S.insightsIntro, id: 'ins-title' }) : '<h2 class="sr-only" id="ins-title">Insights jurídicos</h2>'}
      ${filter ? `<div class="chips reveal" role="group" aria-label="Filtrar por categoría">
        <button type="button" class="chip" data-filter="all" aria-pressed="true">Todos</button>
        ${cats.map((c) => `<button type="button" class="chip" data-filter="${c}" aria-pressed="false">${c}</button>`).join('')}</div>` : ''}
      <div class="blog-grid" id="blog-grid">${S.articles.map(U.BlogCard).join('')}</div>
      ${all ? `<div class="section-more reveal">${Button({ label: 'Ver todos los insights', href: '#/insights', variant: 'outline' })}</div>` : ''}
    </div></section>`;
  };

  const sFaq = () => `<section class="section on-light" id="faq" aria-labelledby="faq-title">
    <div class="container faq-wrap">
      <div class="faq-wrap__head">${SectionHeader({ ...S.faqIntro, id: 'faq-title' })}</div>
      ${U.FAQAccordion(S.faqs, 'faq')}
    </div></section>`;

  const sCta = () => `<section class="cta on-dark" id="cta" aria-labelledby="cta-title">
    <div class="cta__media" data-parallax=".12"><img src="${S.images.cta}" alt="" loading="lazy" width="1920" height="800"></div>
    <div class="cta__shade"></div>
    <div class="container cta__inner">
      <span class="gline" aria-hidden="true"></span>
      <h2 class="cta__title reveal" id="cta-title">${S.cta.title}</h2>
      <p class="cta__text reveal" style="--d:.1s">${S.cta.text}</p>
      <div class="cta__btns reveal" style="--d:.2s">
        ${Button({ label: S.cta.primary, href: '#/contacto', variant: 'gold' })}
        ${Button({ label: S.cta.secondary, href: U.waUrl(), variant: 'ghost', attrs: 'target="_blank" rel="noopener noreferrer"' })}
      </div>
    </div></section>`;

  const mapUrl = () => `https://www.google.com/maps/dir/?api=1&destination=${encodeURIComponent(S.contact.address.mapsQuery)}`;
  const sContact = () => {
    const c = S.contact, a = c.address, cs = S.contactSection;
    const row = (ic, label, val) => `<li>${icon(ic, '', 22)}<div><span>${label}</span>${val}</div></li>`;
    return `<section class="section on-white" id="contacto" aria-labelledby="contact-title">
      <div class="container contact">
        <div class="contact__info">
          ${SectionHeader({ eyebrow: cs.eyebrow, title: cs.title, text: cs.text, id: 'contact-title' })}
          <ul class="contact__list reveal" style="--d:.2s">
            ${row('phone', 'Teléfono', `<a class="u-link" href="tel:+${U.digits(c.phone)}">${c.phone}</a>`)}
            ${row('mail', 'Email', `<a class="u-link" href="mailto:${c.email}">${c.email}</a>`)}
            ${row('pin', 'Oficina', `<address>${a.line1}, ${a.line2}<br>${a.city}, ${a.country}</address>`)}
            ${row('clock', 'Horario', `<span class="plain">${c.hours}</span>`)}
          </ul>
        </div>
        <div class="contact__form reveal" style="--d:.15s">${U.ContactForm(state.prefArea)}</div>
      </div></section>`;
  };

  /* Mapa estilizado — sustituir `.map__canvas` por un <iframe> de Google Maps /
     Mapbox / OpenStreetMap cuando exista la dirección real. */
  function mapSvg() {
    let s = '<svg viewBox="0 0 1600 560" preserveAspectRatio="xMidYMid slice" role="img" aria-label="Mapa estilizado de Ciudad de México (marcador de posición)"><rect width="1600" height="560" fill="#101828"/>';
    s += '<polygon points="120,330 420,300 460,470 180,520" fill="#C5A46D" fill-opacity=".05"/><polygon points="1050,60 1330,90 1290,240 1010,200" fill="#C5A46D" fill-opacity=".05"/>';
    for (let x = -100; x < 1700; x += 92) s += `<line x1="${x}" y1="0" x2="${x + 60}" y2="560" stroke="#C5A46D" stroke-opacity=".11"/>`;
    for (let y = 30; y < 560; y += 78) s += `<line x1="0" y1="${y}" x2="1600" y2="${y - 22}" stroke="#C5A46D" stroke-opacity=".11"/>`;
    s += '<line x1="-40" y1="520" x2="1640" y2="70" stroke="#C5A46D" stroke-opacity=".36" stroke-width="7"/><line x1="-40" y1="520" x2="1640" y2="70" stroke="#101828" stroke-opacity=".8" stroke-width="2"/>';
    s += '<line x1="700" y1="-10" x2="900" y2="580" stroke="#C5A46D" stroke-opacity=".26" stroke-width="4"/><line x1="0" y1="250" x2="1600" y2="210" stroke="#C5A46D" stroke-opacity=".26" stroke-width="4"/>';
    return s + '</svg>';
  }
  const sMap = () => `<section class="map on-dark" aria-labelledby="map-title">
    <div class="map__canvas" id="map-embed" data-provider="placeholder">${mapSvg()}
      <span class="map__pin" aria-hidden="true"><i></i><svg width="34" height="44" viewBox="0 0 34 44"><path d="M17 43S32 29 32 16a15 15 0 1 0-30 0c0 13 15 27 15 27z" fill="#C5A46D"/><circle cx="17" cy="16" r="5.500" fill="#0B1220"/></svg></span>
    </div>
    <div class="map__card">
      <p class="eyebrow eyebrow--gold">Oficina</p>
      <h2 id="map-title" class="map__title">${S.contact.address.city}, ${S.contact.address.country}</h2>
      <address>${S.contact.address.line1}<br>${S.contact.address.line2}<br>C.P. ${S.contact.address.postalCode}</address>
      ${Button({ label: 'Cómo llegar', href: mapUrl(), variant: 'gold', attrs: 'target="_blank" rel="noopener noreferrer"' })}
    </div></section>`;

  const sBand = (k) => Band(S.bands[k]);
  /* Enlace a la siguiente vista en el orden del menú */
  const ORDER = ['nosotros', 'areas', 'equipo', 'experiencia', 'insights', 'contacto'];
  const sNext = (key) => {
    const i = ORDER.indexOf(key), nk = ORDER[i + 1]; if (!nk) return '';
    const item = S.nav.find((n) => n.href === '#/' + nk);
    return NextPage({ href: item.href, label: 'Siguiente', title: item.label, image: S.pages[nk].image });
  };
  const hero = (key, extra = {}) => PageHero({ ...S.pages[key], crumb: [['Inicio', '#/'], [strip(S.pages[key].eyebrow)]], ...extra });

  /* ============================== VISTAS ============================== */
  const seoOf = (key) => ({ title: strip(S.pages[key].eyebrow) + ' | ' + S.firm.name, desc: S.pages[key].text });
  const VIEWS = {
    home: () => ({ title: S.seo.title, desc: S.seo.description,
      html: [sHero(), sTrust(), sAbout(), sAreas({ all: true }), sBand('home'), sProcess(), sTestimonials(), sInsights({ all: true }), sCta()].join('') }),
    nosotros: () => ({ ...seoOf('nosotros'),
      html: hero('nosotros') + sAbout({ label: 'Conocer al equipo', href: '#/equipo' }) + sBand('nosotros') + sProcess() + sFaq() + sNext('nosotros') }),
    areas: () => ({ ...seoOf('areas'),
      html: hero('areas') + sAreas({ header: false }) + sBand('areas') + sNext('areas') }),
    equipo: () => ({ ...seoOf('equipo'),
      html: hero('equipo') + sTeam() + sBand('equipo') + sCta() + sNext('equipo') }),
    experiencia: () => ({ ...seoOf('experiencia'),
      html: hero('experiencia') + sExperience() + sTestimonials() + sNext('experiencia') }),
    insights: () => ({ ...seoOf('insights'),
      html: hero('insights') + sInsights({ header: false, filter: true }) + sNext('insights') }),
    contacto: () => ({ ...seoOf('contacto'),
      html: hero('contacto') + sContact() + sFaq() + sMap() }),
  };

  function viewArea(slug) {
    const i = S.areas.findIndex((x) => x.slug === slug); if (i < 0) return null;
    const a = S.areas[i];
    const others = S.areas.filter((x) => x.slug !== slug).slice(0, 3);
    return { key: 'areas', title: a.seoTitle, desc: a.seoDescription, html: `
      <article>
        ${PageHero({ eyebrow: `Área ${a.num}`, title: a.title, text: a.short, image: a.image || AREA_IMGS[i % AREA_IMGS.length], alt: '', crumb: [['Inicio', '#/'], ['Áreas de práctica', '#/areas'], [a.title]],
          children: Button({ label: 'Agendar consulta', href: '#/contacto', variant: 'gold', attrs: `data-pref-area="${a.title}"` }) })}
        <section class="section on-light"><div class="container area-layout">
          <div class="area-main">
            <section aria-labelledby="ov"><h2 class="h3" id="ov">Resumen</h2><p class="lead">${a.overview}</p></section>
            <section aria-labelledby="mt"><h2 class="h3" id="mt">Asuntos que atendemos</h2>
              <ul class="ticks">${a.matters.map((m) => `<li>${m}</li>`).join('')}</ul></section>
            <section aria-labelledby="nd"><h2 class="h3" id="nd">Necesidades típicas de nuestros clientes</h2>
              <ul class="ticks">${a.needs.map((m) => `<li>${m}</li>`).join('')}</ul></section>
            <section aria-labelledby="pr"><h2 class="h3" id="pr">Proceso</h2>
              <ol class="mini-steps">${a.process.map(([t, d], k) => `<li><span>${String(k + 1).padStart(2, '0')}</span><div><h3>${t}</h3><p>${d}</p></div></li>`).join('')}</ol></section>
            <section aria-labelledby="fq"><h2 class="h3" id="fq">Preguntas frecuentes</h2>${U.FAQAccordion(a.faqs, 'af')}</section>
          </div>
          <aside class="area-aside" aria-label="Contacto"><div class="aside-card">
            <p class="eyebrow">¿Requiere orientación?</p>
            <h2 class="h4">Conversemos sobre su situación.</h2>
            <p>Solicite una primera conversación sobre ${a.title.toLowerCase()}. Le responderemos en el horario de atención.</p>
            ${Button({ label: 'Agendar consulta', href: '#/contacto', variant: 'primary', attrs: `data-pref-area="${a.title}"`, cls: 'btn--block' })}
            ${Button({ label: 'WhatsApp', href: U.waUrl(`Hola, me gustaría solicitar información sobre ${a.title}.`), variant: 'outline', cls: 'btn--block', attrs: 'target="_blank" rel="noopener noreferrer"' })}
            <p class="aside-card__small">${S.contact.phone}<br>${S.contact.email}</p>
          </div></aside>
        </div></section>
        <section class="section on-dark"><div class="container">
          ${SectionHeader({ eyebrow: 'Otras áreas', title: 'Explore más <em>áreas de práctica.</em>' })}
          <div class="areas-grid">${others.map(U.PracticeAreaCard).join('')}</div></div></section>
      </article>` };
  }

  function viewArticle(slug) {
    const a = S.articles.find((x) => x.slug === slug); if (!a) return null;
    const others = S.articles.filter((x) => x.slug !== slug);
    return { key: 'insights', title: a.title + ' | Insights', desc: a.seoDescription, article: a, html: `
      <article>
        ${PageHero({ image: S.pages.insights.image, cls: 'page-hero--article', crumb: [['Inicio', '#/'], ['Insights', '#/insights'], [a.category]], title: a.title, text: a.excerpt,
          eyebrow: '', children: '' }).replace('<h1', `<p class="article-meta"><span>${a.category}</span><span>${U.fmtDate(a.date)}</span><span>${a.readTime} de lectura</span></p><h1`)}
        <section class="section on-light section--tight"><div class="container container--narrow">
          <figure class="article-cover"><img src="${a.image}" alt="Ilustración editorial abstracta para el artículo: ${a.title}" width="1200" height="800"></figure>
          <div class="prose">${a.body.map(([h, p]) => `${h ? `<h2>${h}</h2>` : ''}<p>${p}</p>`).join('')}</div>
          <aside class="note">${S.articleDisclaimer}</aside>
          <div class="article-cta">${Button({ label: 'Agendar consulta', href: '#/contacto', variant: 'primary' })}
            <a class="link-arrow" href="#/insights">${icon('left', '', 16)} Volver a Insights</a></div>
        </div></section>
        <section class="section on-white"><div class="container">
          ${SectionHeader({ eyebrow: 'Continuar leyendo', title: 'Más <em>insights.</em>' })}
          <div class="blog-grid blog-grid--2">${others.map(U.BlogCard).join('')}</div></div></section>
      </article>` };
  }

  function viewLegal(key) {
    const p = S.legal.pages[key]; if (!p) return null;
    return { key: 'legal', title: p.title, desc: p.title + ' de ' + S.firm.name, html: `
      <article>
        ${PageHero({ image: S.pages.legal.image, title: p.title, crumb: [['Inicio', '#/'], [p.title]] })}
        <section class="section on-light section--tight"><div class="container container--narrow">
          <aside class="note note--wine"><strong>Texto de ejemplo.</strong> Este contenido es un marcador de posición y debe ser redactado o revisado por el despacho y su asesor conforme a la normativa aplicable antes de su publicación.</aside>
          <div class="prose">${p.sections.map(([h, t]) => `<h2>${h}</h2><p>${t}</p>`).join('')}</div>
        </div></section>
      </article>` };
  }

  /* ============================== SEO ============================== */
  function setMeta(title, desc) {
    document.title = title;
    const set = (sel, val) => { const el = $(sel); if (el) el.setAttribute('content', val); };
    set('meta[name="description"]', desc); set('meta[property="og:title"]', title); set('meta[property="og:description"]', desc);
  }
  function injectJsonLd(extra) {
    const c = S.contact, a = c.address;
    const org = { '@context': 'https://schema.org', '@type': 'LegalService', name: S.firm.name, description: S.firm.shortDescription, url: S.firm.url,
      areaServed: { '@type': 'City', name: a.city }, knowsAbout: S.areas.map((x) => x.title) };
    if (S.seo.includeLocalBusinessData) {          // solo con datos reales
      org.telephone = c.phone; org.email = c.email;
      org.address = { '@type': 'PostalAddress', streetAddress: a.line1, addressLocality: a.city, addressRegion: a.state, postalCode: a.postalCode, addressCountry: 'MX' };
    }
    let el = $('#ld-json'); if (!el) { el = document.createElement('script'); el.type = 'application/ld+json'; el.id = 'ld-json'; document.head.appendChild(el); }
    el.textContent = JSON.stringify(extra ? [org, extra] : org);
  }

  /* ============================== ENRUTADOR ============================== */
  const view = $('#view');
  const LEGACY = ['inicio', 'nosotros', 'areas', 'equipo', 'experiencia', 'insights', 'contacto'];
  const norm = (h) => (!h || h === '#' || h === '#/' || h === '#inicio' ? '#/' : h);

  function resolve() {
    let h = decodeURIComponent(location.hash || '');
    if (h.length > 1 && !h.startsWith('#/') && LEGACY.includes(h.slice(1))) h = h === '#inicio' ? '#/' : '#/' + h.slice(1);   // compatibilidad con anclas antiguas
    if (norm(h) === '#/') return { name: 'home', v: VIEWS.home() };
    if (h.startsWith('#/')) {
      const [, kind, slug] = h.split('/');
      if (VIEWS[kind] && kind !== 'home' && !slug) return { name: kind, v: VIEWS[kind]() };
      if (kind === 'areas' && slug) { const v = viewArea(slug); if (v) return { name: 'area', v }; }
      if (kind === 'insights' && slug) { const v = viewArticle(slug); if (v) return { name: 'article', v }; }
      if (['privacidad', 'terminos', 'disclaimer'].includes(kind)) { const v = viewLegal(kind); if (v) return { name: 'legal', v }; }
      return { name: 'home', v: VIEWS.home(), fallback: true };
    }
    return { name: state.view || 'home', anchor: h.slice(1) };                     // ancla dentro de la vista actual
  }
  const navKey = (name, v) => (name === 'home' ? '#/' : name === 'area' ? '#/areas' : name === 'article' ? '#/insights' : name === 'legal' ? '' : '#/' + name);

  function mount(name, v) {
    state.view = name;
    view.innerHTML = v.html;
    document.body.dataset.view = name;
    setMeta(v.title, v.desc);
    injectJsonLd(v.article ? { '@context': 'https://schema.org', '@type': 'BlogPosting', headline: v.article.title, datePublished: v.article.date, description: v.article.seoDescription, publisher: { '@type': 'Organization', name: S.firm.name } } : null);
    setActive(navKey(name));
    initPage();
  }
  let leaving = false;
  function route(first) {
    closeMenu();
    const r = resolve();
    if (r.anchor && state.view) {                                                   // ancla en la misma vista
      const el = document.getElementById(r.anchor); if (el) el.scrollIntoView(); return;
    }
    const same = !first && r.name === state.view && r.name === 'home' && !r.fallback;
    if (same && !leaving) { scrollTo({ top: 0, behavior: reduced ? 'auto' : 'smooth' }); return; }
    const go = () => {
      mount(r.name, r.v);
      window.scrollTo({ top: 0, behavior: 'instant' });
      view.classList.remove('is-leaving'); leaving = false;
    };
    if (first === true || reduced) return go();
    if (leaving) return;
    leaving = true;
    const bar = $('#route-bar'); bar.classList.remove('run'); void bar.offsetWidth; bar.classList.add('run');
    view.classList.add('is-leaving'); setTimeout(go, 300);
  }

  /* ============================== COMPORTAMIENTOS ============================== */
  let io;
  function initPage() {
    io && io.disconnect();
    const items = $$('.reveal, .reveal-img, .reveal-steps');
    if (reduced || !('IntersectionObserver' in window)) items.forEach((e) => e.classList.add('in'));
    else {
      io = new IntersectionObserver((es) => es.forEach((e) => { if (e.isIntersecting) { e.target.classList.add('in'); io.unobserve(e.target); } }), { threshold: 0.14, rootMargin: '0px 0px -6% 0px' });
      items.forEach((e) => io.observe(e));
    }
    initCarousel(); initForm(); onScroll();
    const m = $('#main'); if (m && state.view) m.focus({ preventScroll: true });
  }
  function setActive(href) {
    $$('[data-nav]').forEach((a) => { const on = a.getAttribute('href') === href; a.classList.toggle('is-active', on); on ? a.setAttribute('aria-current', 'page') : a.removeAttribute('aria-current'); });
  }

  let ticking = false;
  function onScroll() {
    if (ticking) return; ticking = true;
    requestAnimationFrame(() => {
      ticking = false;
      const nav = $('#nav'); if (!nav) return;
      nav.classList.toggle('is-solid', scrollY > 40 || $('#burger').getAttribute('aria-expanded') === 'true');
      if (reduced) return;
      const vh = innerHeight;
      $$('[data-parallax]').forEach((el) => {
        const r = el.parentElement.getBoundingClientRect();
        if (r.bottom < -100 || r.top > vh + 100) return;
        const y = -((r.top + r.height / 2) - vh / 2) * parseFloat(el.dataset.parallax);
        el.style.transform = `translate3d(0,${y.toFixed(1)}px,0)`;
      });
    });
  }

  function closeMenu() {
    const b = $('#burger'); if (!b || b.getAttribute('aria-expanded') !== 'true') return;
    b.setAttribute('aria-expanded', 'false'); b.setAttribute('aria-label', 'Abrir menú');
    $('#menu').classList.remove('is-open'); $('#menu').setAttribute('aria-hidden', 'true');
    document.documentElement.classList.remove('is-locked'); onScroll();
  }
  function toggleMenu() {
    const b = $('#burger'), open = b.getAttribute('aria-expanded') !== 'true';
    if (!open) return closeMenu();
    b.setAttribute('aria-expanded', 'true'); b.setAttribute('aria-label', 'Cerrar menú');
    $('#menu').classList.add('is-open'); $('#menu').setAttribute('aria-hidden', 'false');
    document.documentElement.classList.add('is-locked'); $('#nav').classList.add('is-solid');
  }

  /* ---- Carrusel de testimonios ---- */
  let carTimer;
  function initCarousel() {
    clearInterval(carTimer);
    const c = $('[data-carousel]'); if (!c) return;
    const slides = $$('.testimonial', c), dots = $$('[data-dot]', c), tog = $('[data-toggle]', c);
    let i = 0, playing = !reduced, hover = false;
    const show = (n) => {
      i = (n + slides.length) % slides.length;
      slides.forEach((s, k) => { s.classList.toggle('is-active', k === i); s.setAttribute('aria-hidden', k === i ? 'false' : 'true'); });
      dots.forEach((d, k) => d.setAttribute('aria-selected', k === i));
    };
    const play = () => { clearInterval(carTimer); if (playing) carTimer = setInterval(() => !hover && show(i + 1), 7000); };
    c.addEventListener('click', (e) => {
      if (e.target.closest('[data-next]')) show(i + 1);
      else if (e.target.closest('[data-prev]')) show(i - 1);
      else if (e.target.closest('[data-dot]')) show(+e.target.closest('[data-dot]').dataset.dot);
      else if (e.target.closest('[data-toggle]')) { playing = !playing; tog.textContent = playing ? 'Pausar' : 'Reanudar'; tog.setAttribute('aria-label', playing ? 'Pausar rotación automática' : 'Reanudar rotación automática'); c.querySelector('.carousel__stage').setAttribute('aria-live', playing ? 'off' : 'polite'); play(); }
    });
    c.addEventListener('mouseenter', () => (hover = true)); c.addEventListener('mouseleave', () => (hover = false));
    c.addEventListener('keydown', (e) => { if (e.key === 'ArrowRight') show(i + 1); if (e.key === 'ArrowLeft') show(i - 1); });
    if (reduced) tog.textContent = 'Reanudar';
    show(0); play();
  }

  /* ---- Formulario ---- */
  function initForm() {
    const f = $('#contact-form'); if (!f) return;
    const err = (id, msg, input) => { $('#e-' + id).textContent = msg; if (input) input.setAttribute('aria-invalid', msg ? 'true' : 'false'); return !msg; };
    const validate = () => {
      const v = (n) => f.elements[n];
      const checks = [
        ['nombre', v('nombre').value.trim().length < 2 ? 'Ingrese su nombre.' : '', v('nombre')],
        ['email', !/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(v('email').value.trim()) ? 'Ingrese un correo electrónico válido.' : '', v('email')],
        ['tel', v('telefono').value && U.digits(v('telefono').value).length < 8 ? 'Ingrese un teléfono válido o déjelo en blanco.' : '', v('telefono')],
        ['area', !v('area').value ? 'Seleccione un área de interés.' : '', v('area')],
        ['msg', v('mensaje').value.trim().length < 10 ? 'Describa brevemente su consulta (mínimo 10 caracteres).' : '', v('mensaje')],
        ['priv', !v('privacidad').checked ? 'Debe confirmar que ha leído el Aviso de Privacidad.' : '', v('privacidad')]
      ];
      let first = null;
      checks.forEach(([id, m, el]) => { if (!err(id, m, el) && !first) first = el; });
      return first;
    };
    f.addEventListener('input', (e) => { if (e.target.getAttribute('aria-invalid') === 'true') validate(); });
    f.addEventListener('submit', async (e) => {
      e.preventDefault();
      const st = $('#form-status'); st.className = 'form__status'; st.textContent = '';
      const bad = validate(); if (bad) { bad.focus(); return; }
      const data = Object.fromEntries(new FormData(f));
      if (data.website) { done(); return; }                       // honeypot
      const btn = $('button[type=submit]', f); btn.disabled = true;
      try {
        if (S.form.mode === 'endpoint' && S.form.endpoint) {
          const r = await fetch(S.form.endpoint, { method: 'POST', headers: { 'Content-Type': 'application/json', Accept: 'application/json' }, body: JSON.stringify(data) });
          if (!r.ok) throw new Error('http');
        } else if (S.form.mode === 'mailto') {
          const body = `Nombre: ${data.nombre}%0D%0AEmail: ${data.email}%0D%0ATeléfono: ${data.telefono || '-'}%0D%0AÁrea: ${data.area}%0D%0A%0D%0A${encodeURIComponent(data.mensaje)}`;
          location.href = `mailto:${S.contact.email}?subject=${encodeURIComponent('Solicitud de contacto — ' + data.area)}&body=${body}`;
        }
        done();
      } catch (_) {
        st.className = 'form__status is-error';
        st.textContent = 'No fue posible enviar su solicitud. Por favor inténtelo de nuevo o contáctenos por teléfono o WhatsApp.';
      } finally { btn.disabled = false; }
      function done() { f.reset(); state.prefArea = ''; st.className = 'form__status is-ok'; st.textContent = S.contactSection.success; }
    });
  }

  /* ---- Perfil de abogado (modal) ---- */
  function openAttorney(slug) {
    const p = S.attorneys.find((x) => x.slug === slug); if (!p) return;
    const list = (a) => `<ul>${a.map((x) => `<li>${x}</li>`).join('')}</ul>`;
    U.Modal.open(`<div class="profile">
      <div class="profile__photo"><img src="${p.image}" alt="Retrato de ${p.name} (imagen de marcador de posición)" width="800" height="1000"></div>
      <div class="profile__body">
        <p class="eyebrow">${p.position}</p>
        <h2 class="h3" id="m-title">${p.name}</h2>
        ${p.demo ? '<p class="tag tag--demo tag--inline">Perfil de demostración — reemplazar con información real</p>' : ''}
        ${p.bio.map((b) => `<p>${b}</p>`).join('')}
        <div class="profile__grid">
          <div><h3>Formación</h3>${list(p.education)}</div>
          <div><h3>Experiencia profesional</h3>${list(p.experience)}</div>
          <div><h3>Áreas de práctica</h3>${list(p.areas)}</div>
          <div><h3>Membresías</h3>${list(p.memberships)}</div>
          <div><h3>Idiomas</h3>${list(p.languages)}</div>
          <div><h3>Credenciales</h3><p>${p.credentials}</p></div>
        </div>
        <div class="profile__cta">${Button({ label: 'Agendar consulta', href: '#/contacto', variant: 'primary', attrs: 'data-close' })}
          ${p.linkedin ? `<a class="u-link" href="${p.linkedin}" target="_blank" rel="noopener noreferrer">LinkedIn</a>` : ''}</div>
      </div></div>`, 'm-title');
  }

  /* ---- Filtro de Insights ---- */
  function filterInsights(btn) {
    const cat = btn.dataset.filter;
    $$('.chip').forEach((c) => c.setAttribute('aria-pressed', String(c === btn)));
    $$('#blog-grid .blog-card').forEach((c) => { c.hidden = !(cat === 'all' || c.dataset.cat === cat); });
  }

  /* ---- Delegación global de eventos ---- */
  document.addEventListener('click', (e) => {
    const t = e.target;
    const at = t.closest('[data-attorney]'); if (at) return openAttorney(at.dataset.attorney);
    const ch = t.closest('[data-filter]'); if (ch) return filterInsights(ch);
    const qb = t.closest('.faq__q button');
    if (qb) {
      const open = qb.getAttribute('aria-expanded') === 'true';
      $$('.faq__q button', qb.closest('[data-faq]')).forEach((b) => { if (b !== qb) { b.setAttribute('aria-expanded', 'false'); b.closest('.faq__item').classList.remove('is-open'); } });
      qb.setAttribute('aria-expanded', String(!open)); qb.closest('.faq__item').classList.toggle('is-open', !open);
      return;
    }
    if (t.closest('#burger')) return toggleMenu();
    if (t.closest('#menu a')) closeMenu();
    const a = t.closest('a[href^="#"]');
    if (a) {
      const pa = t.closest('[data-pref-area]');
      state.prefArea = pa ? pa.dataset.prefArea : '';
      const sel = $('#f-area'); if (sel && pa) sel.value = state.prefArea;
      const href = a.getAttribute('href');
      if (href.startsWith('#/') && norm(href) === norm(location.hash) && !a.hasAttribute('data-close')) {   // misma vista → volver arriba
        e.preventDefault(); scrollTo({ top: 0, behavior: reduced ? 'auto' : 'smooth' });
      }
    }
  });
  document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeMenu(); });
  addEventListener('scroll', onScroll, { passive: true });
  addEventListener('resize', () => { onScroll(); if (innerWidth > 1259) closeMenu(); });
  addEventListener('hashchange', () => route());

  /* ---- Tarjetas: luz que sigue al cursor + cursor personalizado ---- */
  if (matchMedia('(hover:hover) and (pointer:fine)').matches && !reduced) {
    document.addEventListener('pointermove', (e) => {
      const c = e.target.closest && e.target.closest('.area-card');
      if (c) { const r = c.getBoundingClientRect(); c.style.setProperty('--mx', (e.clientX - r.left) + 'px'); c.style.setProperty('--my', (e.clientY - r.top) + 'px'); }
    });
    const cur = $('.cursor'); let x = -100, y = -100, cx = x, cy = y, raf;
    document.addEventListener('pointermove', (e) => { x = e.clientX; y = e.clientY; cur.classList.add('is-on'); if (!raf) raf = requestAnimationFrame(loop);
      cur.classList.toggle('is-link', !!e.target.closest('a,button,[data-attorney],.faq__q,label,select,input,textarea')); });
    document.addEventListener('mouseleave', () => cur.classList.remove('is-on'));
    function loop() { cx += (x - cx) * 0.18; cy += (y - cy) * 0.18; cur.style.transform = `translate3d(${cx}px,${cy}px,0)`; raf = (Math.abs(x - cx) + Math.abs(y - cy) > .3) ? requestAnimationFrame(loop) : null; }
  }

  /* ============================== ARRANQUE ============================== */
  history.scrollRestoration = 'manual';
  $('#header-root').innerHTML = U.Navbar();
  $('#footer-root').innerHTML = U.Footer();
  $('#whatsapp-root').innerHTML = U.WhatsAppButton();
  route(true);
  if (!reduced) setTimeout(() => { const w = $('.wa'); if (w) { w.classList.add('is-hint'); setTimeout(() => w.classList.remove('is-hint'), 6500); } }, 4500);
})();
