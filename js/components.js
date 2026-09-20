/* =========================================================================
   COMPONENTES REUTILIZABLES
   Cada componente es una función pura: (datos) → HTML. Sin dependencias.
   Equivalencia para migrar:
     Laravel  → Blade components (<x-button>, <x-practice-area-card> …)
     React    → componentes funcionales con las mismas props
   ========================================================================= */
(function () {
  const S = window.SITE;
  const digits = (s) => String(s).replace(/\D/g, '');
  const fmtDate = (iso) =>
    new Date(iso + 'T12:00:00').toLocaleDateString(S.firm.locale, { day: 'numeric', month: 'long', year: 'numeric' });

  /* ------------------------------- Iconos ------------------------------- */
  const ICONS = {
    arrow: '<path d="M4 12h16M14 6l6 6-6 6"/>',
    building: '<path d="M4 21V5l9-2v18M13 8l7 2v11M2 21h20M8 8h1M8 12h1M8 16h1M16 13h1M16 17h1"/>',
    columns: '<path d="M3 8l9-5 9 5M5 8v11M10 8v11M14 8v11M19 8v11M3 21h18"/>',
    people: '<circle cx="9" cy="8" r="3.2"/><path d="M3 20c0-3.5 2.7-6 6-6s6 2.5 6 6"/><circle cx="17" cy="9" r="2.4"/><path d="M16 14.2c3 0 5 2 5 5"/>',
    house: '<path d="M3 11l9-7 9 7M5 10v10h14V10M10 20v-6h4v6"/>',
    document: '<path d="M6 3h8l4 4v14H6zM14 3v4h4M9 12h6M9 16h6"/>',
    mark: '<circle cx="12" cy="12" r="9"/><path d="M15 9.5a3.5 3.5 0 1 0 0 5"/>',
    menu: '<path d="M4 8h16M4 16h16"/>',
    close: '<path d="M5 5l14 14M19 5L5 19"/>',
    left: '<path d="M20 12H4M10 6l-6 6 6 6"/>',
    pin: '<path d="M12 21s7-5.6 7-11a7 7 0 1 0-14 0c0 5.400 7 11 7 11z"/><circle cx="12" cy="10" r="2.500"/>',
    phone: '<path d="M5 4h4l2 5-2.500 1.500a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2z"/>',
    mail: '<rect x="3" y="5" width="18" height="14" rx="1"/><path d="M3 7l9 6 9-6"/>',
    clock: '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
    whatsapp: '<path d="M20 11.500a8 8 0 0 1-11.900 7L4 20l1.500-4A8 8 0 1 1 20 11.500z"/><path d="M9 8.500c0 3 3 6 6 6l1-1.500-2-1-1 .7c-.8-.4-1.600-1.200-2-2l.7-1-1-2z"/>',
    check: '<path d="M5 12.500l4.500 4.500L19 7.500"/>'
  };
  function icon(name, cls = '', size = 24) {
    const body = (ICONS[name] || '').replace(/<(path|circle|rect)\s/g, '<$1 pathLength="1" ');
    return `<svg class="icon ${cls}" width="${size}" height="${size}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">${body}</svg>`;
  }

  /* ------------------------------- Botón ------------------------------- */
  function Button({ label, href, variant = 'primary', arrow = true, cls = '', attrs = '', type = 'button' }) {
    const inner = `<span class="btn__label">${label}</span>${arrow ? icon('arrow', 'btn__arrow', 18) : ''}`;
    const c = `btn btn--${variant} ${cls}`.trim();
    return href
      ? `<a class="${c}" href="${href}" ${attrs}>${inner}</a>`
      : `<button class="${c}" type="${type}" ${attrs}>${inner}</button>`;
  }

  /* -------------------------- Encabezado de sección ------------------------- */
  function SectionHeader({ eyebrow, title, text, tag = 'h2', align = '', id = '' }) {
    return `<header class="section-head ${align}">
      ${eyebrow ? `<p class="eyebrow reveal">${eyebrow}</p>` : ''}
      <${tag} class="h2 reveal" ${id ? `id="${id}"` : ''} style="--d:.08s">${title}</${tag}>
      ${text ? `<p class="lead reveal" style="--d:.16s">${text}</p>` : ''}
    </header>`;
  }

  /* --------------------------- Tarjeta de área --------------------------- */
  function PracticeAreaCard(a, i = 0) {
    return `<a class="area-card reveal" style="--d:${(i % 3) * 0.09}s" href="#/areas/${a.slug}" aria-label="${a.title}: conocer área">
      <span class="area-card__top">
        <span class="area-card__num" aria-hidden="true">${a.num}</span>
        ${icon(a.icon, 'area-card__icon', 34)}
      </span>
      <h3 class="area-card__title">${a.title}</h3>
      <p class="area-card__text">${a.short}</p>
      <span class="area-card__more">Conocer área <span class="area-card__arrow">${icon('arrow', '', 18)}</span></span>
    </a>`;
  }

  /* --------------------------- Tarjeta de abogado --------------------------- */
  function AttorneyCard(p, i = 0) {
    return `<article class="attorney reveal" style="--d:${i * 0.1}s">
      <button class="attorney__photo" type="button" data-attorney="${p.slug}" aria-label="Ver perfil de ${p.name}">
        <img src="${p.image}" alt="Retrato de ${p.name}, ${p.position} (imagen de marcador de posición)" loading="lazy" width="800" height="1000">
        ${p.demo ? '<span class="tag tag--demo">Demo</span>' : ''}
      </button>
      <div class="attorney__body">
        <p class="attorney__role">${p.position}</p>
        <h3 class="attorney__name">${p.name}</h3>
        <p class="attorney__areas">${p.areas.join(' · ')}</p>
        <p class="attorney__bio">${p.bioShort}</p>
        <p class="attorney__edu"><span>Formación</span> ${p.education[0]}</p>
        <p class="attorney__edu"><span>Credenciales</span> ${p.credentials}</p>
        <button class="link-arrow" type="button" data-attorney="${p.slug}">Ver perfil ${icon('arrow', '', 16)}</button>
      </div>
    </article>`;
  }

  /* ---------------------------- Testimonio ---------------------------- */
  function TestimonialCard(t, i, total) {
    return `<figure class="testimonial" data-slide="${i}" role="group" aria-roledescription="diapositiva" aria-label="${i + 1} de ${total}" ${i ? 'aria-hidden="true"' : ''}>
      <span class="testimonial__mark" aria-hidden="true">“</span>
      <blockquote class="testimonial__quote">${t.quote}</blockquote>
      <figcaption class="testimonial__by"><strong>— ${t.author}</strong><span>${t.area}</span></figcaption>
    </figure>`;
  }

  /* ----------------------------- Artículo ----------------------------- */
  function BlogCard(a, i = 0) {
    return `<article class="blog-card reveal" data-cat="${a.category}" style="--d:${i * 0.1}s">
      <a class="blog-card__media" href="#/insights/${a.slug}" tabindex="-1" aria-hidden="true">
        <img src="${a.image}" alt="" loading="lazy" width="1200" height="800">
      </a>
      <div class="blog-card__meta"><span>${a.category}</span><span>${fmtDate(a.date)}</span><span>${a.readTime} de lectura</span></div>
      <h3 class="blog-card__title"><a href="#/insights/${a.slug}">${a.title}</a></h3>
      <p class="blog-card__excerpt">${a.excerpt}</p>
      <a class="link-arrow" href="#/insights/${a.slug}">Leer artículo ${icon('arrow', '', 16)}</a>
    </article>`;
  }

  /* ------------------------------ Acordeón ------------------------------ */
  function FAQAccordion(items, prefix = 'faq') {
    return `<div class="faq" data-faq>${items.map(([q, a], i) => `
      <div class="faq__item reveal" style="--d:${Math.min(i, 5) * 0.05}s">
        <h3 class="faq__q"><button type="button" id="${prefix}-b${i}" aria-expanded="false" aria-controls="${prefix}-p${i}">
          <span>${q}</span><i class="faq__icon" aria-hidden="true"></i></button></h3>
        <div class="faq__panel" id="${prefix}-p${i}" role="region" aria-labelledby="${prefix}-b${i}"><div><p>${a}</p></div></div>
      </div>`).join('')}</div>`;
  }

  /* ------------------------- Formulario de contacto ------------------------- */
  function ContactForm(prefArea = '') {
    const c = S.contactSection;
    return `<form class="form" id="contact-form" novalidate aria-describedby="form-legal">
      <div class="field"><label for="f-nombre">Nombre</label>
        <input id="f-nombre" name="nombre" type="text" autocomplete="name" required>
        <p class="field__err" id="e-nombre" role="alert"></p></div>
      <div class="field-row">
        <div class="field"><label for="f-email">Email</label>
          <input id="f-email" name="email" type="email" autocomplete="email" required>
          <p class="field__err" id="e-email" role="alert"></p></div>
        <div class="field"><label for="f-tel">Teléfono</label>
          <input id="f-tel" name="telefono" type="tel" autocomplete="tel" inputmode="tel">
          <p class="field__err" id="e-tel" role="alert"></p></div>
      </div>
      <div class="field"><label for="f-area">Área de interés</label>
        <div class="select"><select id="f-area" name="area" required>
          <option value="">Seleccione un área</option>
          ${S.areas.map((a) => `<option ${a.title === prefArea ? 'selected' : ''}>${a.title}</option>`).join('')}
          <option>Otra / No estoy seguro(a)</option></select></div>
        <p class="field__err" id="e-area" role="alert"></p></div>
      <div class="field"><label for="f-msg">Mensaje <small>(descripción general, sin datos sensibles)</small></label>
        <textarea id="f-msg" name="mensaje" rows="4" maxlength="800" required></textarea>
        <p class="field__err" id="e-msg" role="alert"></p></div>
      <div class="field field--hp" aria-hidden="true"><label>No llenar<input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
      <div class="field field--check"><label class="check"><input type="checkbox" id="f-priv" name="privacidad" required>
        <span class="check__box" aria-hidden="true">${icon('check', '', 14)}</span>
        <span>${c.consent} <a class="u-link" href="#/privacidad">${c.consentLink}</a>.</span></label>
        <p class="field__err" id="e-priv" role="alert"></p></div>
      ${Button({ label: c.submit, variant: 'primary', type: 'submit', cls: 'btn--block' })}
      <p class="form__legal" id="form-legal">${c.disclaimer}</p>
      <p class="form__status" id="form-status" role="status" aria-live="polite"></p>
    </form>`;
  }

  /* ----------------------------- Botón WhatsApp ----------------------------- */
  function waUrl(extra) {
    const w = S.contact.whatsapp;
    return `https://wa.me/${digits(w.number)}?text=${encodeURIComponent(extra || w.message)}`;
  }
  function WhatsAppButton() {
    const w = S.contact.whatsapp;
    return `<div class="wa">
      <span class="wa__tip" id="wa-tip" role="tooltip">${w.tooltip}</span>
      <a class="wa__btn" href="${waUrl()}" target="_blank" rel="noopener noreferrer" aria-label="Escribir por WhatsApp" aria-describedby="wa-tip">
        ${icon('whatsapp', '', 28)}
      </a></div>`;
  }

  /* ------------------------------ Navbar ------------------------------ */
  function Logo(cls = '') {
    return `<a class="logo ${cls}" href="#/" aria-label="${S.firm.name} — inicio">
      <svg class="logo__mark" width="30" height="30" viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="1.3" aria-hidden="true"><rect x=".65" y=".65" width="30.700" height="30.700"/><path d="M10 24V8h7.500a4.500 4.500 0 0 1 0 9H10" stroke="#C5A46D"/></svg>
      <span class="logo__text">${S.firm.name}</span></a>`;
  }
  function Navbar() {
    const links = S.nav.map((n) => `<li><a class="nav__link" href="${n.href}" data-nav>${n.label}</a></li>`).join('');
    const mlinks = S.nav.map((n, i) => `<li style="--i:${i}"><a href="${n.href}">${n.label}</a></li>`).join('');
    return `<header class="nav" id="nav">
      <div class="nav__inner container">
        ${Logo()}
        <nav class="nav__links" aria-label="Principal"><ul>${links}</ul></nav>
        <div class="nav__actions">
          ${Button({ label: S.ctaLabel, href: '#/contacto', variant: 'gold', arrow: false, cls: 'btn--sm nav__cta' })}
          <button class="burger" id="burger" type="button" aria-expanded="false" aria-controls="menu" aria-label="Abrir menú">
            <span></span><span></span></button>
        </div>
      </div>
      <div class="menu" id="menu" aria-hidden="true">
        <nav aria-label="Menú móvil"><ul>${mlinks}</ul></nav>
        <div class="menu__foot">
          ${Button({ label: S.ctaLabel, href: '#/contacto', variant: 'gold' })}
          <p>${S.contact.phone}<br>${S.contact.email}</p>
        </div>
      </div>
    </header>`;
  }

  /* ------------------------------ Footer ------------------------------ */
  function Footer() {
    const a = S.contact.address;
    return `<footer class="footer on-dark">
      <div class="container footer__grid">
        <div class="footer__brand">
          ${Logo()}
          <p>${S.firm.shortDescription}</p>
          <ul class="footer__social" aria-label="Redes sociales">
            ${S.contact.social.map((s) => `<li><a class="u-link" href="${s.url}" target="_blank" rel="noopener noreferrer">${s.name}</a></li>`).join('')}
          </ul>
        </div>
        <nav aria-label="Navegación del pie"><h2 class="footer__h">Navegación</h2>
          <ul>${S.footerNav.map((n) => `<li><a class="u-link" href="${n.href}">${n.label}</a></li>`).join('')}</ul></nav>
        <div><h2 class="footer__h">Contacto</h2>
          <ul>
            <li><a class="u-link" href="tel:${'+' + digits(S.contact.phone)}">${S.contact.phone}</a></li>
            <li><a class="u-link" href="mailto:${S.contact.email}">${S.contact.email}</a></li>
            <li>${a.line1}<br>${a.line2}<br>${a.city}, ${a.country}</li>
          </ul></div>
        <div><h2 class="footer__h">Legal</h2>
          <ul>
            <li><a class="u-link" href="#/privacidad">Aviso de Privacidad</a></li>
            <li><a class="u-link" href="#/terminos">Términos y condiciones</a></li>
            <li><a class="u-link" href="#/disclaimer">Disclaimer legal</a></li>
          </ul></div>
      </div>
      <div class="container footer__bottom">
        <p class="footer__disc">${S.legal.footerDisclaimer}</p>
        <p>© ${S.firm.year} ${S.firm.name}. Todos los derechos reservados.</p>
      </div>
    </footer>`;
  }

  /* ---------------------- Cabecera de vista (con imagen) ---------------------- */
  function crumbs(items) {
    return `<nav class="crumbs" aria-label="Migas de pan"><ol>${items.map(([l, h]) =>
      `<li>${h ? `<a href="${h}">${l}</a>` : `<span aria-current="page">${l}</span>`}</li>`).join('')}</ol></nav>`;
  }
  function PageHero({ eyebrow, title, text, image, alt = '', crumb, children = '', cls = '' }) {
    return `<section class="page-hero on-dark ${cls}">
      ${image ? `<div class="page-hero__media" data-parallax=".12"><img src="${image}" alt="${alt}" width="1920" height="1080" fetchpriority="high"></div>` : ''}
      <div class="page-hero__shade"></div><div class="grain" aria-hidden="true"></div>
      <div class="container page-hero__inner">
        ${crumb ? crumbs(crumb) : ''}
        ${eyebrow ? `<p class="eyebrow eyebrow--gold page-hero__eyebrow">${eyebrow}</p>` : ''}
        <h1 class="page-hero__title">${title}</h1>
        ${text ? `<p class="page-hero__text">${text}</p>` : ''}
        ${children}
      </div></section>`;
  }

  /* ------------------- Banda de imagen con frase editorial ------------------- */
  function Band({ image, quote, cite }) {
    return `<section class="band on-dark" aria-label="${cite}">
      <div class="band__media" data-parallax=".1"><img src="${image}" alt="" loading="lazy" width="1920" height="1080"></div>
      <div class="band__shade"></div>
      <div class="container band__inner">
        <span class="gline gline--h" aria-hidden="true"></span>
        <blockquote class="band__quote reveal">${quote}</blockquote>
        <p class="band__cite reveal" style="--d:.12s">${cite}</p>
      </div></section>`;
  }

  /* ------------------ Enlace a la siguiente vista (navegación) ------------------ */
  function NextPage({ href, label, title, image }) {
    return `<a class="next-page on-dark" href="${href}">
      <div class="next-page__media"><img src="${image}" alt="" loading="lazy" width="1920" height="1080"></div>
      <div class="next-page__shade"></div>
      <div class="container next-page__inner">
        <span class="next-page__label">${label}</span>
        <span class="next-page__title">${title}</span>
        <span class="next-page__arrow">${icon('arrow', '', 44)}</span>
      </div></a>`;
  }

  /* ------------------------------ Modal ------------------------------ */
  const Modal = (function () {
    let lastFocus = null, root, onClose;
    const focusables = (el) => [...el.querySelectorAll('a[href],button:not([disabled]),input,select,textarea,[tabindex]:not([tabindex="-1"])')].filter((e) => e.offsetParent !== null);
    function trap(e) {
      if (!root || !root.firstElementChild) return;
      if (e.key === 'Escape') return close();
      if (e.key !== 'Tab') return;
      const f = focusables(root); if (!f.length) return;
      const first = f[0], last = f[f.length - 1];
      if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
      else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
    }
    function open(html, labelId, cb) {
      root = document.getElementById('modal-root');
      lastFocus = document.activeElement; onClose = cb;
      root.innerHTML = `<div class="modal" role="dialog" aria-modal="true" aria-labelledby="${labelId}">
        <div class="modal__backdrop" data-close></div>
        <div class="modal__panel" tabindex="-1">
          <button class="modal__close" type="button" data-close aria-label="Cerrar">${icon('close', '', 22)}</button>
          ${html}</div></div>`;
      document.documentElement.classList.add('is-locked');
      requestAnimationFrame(() => {
        root.firstElementChild.classList.add('is-open');
        (root.querySelector('.modal__close') || root).focus();
      });
      root.addEventListener('click', clickClose);
      document.addEventListener('keydown', trap);
    }
    function clickClose(e) { if (e.target.closest('[data-close]')) close(); }
    function close() {
      if (!root || !root.firstElementChild) return;
      const m = root.firstElementChild; m.classList.remove('is-open'); m.classList.add('is-closing');
      document.removeEventListener('keydown', trap); root.removeEventListener('click', clickClose);
      const done = () => { root.innerHTML = ''; document.documentElement.classList.remove('is-locked'); if (lastFocus && lastFocus.focus) lastFocus.focus({ preventScroll: true }); if (onClose) onClose(); };
      matchMedia('(prefers-reduced-motion: reduce)').matches ? done() : setTimeout(done, 360);
    }
    return { open, close };
  })();

  window.UI = { icon, Button, SectionHeader, PracticeAreaCard, AttorneyCard, TestimonialCard, BlogCard,
    FAQAccordion, ContactForm, PageHero, Band, NextPage, crumbs, WhatsAppButton, Navbar, Footer, Modal, Logo, waUrl, fmtDate, digits };
})();
