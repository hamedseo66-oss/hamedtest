<?php
/**
 * Hero Section
 *
 * @package Royita
 */
?>
<section class="hero-section" aria-label="بخش اصلی">
    <div class="container">
        <div class="hero-section__inner">

            <!-- Right Side: Content -->
            <div class="hero-section__content">
                <div class="hero-section__badge">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    پلتفرم شماره یک ویدیو مارکتینگ ایران
                </div>

                <h1 class="hero-section__title">
                    بهترین تولیدکنندگان ویدیو را در
                    <span>کمتر از ۲۴ ساعت</span>
                    پیدا کنید
                </h1>

                <p class="hero-section__subtitle">
                    رویتا مستقیم‌ترین راه ارتباط برندها با کریتورهای حرفه‌ای ویدیو است. 
                    کمپین بسازید، پیشنهاد دریافت کنید و پروژه را با خیال راحت مدیریت کنید.
                </p>

                <div class="hero-section__ctas">
                    <a href="<?php echo esc_url(home_url('/register/?role=brand')); ?>" class="btn btn-cta btn-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        کمپین بسازید
                    </a>
                    <a href="<?php echo esc_url(home_url('/register/?role=creator')); ?>" class="btn btn-outline-white btn-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/></svg>
                        کریتور شوید
                    </a>
                </div>

                <!-- Trust Indicators -->
                <div class="hero-section__trust">
                    <div class="hero-section__trust-item">+۲۰۰۰ کریتور فعال</div>
                    <div class="hero-section__trust-item">+۵۰۰ برند همکار</div>
                    <div class="hero-section__trust-item">پرداخت امن</div>
                    <div class="hero-section__trust-item">پشتیبانی ۲۴/۷</div>
                </div>
            </div>

            <!-- Left Side: Mock Dashboard Card -->
            <div class="hero-section__visual" aria-hidden="true">
                <div class="hero-dashboard-card">
                    <div class="hero-dashboard-card__header">
                        <span class="hero-dashboard-card__title">داشبورد برند</span>
                        <span class="badge badge-success">
                            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="6"/></svg>
                            آنلاین
                        </span>
                    </div>

                    <div class="hero-dashboard-card__stats">
                        <div class="hero-dashboard-card__stat">
                            <span class="hero-dashboard-card__stat-num">۱۲</span>
                            <span class="hero-dashboard-card__stat-lbl">کمپین فعال</span>
                        </div>
                        <div class="hero-dashboard-card__stat">
                            <span class="hero-dashboard-card__stat-num">۴۷</span>
                            <span class="hero-dashboard-card__stat-lbl">پیشنهاد جدید</span>
                        </div>
                        <div class="hero-dashboard-card__stat">
                            <span class="hero-dashboard-card__stat-num">۸</span>
                            <span class="hero-dashboard-card__stat-lbl">پروژه در حال انجام</span>
                        </div>
                        <div class="hero-dashboard-card__stat">
                            <span class="hero-dashboard-card__stat-num" style="color:var(--royita-success)">۹۸٪</span>
                            <span class="hero-dashboard-card__stat-lbl">رضایت</span>
                        </div>
                    </div>

                    <!-- Active Campaign -->
                    <div class="hero-dashboard-card__campaign">
                        <div class="hero-dashboard-card__campaign-dot"></div>
                        <div class="hero-dashboard-card__campaign-info">
                            <div class="hero-dashboard-card__campaign-name">کمپین تبلیغاتی محصول جدید</div>
                            <div class="hero-dashboard-card__campaign-budget">بودجه: ۵،۰۰۰،۰۰۰ تومان</div>
                        </div>
                        <span class="badge badge-success" style="font-size:10px">فعال</span>
                    </div>

                    <div class="hero-dashboard-card__proposals-bar">
                        <span>۱۲ پیشنهاد</span>
                        <div class="hero-dashboard-card__bar">
                            <div class="hero-dashboard-card__bar-fill" style="width:60%"></div>
                        </div>
                        <span>۲۰ مجاز</span>
                    </div>

                    <!-- Creator suggestions -->
                    <div style="margin-top:12px;padding-top:12px;border-top:1px solid var(--royita-border);">
                        <div style="font-size:11px;color:var(--royita-gray);margin-bottom:8px;">کریتورهای پیشنهادی</div>
                        <div style="display:flex;gap:6px;">
                            <?php
                            $colors = ['#1A6DC7','#E96218','#10B981','#8B5CF6'];
                            $names  = ['آ','ب','پ','ت'];
                            foreach ($colors as $i => $color):
                            ?>
                            <div style="width:32px;height:32px;border-radius:50%;background:<?php echo esc_attr($color); ?>;display:flex;align-items:center;justify-content:center;color:#fff;font-size:13px;font-weight:700;">
                                <?php echo esc_html($names[$i]); ?>
                            </div>
                            <?php endforeach; ?>
                            <div style="width:32px;height:32px;border-radius:50%;background:var(--royita-gray-xlight);display:flex;align-items:center;justify-content:center;color:var(--royita-gray);font-size:10px;font-weight:700;">
                                +۱۶
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Floating badges -->
                <div style="position:absolute;top:60px;left:-20px;background:#fff;border-radius:12px;padding:10px 14px;box-shadow:var(--shadow-lg);display:flex;align-items:center;gap:8px;animation:float 4s ease-in-out infinite;animation-delay:1s;" aria-hidden="true">
                    <span style="width:36px;height:36px;background:var(--royita-success-light);border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:1.2rem;">✅</span>
                    <div>
                        <div style="font-size:11px;font-weight:700;color:var(--royita-dark);">پروژه تأیید شد</div>
                        <div style="font-size:10px;color:var(--royita-gray);">چند لحظه پیش</div>
                    </div>
                </div>

                <div style="position:absolute;bottom:80px;left:-30px;background:#fff;border-radius:12px;padding:10px 14px;box-shadow:var(--shadow-lg);display:flex;align-items:center;gap:8px;animation:float 5s ease-in-out infinite;animation-delay:2s;" aria-hidden="true">
                    <span style="font-size:1.5rem;">💰</span>
                    <div>
                        <div style="font-size:11px;font-weight:700;color:var(--royita-dark);">۳،۵۰۰،۰۰۰ تومان</div>
                        <div style="font-size:10px;color:var(--royita-success);">پرداخت موفق</div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Scroll indicator -->
    <div style="position:absolute;bottom:30px;left:50%;transform:translateX(-50%);text-align:center;color:rgba(255,255,255,0.6);" aria-hidden="true">
        <div style="font-size:11px;margin-bottom:6px;">بیشتر ببینید</div>
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="animation:float 2s ease infinite"><polyline points="6 9 12 15 18 9"/></svg>
    </div>
</section>
