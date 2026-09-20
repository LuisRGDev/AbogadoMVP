/*
 * Comportamientos del sitio público. Sin dependencias y sin scripts en línea
 * (compatible con una CSP estricta). Cada módulo es independiente y tolera la
 * ausencia de su marcado.
 */
const $ = (selector, root = document) => root.querySelector(selector);
const $$ = (selector, root = document) => [...root.querySelectorAll(selector)];
const reduced = matchMedia('(prefers-reduced-motion: reduce)').matches;
const hasObserver = 'IntersectionObserver' in window;

/* ---------- Navegación: estado al hacer scroll y menú móvil ---------- */
function initNavigation() {
    const nav = $('#nav');
    const burger = $('#burger');
    const menu = $('#menu');
    if (!nav) {
        return;
    }

    const menuOpen = () => burger?.getAttribute('aria-expanded') === 'true';
    let ticking = false;
    const update = () => {
        ticking = false;
        nav.classList.toggle('is-solid', window.scrollY > 40 || menuOpen());
    };
    window.addEventListener('scroll', () => {
        if (!ticking) {
            ticking = true;
            requestAnimationFrame(update);
        }
    }, { passive: true });
    update();

    if (!burger || !menu) {
        return;
    }

    const setMenu = (open) => {
        burger.setAttribute('aria-expanded', String(open));
        burger.setAttribute('aria-label', open ? 'Cerrar menú' : 'Abrir menú');
        menu.classList.toggle('is-open', open);
        menu.inert = !open;
        document.documentElement.classList.toggle('is-locked', open);
        update();
    };

    burger.addEventListener('click', () => setMenu(!menuOpen()));
    menu.addEventListener('click', (event) => {
        if (event.target.closest('a')) {
            setMenu(false);
        }
    });
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && menuOpen()) {
            setMenu(false);
            burger.focus();
        }
    });
    window.addEventListener('resize', () => {
        if (window.innerWidth > 1359 && menuOpen()) {
            setMenu(false);
        }
    });
}

/* ---------- Revelado al entrar en pantalla ---------- */
function initReveal() {
    const items = $$('.reveal, .reveal-img, .reveal-steps');
    if (reduced || !hasObserver) {
        items.forEach((item) => item.classList.add('in'));
        return;
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('in');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.14, rootMargin: '0px 0px -6% 0px' });
    items.forEach((item) => observer.observe(item));
}

/* ---------- Parallax: solo se calculan (y se promueven a capa GPU) las imágenes visibles ---------- */
function initParallax() {
    if (reduced) {
        return;
    }

    const layers = $$('[data-parallax]').map((el) => ({ el, box: el.parentElement, k: parseFloat(el.dataset.parallax) }));
    const visible = new Set();
    const byBox = new Map(layers.map((layer) => [layer.box, layer]));

    const paint = () => {
        if (!visible.size) {
            return;
        }
        const vh = window.innerHeight;
        const offsets = [];
        visible.forEach((layer) => {
            const rect = layer.box.getBoundingClientRect();
            offsets.push([layer.el, -((rect.top + rect.height / 2) - vh / 2) * layer.k]);
        });
        offsets.forEach(([el, y]) => {
            el.style.transform = `translate3d(0,${y.toFixed(1)}px,0)`;
        });
    };

    if (hasObserver) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                const layer = byBox.get(entry.target);
                if (!layer) {
                    return;
                }
                if (entry.isIntersecting) {
                    visible.add(layer);
                    layer.el.style.willChange = 'transform';
                } else {
                    visible.delete(layer);
                    layer.el.style.willChange = '';
                }
            });
            paint();
        }, { rootMargin: '100px 0px' });
        layers.forEach((layer) => observer.observe(layer.box));
    } else {
        layers.forEach((layer) => visible.add(layer));
    }

    let ticking = false;
    window.addEventListener('scroll', () => {
        if (!ticking) {
            ticking = true;
            requestAnimationFrame(() => {
                ticking = false;
                paint();
            });
        }
    }, { passive: true });
    paint();

    // Animaciones infinitas (destello, línea de scroll, ping del mapa) en pausa fuera de pantalla.
    if (hasObserver) {
        const pause = new IntersectionObserver((entries) => entries.forEach((entry) => {
            entry.target.classList.toggle('is-offscreen', !entry.isIntersecting);
        }));
        $$('.hero, .page-hero, .map').forEach((el) => pause.observe(el));
    }
}

