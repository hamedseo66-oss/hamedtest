document.addEventListener('DOMContentLoaded', function () {

    // Sticky header shadow
    const header = document.getElementById('site-header');
    if (header) {
        window.addEventListener('scroll', () => {
            header.classList.toggle('scrolled', window.scrollY > 10);
        }, { passive: true });
    }

    // Mobile nav toggle
    const hamburger = document.getElementById('hamburger');
    const nav = document.getElementById('main-nav');
    if (hamburger && nav) {
        hamburger.addEventListener('click', () => {
            const open = nav.classList.toggle('open');
            hamburger.classList.toggle('active', open);
            hamburger.setAttribute('aria-expanded', open);
            document.body.style.overflow = open ? 'hidden' : '';
        });
        // Close on outside click
        document.addEventListener('click', (e) => {
            if (!header.contains(e.target) && nav.classList.contains('open')) {
                nav.classList.remove('open');
                hamburger.classList.remove('active');
                hamburger.setAttribute('aria-expanded', 'false');
                document.body.style.overflow = '';
            }
        });
    }

    // Back to top
    const btt = document.getElementById('backToTop');
    if (btt) {
        window.addEventListener('scroll', () => {
            btt.classList.toggle('visible', window.scrollY > 400);
        }, { passive: true });
        btt.addEventListener('click', (e) => {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // Intersection Observer — fade-in animation (if AOS not loaded)
    const aosEls = document.querySelectorAll('[data-aos]');
    if (aosEls.length && typeof AOS === 'undefined') {
        const style = document.createElement('style');
        style.textContent = `
            [data-aos] { opacity: 0; transform: translateY(24px); transition: opacity .55s ease, transform .55s ease; }
            [data-aos].aos-animate { opacity: 1; transform: none; }
            [data-aos-delay="80"].aos-animate  { transition-delay: .08s; }
            [data-aos-delay="100"].aos-animate { transition-delay: .1s; }
            [data-aos-delay="120"].aos-animate { transition-delay: .12s; }
            [data-aos-delay="160"].aos-animate { transition-delay: .16s; }
            [data-aos-delay="240"].aos-animate { transition-delay: .24s; }
            [data-aos-delay="300"].aos-animate { transition-delay: .3s; }
            [data-aos="fade-right"] { transform: translateX(-24px); }
            [data-aos="fade-left"]  { transform: translateX(24px); }
        `;
        document.head.appendChild(style);

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('aos-animate');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

        aosEls.forEach(el => observer.observe(el));
    }

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(link => {
        link.addEventListener('click', (e) => {
            const target = document.querySelector(link.getAttribute('href'));
            if (!target) return;
            e.preventDefault();
            const offset = (header ? header.offsetHeight : 0) + 16;
            window.scrollTo({ top: target.getBoundingClientRect().top + window.scrollY - offset, behavior: 'smooth' });
            // Close mobile nav
            if (nav && nav.classList.contains('open')) {
                nav.classList.remove('open');
                if (hamburger) { hamburger.classList.remove('active'); hamburger.setAttribute('aria-expanded', 'false'); }
                document.body.style.overflow = '';
            }
        });
    });

    // Form submission feedback
    const form = document.querySelector('.appt-form');
    if (form) {
        form.addEventListener('submit', function (e) {
            const btn = form.querySelector('button[type="submit"]');
            if (btn) {
                btn.disabled = true;
                btn.textContent = 'در حال ارسال...';
            }
        });
    }
});
