/**
 * Royita Main JavaScript
 *
 * @package Royita
 */

'use strict';

(function () {

    // =====================================================
    // UTILITIES
    // =====================================================

    const $ = (sel, ctx = document) => ctx.querySelector(sel);
    const $$ = (sel, ctx = document) => [...ctx.querySelectorAll(sel)];

    function debounce(fn, delay) {
        let timer;
        return function (...args) {
            clearTimeout(timer);
            timer = setTimeout(() => fn.apply(this, args), delay);
        };
    }

    function toEasternNumerals(num) {
        const map = { '0': '۰', '1': '۱', '2': '۲', '3': '۳', '4': '۴', '5': '۵', '6': '۶', '7': '۷', '8': '۸', '9': '۹', ',': '،' };
        return String(num).replace(/[0-9,]/g, m => map[m] || m);
    }

    function showToast(message, type = 'info', duration = 4000) {
        const container = $('#toastContainer');
        if (!container) return;

        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        const icons = { success: '✓', error: '✕', info: 'ℹ', warning: '⚠' };
        toast.innerHTML = `<span style="font-size:1.2rem">${icons[type] || 'ℹ'}</span><span>${message}</span>`;

        container.appendChild(toast);

        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(100%)';
            toast.style.transition = '0.3s ease';
            setTimeout(() => toast.remove(), 300);
        }, duration);
    }

    // =====================================================
    // STICKY HEADER
    // =====================================================
    function initStickyHeader() {
        const header = $('#royitaHeader');
        if (!header) return;

        const onScroll = debounce(function () {
            if (window.scrollY > 50) {
                header.classList.add('is-scrolled');
            } else {
                header.classList.remove('is-scrolled');
            }
        }, 10);

        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();
    }

    // =====================================================
    // MOBILE MENU
    // =====================================================
    function initMobileMenu() {
        const hamburger = $('#hamburgerBtn');
        const menu      = $('#mobileMenu');
        const overlay   = $('#mobileMenuOverlay');
        const close     = $('#mobileMenuClose');

        if (!hamburger || !menu) return;

        function openMenu() {
            menu.classList.add('is-open');
            overlay.classList.add('is-active');
            hamburger.classList.add('is-active');
            hamburger.setAttribute('aria-expanded', 'true');
            document.body.style.overflow = 'hidden';
            menu.querySelector('a') && menu.querySelector('a').focus();
        }

        function closeMenu() {
            menu.classList.remove('is-open');
            overlay.classList.remove('is-active');
            hamburger.classList.remove('is-active');
            hamburger.setAttribute('aria-expanded', 'false');
            document.body.style.overflow = '';
        }

        hamburger.addEventListener('click', () => {
            menu.classList.contains('is-open') ? closeMenu() : openMenu();
        });

        overlay.addEventListener('click', closeMenu);
        close && close.addEventListener('click', closeMenu);

        // Close on Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && menu.classList.contains('is-open')) {
                closeMenu();
            }
        });
    }

    // =====================================================
    // HOW IT WORKS TABS
    // =====================================================
    function initHowItWorksTabs() {
        const tabs = $$('.how-tab-btn');
        if (!tabs.length) return;

        tabs.forEach(btn => {
            btn.addEventListener('click', function () {
                const tabName = this.dataset.tab;

                tabs.forEach(t => {
                    t.classList.remove('active');
                    t.setAttribute('aria-selected', 'false');
                });
                this.classList.add('active');
                this.setAttribute('aria-selected', 'true');

                $$('.how-steps').forEach(panel => {
                    panel.classList.remove('active');
                });

                const target = $(`#${tabName}-steps`);
                if (target) target.classList.add('active');
            });
        });
    }

    // =====================================================
    // COUNTER ANIMATION
    // =====================================================
    function initCounters() {
        const counters = $$('.counter');
        if (!counters.length) return;

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (!entry.isIntersecting) return;

                const el       = entry.target;
                const target   = parseInt(el.dataset.target, 10);
                const suffix   = el.dataset.suffix || '';
                const duration = 2000;
                const step     = target / (duration / 16);
                let current    = 0;

                const timer = setInterval(() => {
                    current += step;
                    if (current >= target) {
                        current = target;
                        clearInterval(timer);
                    }
                    const formatted = new Intl.NumberFormat('fa-IR').format(Math.round(current));
                    el.textContent = formatted + suffix;
                }, 16);

                observer.unobserve(el);
            });
        }, { threshold: 0.5 });

        counters.forEach(c => observer.observe(c));
    }

    // =====================================================
    // FAQ ACCORDION
    // =====================================================
    function initFaqAccordion() {
        const faqItems = $$('.faq-item');
        if (!faqItems.length) return;

        faqItems.forEach(item => {
            const question = item.querySelector('.faq-question');
            const answer   = item.querySelector('.faq-answer');

            if (!question || !answer) return;

            question.addEventListener('click', function () {
                const isOpen = item.classList.contains('is-open');

                // Close all
                faqItems.forEach(i => {
                    i.classList.remove('is-open');
                    i.querySelector('.faq-answer') && i.querySelector('.faq-answer').classList.remove('is-open');
                    i.querySelector('.faq-question') && i.querySelector('.faq-question').setAttribute('aria-expanded', 'false');
                });

                // Open clicked
                if (!isOpen) {
                    item.classList.add('is-open');
                    answer.classList.add('is-open');
                    question.setAttribute('aria-expanded', 'true');
                }
            });
        });
    }

    // =====================================================
    // CAMPAIGN FILTER TABS
    // =====================================================
    function initCampaignFilters() {
        const filterBtns = $$('.campaign-filter-btn');
        const sortSelect  = $('#campaignSort');
        const grid        = $('#campaignsGrid');
        const loadMoreBtn = $('#loadMoreCampaigns');

        if (!filterBtns.length && !sortSelect) return;

        let currentPage     = 2;
        let currentCategory = '';
        let currentSort     = 'newest';

        function resetGrid() {
            currentPage = 2;
            if (loadMoreBtn) {
                loadMoreBtn.style.display = '';
            }
        }

        filterBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                filterBtns.forEach(b => {
                    b.classList.remove('btn-primary');
                    b.classList.add('btn-outline');
                    b.setAttribute('aria-selected', 'false');
                });
                this.classList.add('btn-primary');
                this.classList.remove('btn-outline');
                this.setAttribute('aria-selected', 'true');

                currentCategory = this.dataset.category;
                resetGrid();
                loadCampaigns(true);
            });
        });

        sortSelect && sortSelect.addEventListener('change', function () {
            currentSort = this.value;
            resetGrid();
            loadCampaigns(true);
        });

        function loadCampaigns(replace = false, page = 1) {
            if (!grid) return;

            const btn = replace ? null : loadMoreBtn;
            if (btn) {
                btn.disabled = true;
                btn.querySelector('.btn-text') && (btn.querySelector('.btn-text').textContent = typeof royitaVars !== 'undefined' ? royitaVars.strings.loading : 'در حال بارگذاری...');
            }

            if (replace) {
                grid.innerHTML = '<div style="text-align:center;padding:3rem;grid-column:1/-1"><div class="spinner spinner-lg"></div></div>';
            }

            const formData = new FormData();
            formData.append('action',   'royita_load_more_campaigns');
            formData.append('nonce',    typeof royitaVars !== 'undefined' ? royitaVars.nonce : '');
            formData.append('page',     replace ? 1 : currentPage);
            formData.append('category', currentCategory);
            formData.append('sort',     currentSort);

            fetch(typeof royitaVars !== 'undefined' ? royitaVars.ajaxurl : '/wp-admin/admin-ajax.php', {
                method: 'POST',
                body: formData,
            })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        if (replace) {
                            grid.innerHTML = data.data.html || '<div style="grid-column:1/-1;text-align:center;padding:3rem;"><p style="color:var(--royita-gray)">کمپینی یافت نشد</p></div>';
                        } else {
                            grid.insertAdjacentHTML('beforeend', data.data.html);
                            currentPage++;
                        }

                        if (!data.data.has_more && loadMoreBtn) {
                            loadMoreBtn.style.display = 'none';
                        }
                    }
                })
                .catch(() => {
                    showToast(typeof royitaVars !== 'undefined' ? royitaVars.strings.error : 'خطا در بارگذاری', 'error');
                })
                .finally(() => {
                    if (btn) {
                        btn.disabled = false;
                        btn.querySelector('.btn-text') && (btn.querySelector('.btn-text').textContent = 'بارگذاری کمپین‌های بیشتر');
                    }
                });
        }

        // Load More button
        loadMoreBtn && loadMoreBtn.addEventListener('click', () => loadCampaigns(false, currentPage));
    }

    // =====================================================
    // TESTIMONIAL TABS & SLIDER
    // =====================================================
    function initTestimonials() {
        const tabBtns    = $$('.testimonial-tab-btn');
        const slides     = $$('.testimonial-slide');
        const prevBtn    = $('#testimonialPrev');
        const nextBtn    = $('#testimonialNext');
        const dots       = $$('.slider-dot');

        if (!tabBtns.length) return;

        let currentTab   = 0;
        let autoPlayTimer = null;

        function switchTab(index) {
            tabBtns.forEach((btn, i) => {
                btn.classList.toggle('active', i === index);
                btn.setAttribute('aria-selected', i === index ? 'true' : 'false');
            });
            slides.forEach((slide, i) => {
                slide.classList.toggle('active', i === index);
            });
            currentTab = index;
        }

        tabBtns.forEach((btn, i) => {
            btn.addEventListener('click', () => {
                switchTab(i);
                resetAutoPlay();
            });
        });

        function resetAutoPlay() {
            clearInterval(autoPlayTimer);
            autoPlayTimer = setInterval(() => {
                switchTab((currentTab + 1) % tabBtns.length);
            }, 7000);
        }

        // Dots navigation for testimonials inside each slide
        dots.forEach((dot, i) => {
            dot.addEventListener('click', () => {
                dots.forEach(d => d.classList.remove('active'));
                dot.classList.add('active');
            });
        });

        // Prev/Next (switch tabs)
        prevBtn && prevBtn.addEventListener('click', () => {
            const prev = (currentTab - 1 + tabBtns.length) % tabBtns.length;
            switchTab(prev);
            resetAutoPlay();
        });

        nextBtn && nextBtn.addEventListener('click', () => {
            const next = (currentTab + 1) % tabBtns.length;
            switchTab(next);
            resetAutoPlay();
        });

        resetAutoPlay();
    }

    // =====================================================
    // FAVORITE TOGGLE
    // =====================================================
    function initFavorites() {
        document.addEventListener('click', function (e) {
            const btn = e.target.closest('.btn-favorite');
            if (!btn) return;

            e.preventDefault();

            if (typeof royitaVars !== 'undefined' && !royitaVars.isLoggedIn) {
                showToast(royitaVars.strings.loginRequired, 'info');
                return;
            }

            const postId = btn.dataset.postId;
            const type   = btn.dataset.type || 'campaign';

            btn.disabled = true;

            const formData = new FormData();
            formData.append('action',  'royita_toggle_favorite');
            formData.append('nonce',   typeof royitaVars !== 'undefined' ? royitaVars.nonce : '');
            formData.append('post_id', postId);
            formData.append('type',    type);

            fetch(typeof royitaVars !== 'undefined' ? royitaVars.ajaxurl : '/wp-admin/admin-ajax.php', {
                method: 'POST',
                body: formData,
            })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        btn.classList.toggle('is-favorite', data.data.is_favorite);
                        showToast(data.data.message, 'success');
                    } else {
                        showToast(data.data && data.data.message ? data.data.message : 'خطایی رخ داد', 'error');
                    }
                })
                .catch(() => showToast('خطا در اتصال', 'error'))
                .finally(() => { btn.disabled = false; });
        });
    }

    // =====================================================
    // SMOOTH SCROLL
    // =====================================================
    function initSmoothScroll() {
        document.addEventListener('click', function (e) {
            const anchor = e.target.closest('a[href^="#"]');
            if (!anchor) return;

            const id = anchor.getAttribute('href').slice(1);
            if (!id) return;

            const target = document.getElementById(id);
            if (!target) return;

            e.preventDefault();
            const offset = 90;
            const y = target.getBoundingClientRect().top + window.scrollY - offset;
            window.scrollTo({ top: y, behavior: 'smooth' });
        });
    }

    // =====================================================
    // SCROLL REVEAL
    // =====================================================
    function initScrollReveal() {
        const elements = $$('.reveal');
        if (!elements.length || !window.IntersectionObserver) return;

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const delay = entry.target.dataset.revealDelay || 0;
                    setTimeout(() => {
                        entry.target.classList.add('revealed');
                    }, parseInt(delay));
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.15 });

        elements.forEach(el => observer.observe(el));
    }

    // =====================================================
    // FORM VALIDATION HELPERS
    // =====================================================
    function initForms() {
        // Real-time validation feedback
        $$('input[required], textarea[required]').forEach(input => {
            input.addEventListener('blur', function () {
                if (!this.value.trim()) {
                    this.classList.add('is-invalid');
                } else {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                }
            });

            input.addEventListener('input', function () {
                if (this.classList.contains('is-invalid') && this.value.trim()) {
                    this.classList.remove('is-invalid');
                }
            });
        });

        // Email validation
        $$('input[type="email"]').forEach(input => {
            input.addEventListener('blur', function () {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (this.value && !emailRegex.test(this.value)) {
                    this.classList.add('is-invalid');
                }
            });
        });
    }

    // =====================================================
    // LOAD MORE CREATORS (standalone)
    // =====================================================
    function initLoadMoreCreators() {
        const btn  = $('#loadMoreCreators');
        const grid = $('#creatorsGrid');

        if (!btn || !grid) return;

        let page = 2;

        btn.addEventListener('click', function () {
            this.disabled = true;
            const btnText = this.querySelector('.btn-text');
            if (btnText) btnText.textContent = 'در حال بارگذاری...';

            const formData = new FormData();
            formData.append('action', 'royita_load_more_creators');
            formData.append('nonce',  typeof royitaVars !== 'undefined' ? royitaVars.nonce : '');
            formData.append('page',   page);

            // Get active filters
            const specialty    = $('#filterSpecialty')?.value || '';
            const industry     = $('#filterIndustry')?.value || '';
            const availability = $('#filterAvailability')?.value || '';
            formData.append('specialty',    specialty);
            formData.append('industry',     industry);
            formData.append('availability', availability);

            fetch(typeof royitaVars !== 'undefined' ? royitaVars.ajaxurl : '/wp-admin/admin-ajax.php', {
                method: 'POST',
                body: formData,
            })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        grid.insertAdjacentHTML('beforeend', data.data.html);
                        page++;

                        if (!data.data.has_more) {
                            btn.style.display = 'none';
                        }
                    }
                })
                .catch(() => showToast('خطا در بارگذاری', 'error'))
                .finally(() => {
                    this.disabled = false;
                    if (btnText) btnText.textContent = 'بارگذاری کریتورهای بیشتر';
                });
        });
    }

    // =====================================================
    // INIT ALL
    // =====================================================
    function init() {
        initStickyHeader();
        initMobileMenu();
        initHowItWorksTabs();
        initCounters();
        initFaqAccordion();
        initCampaignFilters();
        initTestimonials();
        initFavorites();
        initSmoothScroll();
        initScrollReveal();
        initForms();
        initLoadMoreCreators();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    // Expose utility for other scripts
    window.royita = {
        showToast,
        toEasternNumerals,
        $,
        $$,
    };

})();