/* ---------- Carrusel de testimonios ---------- */
function initCarousel() {
    const carousel = $('[data-carousel]');
    if (!carousel) {
        return;
    }

    const slides = $$('.testimonial', carousel);
    const dots = $$('[data-dot]', carousel);
    const toggle = $('[data-toggle]', carousel);
    if (slides.length < 2) {
        return;
    }

    let index = 0;
    let playing = !reduced;
    let paused = false;
    let inView = true;
    let timer;

    const show = (n) => {
        index = (n + slides.length) % slides.length;
        slides.forEach((slide, k) => {
            slide.classList.toggle('is-active', k === index);
            slide.setAttribute('aria-hidden', k === index ? 'false' : 'true');
        });
        dots.forEach((dot, k) => dot.setAttribute('aria-selected', String(k === index)));
    };
    const play = () => {
        clearInterval(timer);
        if (playing) {
            timer = setInterval(() => {
                if (!paused && inView && !document.hidden) {
                    show(index + 1);
                }
            }, 7000);
        }
    };

    if (hasObserver) {
        new IntersectionObserver(([entry]) => {
            inView = entry.isIntersecting;
        }).observe(carousel);
    }

    carousel.addEventListener('click', (event) => {
        if (event.target.closest('[data-next]')) {
            show(index + 1);
        } else if (event.target.closest('[data-prev]')) {
            show(index - 1);
        } else if (event.target.closest('[data-dot]')) {
            show(Number(event.target.closest('[data-dot]').dataset.dot));
        } else if (event.target.closest('[data-toggle]')) {
            playing = !playing;
            toggle.textContent = playing ? 'Pausar' : 'Reanudar';
            toggle.setAttribute('aria-label', playing ? 'Pausar rotación automática' : 'Reanudar rotación automática');
            $('.carousel__stage', carousel).setAttribute('aria-live', playing ? 'off' : 'polite');
            play();
        }
    });
    ['mouseenter', 'focusin'].forEach((type) => carousel.addEventListener(type, () => { paused = true; }));
    ['mouseleave', 'focusout'].forEach((type) => carousel.addEventListener(type, () => { paused = false; }));
    carousel.addEventListener('keydown', (event) => {
        if (event.key === 'ArrowRight') {
            show(index + 1);
        }
        if (event.key === 'ArrowLeft') {
            show(index - 1);
        }
    });

    if (toggle && reduced) {
        toggle.textContent = 'Reanudar';
    }
    show(0);
    play();
}

/* ---------- Luz que sigue al cursor en las tarjetas de área (un cálculo por frame) ---------- */
function initCardSpotlight() {
    if (reduced || !matchMedia('(hover: hover) and (pointer: fine)').matches) {
        return;
    }
    let event = null;
    let frame = 0;
    document.addEventListener('pointermove', (e) => {
        event = e;
        if (!frame) {
            frame = requestAnimationFrame(() => {
                frame = 0;
                const card = event.target.closest?.('.area-card');
                if (card) {
                    const rect = card.getBoundingClientRect();
                    card.style.setProperty('--mx', `${event.clientX - rect.left}px`);
                    card.style.setProperty('--my', `${event.clientY - rect.top}px`);
                }
            });
        }
    }, { passive: true });
}

/* ---------- Sugerencia flotante de WhatsApp ---------- */
function initWhatsapp() {
    const wa = $('[data-wa]');
    if (!wa || reduced) {
        return;
    }
    setTimeout(() => {
        wa.classList.add('is-hint');
        setTimeout(() => wa.classList.remove('is-hint'), 6500);
    }, 4500);
}

/* ---------- Progreso de lectura y compartir (artículos) ---------- */
function initArticle() {
    const bar = $('[data-progress]');
    const article = $('.article-main');
    if (bar && article) {
        let ticking = false;
        const update = () => {
            ticking = false;
            const rect = article.getBoundingClientRect();
            const total = rect.height - window.innerHeight * 0.6;
            const progress = total > 0 ? Math.min(1, Math.max(0, -rect.top / total)) : 0;
            bar.style.setProperty('--p', progress.toFixed(3));
        };
        window.addEventListener('scroll', () => {
            if (!ticking) {
                ticking = true;
                requestAnimationFrame(update);
            }
        }, { passive: true });
        update();
    }

    $$('[data-copy]').forEach((button) => {
        button.addEventListener('click', async () => {
            const status = $('[data-copy-ok]');
            try {
                await navigator.clipboard.writeText(button.dataset.copy);
                if (status) {
                    status.textContent = 'Enlace copiado';
                    setTimeout(() => { status.textContent = ''; }, 2500);
                }
            } catch {
                window.prompt('Copie el enlace:', button.dataset.copy);
            }
        });
    });
}

