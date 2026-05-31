<section class="hero" id="hero">
    <div class="hero__bg">
        <div class="hero__shapes">
            <div class="hero__shape hero__shape--1"></div>
            <div class="hero__shape hero__shape--2"></div>
            <div class="hero__shape hero__shape--3"></div>
        </div>
    </div>
    <div class="container">
        <div class="hero__inner">
            <div class="hero__content" data-aos="fade-right">
                <div class="hero__badge">
                    <span class="badge-dot"></span>
                    مجاز از وزارت بهداشت
                </div>
                <h1 class="hero__title"><?php echo physio_hero_title(); ?></h1>
                <p class="hero__subtitle"><?php echo physio_hero_subtitle(); ?></p>
                <div class="hero__stats">
                    <div class="stat">
                        <strong>+۱۵</strong>
                        <span>سال تجربه</span>
                    </div>
                    <div class="stat-divider"></div>
                    <div class="stat">
                        <strong>+۵۰۰۰</strong>
                        <span>بیمار موفق</span>
                    </div>
                    <div class="stat-divider"></div>
                    <div class="stat">
                        <strong>۱۲</strong>
                        <span>متخصص</span>
                    </div>
                </div>
                <div class="hero__actions">
                    <a href="#appointment" class="btn btn--primary btn--lg">
                        رزرو نوبت رایگان
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                    </a>
                    <a href="tel:021-88888888" class="btn btn--outline btn--lg">
                        <?php echo physio_svg('phone'); ?>
                        تماس با ما
                    </a>
                </div>
            </div>

            <div class="hero__visual" data-aos="fade-left">
                <div class="hero__card-main">
                    <div class="hero__image-wrapper">
                        <div class="hero__image-placeholder">
                            <svg viewBox="0 0 320 400" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect width="320" height="400" rx="24" fill="url(#grad1)"/>
                                <defs>
                                    <linearGradient id="grad1" x1="0" y1="0" x2="320" y2="400" gradientUnits="userSpaceOnUse">
                                        <stop offset="0%" stop-color="#0EA5E9"/>
                                        <stop offset="100%" stop-color="#0284C7"/>
                                    </linearGradient>
                                </defs>
                                <circle cx="160" cy="140" r="60" fill="rgba(255,255,255,0.15)"/>
                                <circle cx="160" cy="120" r="30" fill="rgba(255,255,255,0.9)"/>
                                <path d="M140 155 Q160 135 180 155 L190 200 Q160 215 130 200 Z" fill="rgba(255,255,255,0.85)"/>
                                <path d="M130 200 L110 280 L125 280 L140 230" fill="rgba(255,255,255,0.75)"/>
                                <path d="M190 200 L210 280 L195 280 L180 230" fill="rgba(255,255,255,0.75)"/>
                                <text x="160" y="340" font-family="Arial" font-size="14" fill="rgba(255,255,255,0.7)" text-anchor="middle">متخصص فیزیوتراپی</text>
                            </svg>
                        </div>
                    </div>
                    <div class="hero__float-card hero__float-card--1">
                        <div class="float-card-icon float-card-icon--green">
                            <?php echo physio_svg('check'); ?>
                        </div>
                        <div>
                            <strong>درمان موفق</strong>
                            <span>۹۸٪ رضایت بیماران</span>
                        </div>
                    </div>
                    <div class="hero__float-card hero__float-card--2">
                        <div class="float-card-icon float-card-icon--blue">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                        </div>
                        <div>
                            <strong>نوبت امروز</strong>
                            <span>ظرفیت محدود</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="hero__wave">
        <svg viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
            <path d="M0 80V40C360 0 720 80 1080 40C1260 20 1380 10 1440 10V80H0Z" fill="white"/>
        </svg>
    </div>
</section>
