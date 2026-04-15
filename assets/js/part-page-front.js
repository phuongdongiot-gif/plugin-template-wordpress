(function () {
    'use strict';

    /* ================================================================
       1. SCROLL-IN ANIMATIONS (Intersection Observer)
       ================================================================ */
    var fadeEls = document.querySelectorAll('.pp-fade-up');

    if ('IntersectionObserver' in window && fadeEls.length) {
        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12 });

        fadeEls.forEach(function (el) {
            observer.observe(el);
        });
    } else {
        // Fallback: show all immediately
        fadeEls.forEach(function (el) {
            el.classList.add('is-visible');
        });
    }

    /* ================================================================
       2. VIDEO: POSTER → IFRAME ON CLICK
       ================================================================ */
    var posters = document.querySelectorAll('.js-video-poster');

    posters.forEach(function (poster) {
        poster.addEventListener('click', function () {
            var src    = poster.getAttribute('data-src');
            var iframe = document.createElement('iframe');
            iframe.setAttribute('src', src);
            iframe.setAttribute('allowfullscreen', '');
            iframe.setAttribute('allow', 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture');
            iframe.style.cssText = 'position:absolute;inset:0;width:100%;height:100%;border:none;';
            poster.parentNode.insertBefore(iframe, poster);
            poster.remove();
        });
    });

    /* ================================================================
       3. VIDEO AUTOPLAY ON VIEWPORT ENTER
       ================================================================ */
    var selfVideos = document.querySelectorAll('.pp-video-self[autoplay]');

    if ('IntersectionObserver' in window && selfVideos.length) {
        var vObs = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.play().catch(function () {});
                } else {
                    entry.target.pause();
                }
            });
        }, { threshold: 0.3 });

        selfVideos.forEach(function (v) { vObs.observe(v); });
    }

    /* ================================================================
       4. SMOOTH SCROLL (for pp-scroll-btn)
       ================================================================ */
    document.addEventListener('click', function (e) {
        var btn = e.target.closest('.pp-scroll-btn');
        if (!btn) return;

        var href = btn.getAttribute('href');
        if (!href || href.charAt(0) !== '#') return;

        e.preventDefault();
        var target = document.getElementById(href.slice(1));
        if (!target) return;

        var offset = 80; // account for sticky tab bar
        var top    = target.getBoundingClientRect().top + window.pageYOffset - offset;

        window.scrollTo({ top: top, behavior: 'smooth' });
    });

    /* ================================================================
       5. TABBED CONTENT
       ================================================================ */
    var tabNav   = document.querySelector('.pp-tabs-nav');
    var panels   = document.querySelectorAll('.pp-tab-panel');
    var tabsWrap = document.querySelector('.pp-tabs-nav-wrap');

    if (tabNav) {
        tabNav.addEventListener('click', function (e) {
            var btn = e.target.closest('.pp-tab-btn');
            if (!btn) return;

            var idx = btn.getAttribute('data-tab');

            // Update buttons
            tabNav.querySelectorAll('.pp-tab-btn').forEach(function (b) {
                b.classList.remove('is-active');
                b.setAttribute('aria-selected', 'false');
            });
            btn.classList.add('is-active');
            btn.setAttribute('aria-selected', 'true');

            // Update panels
            panels.forEach(function (panel) {
                panel.classList.remove('is-active');
                panel.hidden = true;
            });

            var activePanel = document.getElementById('pp-tab-panel-' + idx);
            if (activePanel) {
                activePanel.classList.add('is-active');
                activePanel.hidden = false;

                // Trigger fade-up on newly visible elements
                activePanel.querySelectorAll('.pp-fade-up:not(.is-visible)').forEach(function (el) {
                    el.classList.add('is-visible');
                });
            }
        });
    }

    /* ================================================================
       6. STICKY TAB BAR — add shadow class on scroll
       ================================================================ */
    if (tabsWrap) {
        var stickyObs = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                tabsWrap.classList.toggle('is-stuck', !entry.isIntersecting);
            });
        }, { rootMargin: '-1px 0px 0px 0px', threshold: [1] });

        stickyObs.observe(tabsWrap);
    }

}());