/* ---------- Formulario de contacto: validación en el navegador y envío sin recargar ---------- */
function initContactForm() {
    const form = $('[data-contact-form]');
    if (!form) {
        return;
    }

    const status = $('#form-status', form);
    const errorIds = { preferred_date: 'date', preferred_slot: 'slot', meeting_mode: 'mode' };
    const setError = (name, message) => {
        const field = form.elements[name];
        const holder = $(`#e-${errorIds[name] ?? name}`, form);
        if (holder) {
            holder.textContent = message;
        }
        if (field?.setAttribute) {
            field.setAttribute('aria-invalid', message ? 'true' : 'false');
        }

        return !message;
    };

    const validate = () => {
        const value = (name) => (form.elements[name]?.value ?? '').trim();
        const appointment = form.elements.type.value === 'appointment';
        const checks = [
            ['name', value('name').length < 2 ? 'Ingrese su nombre.' : ''],
            ['email', !/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(value('email')) ? 'Ingrese un correo electrónico válido.' : ''],
            ['phone', value('phone') && value('phone').replace(/\D/g, '').length < 7 ? 'Ingrese un teléfono válido o déjelo en blanco.' : ''],
            ['message', value('message').length < 10 ? 'Describa brevemente su consulta (mínimo 10 caracteres).' : ''],
            ['consent', !form.elements.consent.checked ? 'Debe confirmar que ha leído el Aviso de Privacidad.' : ''],
            ['preferred_date', appointment && !value('preferred_date') ? 'Elija una fecha preferida.' : ''],
            ['preferred_slot', appointment && !value('preferred_slot') ? 'Elija un horario.' : ''],
            ['meeting_mode', appointment && !value('meeting_mode') ? 'Elija una modalidad.' : ''],
        ];
        let first = null;
        checks.forEach(([name, message]) => {
            if (!setError(name, message) && !first) {
                first = form.elements[name];
            }
        });

        return first;
    };

    form.addEventListener('input', (event) => {
        if (event.target.getAttribute?.('aria-invalid') === 'true') {
            validate();
        }
    });

    form.addEventListener('submit', async (event) => {
        const invalid = validate();
        if (invalid) {
            event.preventDefault();
            invalid.focus();
            return;
        }
        if (!window.fetch || !window.FormData) {
            return; // envío tradicional
        }

        event.preventDefault();
        status.className = 'form__status';
        status.textContent = '';
        form.classList.add('is-sending');

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                body: new FormData(form),
                credentials: 'same-origin',
            });
            const data = await response.json().catch(() => ({}));

            if (response.ok) {
                form.reset();
                status.className = 'form__status is-ok';
                status.textContent = data.message ?? 'Hemos recibido su solicitud.';
                status.setAttribute('tabindex', '-1');
                status.focus();
            } else if (response.status === 422 && data.errors) {
                Object.entries(data.errors).forEach(([name, messages]) => setError(name, messages[0]));
                status.className = 'form__status is-error';
                status.textContent = 'Revise los campos marcados para continuar.';
            } else {
                throw new Error(String(response.status));
            }
        } catch {
            status.className = 'form__status is-error';
            status.textContent = 'No fue posible enviar su solicitud. Inténtelo de nuevo o contáctenos por teléfono o WhatsApp.';
        } finally {
            form.classList.remove('is-sending');
        }
    });
}

/* ---------- Enfocar mensajes y resultados tras enviar un formulario ---------- */
function initAutofocus() {
    const target = $('[data-autofocus]') ?? (location.hash === '#resultado' ? $('#resultado') : null);
    target?.focus({ preventScroll: false });
}

function init() {
    // Cada módulo es independiente: un fallo en uno no debe dejar el resto sin funcionar.
    [initNavigation, initReveal, initParallax, initCarousel, initCardSpotlight, initWhatsapp, initArticle, initContactForm, initAutofocus].forEach((module) => {
        try {
            module();
        } catch (error) {
            console.error(error);
        }
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
} else {
    init();
}
