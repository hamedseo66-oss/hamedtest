<?php
/**
 * Royita Footer Template
 *
 * @package Royita
 */
?>
    </main><!-- #main -->

    <footer class="royita-footer" role="contentinfo">
        <div class="container">
            <div class="royita-footer__grid">

                <!-- Column 1: Brand -->
                <div class="royita-footer__col">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="royita-footer__brand-logo" aria-label="رویتا">
                        <?php
                        if (has_custom_logo()) {
                            $logo_id  = get_theme_mod('custom_logo');
                            $logo_url = wp_get_attachment_image_url($logo_id, 'full');
                            echo '<img src="' . esc_url($logo_url) . '" alt="رویتا" style="height:40px;width:auto;filter:brightness(0) invert(1)">';
                        } else {
                            echo '<span style="font-size:1.75rem;font-weight:800;color:#fff;">رویتا</span>';
                        }
                        ?>
                    </a>

                    <p class="royita-footer__brand-desc">
                        رویتا پلتفرم تخصصی اتصال برندها به بهترین تولیدکنندگان محتوای ویدیویی ایران است. 
                        با بیش از ۲۰۰۰ کریتور فعال، کمپین‌های خود را به واقعیت تبدیل کنید.
                    </p>

                    <!-- Social Links -->
                    <div class="royita-footer__social">
                        <a href="https://instagram.com/royita.ir" 
                           class="royita-footer__social-link" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           aria-label="اینستاگرام رویتا">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                        </a>
                        <a href="https://linkedin.com/company/royita" 
                           class="royita-footer__social-link" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           aria-label="لینکدین رویتا">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg>
                        </a>
                        <a href="https://twitter.com/royita_ir" 
                           class="royita-footer__social-link" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           aria-label="توییتر رویتا">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"/></svg>
                        </a>
                        <a href="https://youtube.com/@royita" 
                           class="royita-footer__social-link" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           aria-label="یوتیوب رویتا">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46a2.78 2.78 0 0 0-1.95 1.96A29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58A2.78 2.78 0 0 0 3.41 19.6C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 0 0 1.95-1.95A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58z"/><polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Column 2: Quick Links -->
                <div class="royita-footer__col">
                    <h3 class="royita-footer__col-title">لینک‌های سریع</h3>
                    <?php
                    wp_nav_menu([
                        'theme_location' => 'footer',
                        'container'      => false,
                        'menu_class'     => 'royita-footer__links',
                        'fallback_cb'    => false,
                        'depth'          => 1,
                    ]);

                    if (!has_nav_menu('footer')):
                    ?>
                    <ul class="royita-footer__links">
                        <li><a href="<?php echo esc_url(home_url('/campaigns/')); ?>">→ کمپین‌های فعال</a></li>
                        <li><a href="<?php echo esc_url(home_url('/creators/')); ?>">→ کریتورها</a></li>
                        <li><a href="<?php echo esc_url(home_url('/how-it-works/')); ?>">→ چطور کار می‌کند؟</a></li>
                        <li><a href="<?php echo esc_url(home_url('/pricing/')); ?>">→ قیمت‌گذاری</a></li>
                        <li><a href="<?php echo esc_url(home_url('/blog/')); ?>">→ وبلاگ</a></li>
                        <li><a href="<?php echo esc_url(home_url('/about/')); ?>">→ درباره ما</a></li>
                        <li><a href="<?php echo esc_url(home_url('/contact/')); ?>">→ تماس با ما</a></li>
                        <li><a href="<?php echo esc_url(home_url('/faq/')); ?>">→ سؤالات متداول</a></li>
                    </ul>
                    <?php endif; ?>
                </div>

                <!-- Column 3: Contact -->
                <div class="royita-footer__col">
                    <h3 class="royita-footer__col-title">تماس با ما</h3>

                    <div class="royita-footer__contact-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        <span>تهران، خیابان ولیعصر، نرسیده به پارک ساعی</span>
                    </div>

                    <div class="royita-footer__contact-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        <a href="tel:+982112345678" style="color:rgba(255,255,255,0.6);text-decoration:none;">۰۲۱-۱۲۳۴۵۶۷۸</a>
                    </div>

                    <div class="royita-footer__contact-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        <a href="mailto:info@royita.ir" style="color:rgba(255,255,255,0.6);text-decoration:none;">info@royita.ir</a>
                    </div>

                    <div class="royita-footer__contact-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        <span>ساعات پاسخ‌گویی: شنبه تا پنج‌شنبه، ۹ صبح تا ۶ عصر</span>
                    </div>
                </div>

                <!-- Column 4: Newsletter -->
                <div class="royita-footer__col">
                    <h3 class="royita-footer__col-title">خبرنامه</h3>
                    <p style="font-size:0.875rem;color:rgba(255,255,255,0.6);margin-bottom:1rem;line-height:1.7;">
                        برای دریافت آخرین اخبار، کمپین‌های جدید و فرصت‌های همکاری در خبرنامه ما عضو شوید.
                    </p>

                    <form class="royita-footer__newsletter-form" id="newsletterForm" novalidate>
                        <input type="email" 
                               name="email" 
                               placeholder="ایمیل شما"
                               class="royita-footer__newsletter-input"
                               aria-label="آدرس ایمیل"
                               required>
                        <button type="submit" class="btn btn-cta btn-sm" aria-label="عضویت در خبرنامه">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                        </button>
                    </form>

                    <p class="newsletter-msg" id="newsletterMsg" style="display:none;font-size:0.75rem;margin-top:0.5rem;"></p>

                    <div style="margin-top:1.5rem;">
                        <p style="font-size:0.75rem;color:rgba(255,255,255,0.4);margin-bottom:0.75rem;">پرداخت امن با:</p>
                        <div style="display:flex;gap:0.5rem;flex-wrap:wrap;">
                            <span style="background:rgba(255,255,255,0.1);border-radius:6px;padding:4px 10px;font-size:11px;color:rgba(255,255,255,0.7);">زرین‌پال</span>
                            <span style="background:rgba(255,255,255,0.1);border-radius:6px;padding:4px 10px;font-size:11px;color:rgba(255,255,255,0.7);">ایدی‌پی</span>
                            <span style="background:rgba(255,255,255,0.1);border-radius:6px;padding:4px 10px;font-size:11px;color:rgba(255,255,255,0.7);">نماد اعتماد</span>
                        </div>
                    </div>
                </div>

            </div><!-- .royita-footer__grid -->
        </div><!-- .container -->

        <!-- Footer Bottom Bar -->
        <div class="royita-footer__bottom-bar">
            <div class="container">
                <div class="royita-footer__bottom">
                    <p class="royita-footer__bottom-copy">
                        &copy; <?php echo esc_html(date('Y')); ?> رویتا. تمامی حقوق محفوظ است.
                    </p>
                    <nav class="royita-footer__bottom-links" aria-label="لینک‌های فوتر">
                        <a href="<?php echo esc_url(home_url('/privacy-policy/')); ?>">حریم خصوصی</a>
                        <a href="<?php echo esc_url(home_url('/terms-of-service/')); ?>">شرایط استفاده</a>
                        <a href="<?php echo esc_url(home_url('/sitemap.xml')); ?>">نقشه سایت</a>
                    </nav>
                </div>
            </div>
        </div>

    </footer><!-- .royita-footer -->

