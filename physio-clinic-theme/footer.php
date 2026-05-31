<footer class="site-footer">
    <div class="footer-top">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <a href="<?php echo esc_url( home_url('/') ); ?>" class="footer-logo">
                        <span class="logo-icon logo-icon--white">
                            <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="24" cy="24" r="23" fill="rgba(255,255,255,0.15)" stroke="rgba(255,255,255,0.3)" stroke-width="2"/>
                                <path d="M24 10v28M10 24h28" stroke="white" stroke-width="4" stroke-linecap="round"/>
                            </svg>
                        </span>
                        <div>
                            <strong>کلینیک فیزیوتراپی</strong>
                            <small>مرکز تخصصی درمان</small>
                        </div>
                    </a>
                    <p class="footer-desc">کلینیک ما با بیش از ۱۵ سال تجربه، پیشرفته‌ترین روش‌های فیزیوتراپی را با مراقبت شخصی ترکیب می‌کند تا به شما در بازیابی سلامتی و کیفیت زندگی کمک کند.</p>
                    <div class="footer-socials">
                        <a href="#" aria-label="اینستاگرام" class="footer-social">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                        </a>
                        <a href="#" aria-label="واتساپ" class="footer-social">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                        </a>
                        <a href="#" aria-label="تلگرام" class="footer-social">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                        </a>
                    </div>
                </div>

                <div class="footer-col">
                    <h4 class="footer-col__title">خدمات ما</h4>
                    <ul class="footer-links">
                        <li><a href="#">فیزیوتراپی ستون فقرات</a></li>
                        <li><a href="#">درمان آسیب‌های ورزشی</a></li>
                        <li><a href="#">فیزیوتراپی اعصاب</a></li>
                        <li><a href="#">لیزر درمانی</a></li>
                        <li><a href="#">ماساژ درمانی</a></li>
                        <li><a href="#">توانبخشی پس از جراحی</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h4 class="footer-col__title">دسترسی سریع</h4>
                    <ul class="footer-links">
                        <li><a href="#">درباره ما</a></li>
                        <li><a href="#">تیم درمانی</a></li>
                        <li><a href="#">مقالات بهداشتی</a></li>
                        <li><a href="#">سوالات متداول</a></li>
                        <li><a href="#">نوبت‌دهی آنلاین</a></li>
                        <li><a href="#">تماس با ما</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h4 class="footer-col__title">تماس با ما</h4>
                    <ul class="footer-contact">
                        <li>
                            <?php echo physio_svg('phone'); ?>
                            <a href="tel:<?php echo physio_phone(); ?>"><?php echo physio_phone(); ?></a>
                        </li>
                        <li>
                            <?php echo physio_svg('map'); ?>
                            <span>تهران، خیابان ولیعصر، پلاک ۱۲۳</span>
                        </li>
                        <li>
                            <?php echo physio_svg('clock'); ?>
                            <span>شنبه تا پنج‌شنبه: ۸ صبح تا ۸ شب</span>
                        </li>
                    </ul>
                    <div class="footer-emergency">
                        <span>اورژانس:</span>
                        <a href="tel:09123456789">۰۹۱۲-۳۴۵-۶۷۸۹</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container">
            <div class="footer-bottom__inner">
                <p>© <?php echo date('Y'); ?> تمامی حقوق برای کلینیک فیزیوتراپی محفوظ است.</p>
                <ul class="footer-bottom__links">
                    <li><a href="#">حریم خصوصی</a></li>
                    <li><a href="#">شرایط استفاده</a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>

<a href="#" class="back-to-top" id="backToTop" aria-label="بازگشت به بالا">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="18 15 12 9 6 15"/></svg>
</a>

<?php wp_footer(); ?>
</body>
</html>
