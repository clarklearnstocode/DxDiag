/* ==========================================================================
   EstateBook Landing v3.0 — Slideshow + Scroll-reveal
   ========================================================================== */

(function () {
    'use strict';

    /* ─────────────────────────────────────────
       1.  PROPERTY SLIDESHOW
    ───────────────────────────────────────── */
    const dataEl     = document.getElementById('landing-data');
    const imgEl      = document.getElementById('main-slide-img');
    const titleEl    = document.getElementById('slide-title');
    const locEl      = document.getElementById('slide-loc');
    const rateEl     = document.getElementById('slide-rate');
    const amenEl     = document.getElementById('slide-amenities');
    const dotsWrap   = document.getElementById('slideDots');

    if (!dataEl || !imgEl) return;

    let slides = [];
    try { slides = JSON.parse(dataEl.getAttribute('data-slides') || '[]'); }
    catch (e) { slides = []; }

    if (slides.length < 2) return;   // nothing to cycle

    let current  = 0;
    let timer    = null;
    let paused   = false;

    function fmtNumber(n) {
        return Number(n).toLocaleString('en-PH');
    }

    function buildAmenities(p) {
        const parts = [];
        if (p.Property_capacity)  parts.push('👥 ' + p.Property_capacity + ' guests');
        if (p.Property_bathrooms) parts.push('🚿 ' + p.Property_bathrooms + ' baths');
        if (p.Has_pool && p.Has_pool !== '0') parts.push('🏊 Pool');
        return parts;
    }

    function goToSlide(idx) {
        if (idx === current) return;

        /* Fade out image */
        imgEl.style.opacity = '0';
        imgEl.style.transform = 'scale(1.04)';

        setTimeout(function () {
            const p = slides[idx];

            /* Swap content */
            imgEl.src = 'assets/img/' + (p.image_path || 'villa1.png');
            imgEl.onerror = function () { this.src = 'assets/img/villa1.png'; };

            if (titleEl)  titleEl.textContent = p.Property_Name    || '';
            if (rateEl)   rateEl.textContent  = '₱' + fmtNumber(p.Property_rate || 0);

            if (locEl) {
                /* Preserve the SVG icon by only replacing the text node */
                const svg = locEl.querySelector('svg');
                locEl.textContent = ' ' + (p.Property_location || '');
                if (svg) locEl.insertBefore(svg, locEl.firstChild);
            }

            if (amenEl) {
                amenEl.innerHTML = buildAmenities(p)
                    .map(function (t) { return '<span>' + t + '</span>'; })
                    .join('');
            }

            /* Update dots */
            if (dotsWrap) {
                dotsWrap.querySelectorAll('.slide-dot').forEach(function (d, i) {
                    d.classList.toggle('active', i === idx);
                });
            }

            current = idx;

            /* Fade back in */
            imgEl.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            imgEl.style.opacity    = '1';
            imgEl.style.transform  = 'scale(1)';

        }, 320);
    }

    /* Expose globally so PHP-rendered dot onclick can call it */
    window.goToSlide = goToSlide;

    function nextSlide() {
        goToSlide((current + 1) % slides.length);
    }

    function startTimer() {
        clearInterval(timer);
        timer = setInterval(nextSlide, 2800);
    }

    function stopTimer() {
        clearInterval(timer);
    }

    /* Pause on hover, resume on leave */
    const heroCard = document.getElementById('heroCard');
    if (heroCard) {
        heroCard.addEventListener('mouseenter', function () {
            paused = true;
            stopTimer();
        });
        heroCard.addEventListener('mouseleave', function () {
            paused = false;
            startTimer();
        });
    }

    /* Initialise image transition properties */
    imgEl.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
    imgEl.style.opacity    = '1';
    imgEl.style.transform  = 'scale(1)';

    startTimer();


    /* ─────────────────────────────────────────
       2.  SCROLL-REVEAL (IntersectionObserver)
    ───────────────────────────────────────── */
    var prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (!prefersReduced && typeof IntersectionObserver !== 'undefined') {

        /* Stagger delay for grid cards */
        document.querySelectorAll('.fade-card').forEach(function (el, i) {
            el.style.setProperty('--card-delay', (i * 0.10) + 's');
        });

        /* Generic section fade + upward slide */
        var sectionObs = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    sectionObs.unobserve(entry.target);
                }
            });
        }, { threshold: 0.08, rootMargin: '0px 0px -40px 0px' });

        document.querySelectorAll('.fade-section').forEach(function (el) {
            sectionObs.observe(el);
        });

        /* Staggered up-fade for inline .fade-up elements */
        var upObs = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    upObs.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -30px 0px' });

        document.querySelectorAll('.fade-up').forEach(function (el) {
            upObs.observe(el);
        });

        /* Card stagger observer (fires once parent section is in view) */
        var cardObs = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    cardObs.unobserve(entry.target);
                }
            });
        }, { threshold: 0.06, rootMargin: '0px 0px -20px 0px' });

        document.querySelectorAll('.fade-card').forEach(function (el) {
            cardObs.observe(el);
        });

    } else {
        /* Reduced motion: make everything visible instantly */
        document.querySelectorAll('.fade-section, .fade-up, .fade-card').forEach(function (el) {
            el.classList.add('visible');
        });
    }


    /* ─────────────────────────────────────────
       3.  NAV — glass tint on scroll
    ───────────────────────────────────────── */
    var nav = document.querySelector('.glass-nav');
    if (nav) {
        var scrollHandler = function () {
            if (window.scrollY > 60) {
                nav.style.background    = 'rgba(0,31,63,0.96)';
                nav.style.backdropFilter = 'blur(14px)';
                nav.style.boxShadow     = '0 4px 30px rgba(0,0,0,0.28)';
            } else {
                nav.style.background    = '';
                nav.style.backdropFilter = '';
                nav.style.boxShadow     = '';
            }
        };

        window.addEventListener('scroll', scrollHandler, { passive: true });
        scrollHandler(); /* run once on load */
    }


    /* ─────────────────────────────────────────
       4.  SMOOTH ANCHOR SCROLLING
    ───────────────────────────────────────── */
    document.querySelectorAll('a[href^="#"]').forEach(function (link) {
        link.addEventListener('click', function (e) {
            var hash = link.getAttribute('href');
            if (hash === '#') return;
            var target = document.querySelector(hash);
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

})();