</div><!-- #page -->

<!-- Toast Container -->
<div class="toast-container" id="toastContainer" aria-live="polite" aria-atomic="true"></div>

<script>
// Newsletter form AJAX
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('newsletterForm');
    const msg  = document.getElementById('newsletterMsg');

    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const email = form.querySelector('input[name="email"]').value.trim();
            if (!email) return;

            const btn = form.querySelector('button');
            btn.disabled = true;

            fetch(royitaVars.ajaxurl, {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: new URLSearchParams({
                    action: 'royita_newsletter_subscribe',
                    email:  email,
                    nonce:  royitaVars.nonce,
                }),
            })
            .then(r => r.json())
            .then(data => {
                msg.style.display = 'block';
                msg.style.color   = data.success ? '#34d399' : '#ef4444';
                msg.textContent   = data.success
                    ? 'با موفقیت عضو شدید!'
                    : (data.data && data.data.message ? data.data.message : 'خطایی رخ داد.');
                if (data.success) form.reset();
            })
            .catch(() => {
                msg.style.display = 'block';
                msg.style.color   = '#ef4444';
                msg.textContent   = 'خطا در اتصال به سرور';
            })
            .finally(() => { btn.disabled = false; });
        });
    }
});
</script>

<?php wp_footer(); ?>
</body>
</html>
