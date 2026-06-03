<?php
/**
 * How It Works Section
 *
 * @package Royita
 */
?>
<section class="section how-it-works bg-alt" id="how-it-works">
    <div class="container">
        <div class="section-header">
            <span class="section-label">نحوه کار رویتا</span>
            <h2 class="section-title">چطور کار می‌کند؟</h2>
            <p class="section-subtitle">فرآیند ساده و شفاف ما باعث می‌شود برندها و کریتورها به سرعت به هم متصل شوند</p>
        </div>

        <!-- Tab Switcher -->
        <div class="how-tabs" role="tablist" aria-label="نقش کاربر">
            <button class="how-tab-btn active" 
                    role="tab" 
                    aria-selected="true" 
                    aria-controls="brand-steps" 
                    id="brand-tab"
                    data-tab="brand">
                🏢 برند هستم
            </button>
            <button class="how-tab-btn" 
                    role="tab" 
                    aria-selected="false" 
                    aria-controls="creator-steps" 
                    id="creator-tab"
                    data-tab="creator">
                🎬 کریتور هستم
            </button>
        </div>

        <!-- Brand Steps -->
        <div class="how-steps active" id="brand-steps" role="tabpanel" aria-labelledby="brand-tab">
            <div class="how-step reveal">
                <div class="how-step__number">۱</div>
                <div class="how-step__icon">📋</div>
                <h3 class="how-step__title">ثبت کمپین</h3>
                <p class="how-step__desc">
                    در چند دقیقه کمپین خود را ثبت کنید. بریف، بودجه، پلتفرم مورد نظر و الزامات 
                    تولید محتوا را مشخص کنید. کمپین شما فوراً برای کریتورهای مرتبط نمایش داده می‌شود.
                </p>
            </div>

            <div class="how-step reveal" style="transition-delay:0.15s">
                <div class="how-step__number">۲</div>
                <div class="how-step__icon">📬</div>
                <h3 class="how-step__title">دریافت پیشنهاد</h3>
                <p class="how-step__desc">
                    ظرف ۲۴ ساعت پیشنهادهای متنوع از کریتورهای واجد شرایط دریافت کنید. 
                    پورتفولیو، امتیاز و سابقه هر کریتور را بررسی کنید و بهترین گزینه را انتخاب کنید.
                </p>
            </div>

            <div class="how-step reveal" style="transition-delay:0.3s">
                <div class="how-step__number">۳</div>
                <div class="how-step__icon">🤝</div>
                <h3 class="how-step__title">انتخاب کریتور</h3>
                <p class="how-step__desc">
                    پیشنهاد مورد نظرتان را بپذیرید، مبلغ در سیستم امانت‌داری ما نگهداری می‌شود. 
                    پس از تأیید تحویلی‌ها، وجه به کریتور پرداخت می‌شود.
                </p>
            </div>
        </div>

        <!-- Creator Steps -->
        <div class="how-steps" id="creator-steps" role="tabpanel" aria-labelledby="creator-tab">
            <div class="how-step reveal">
                <div class="how-step__number">۱</div>
                <div class="how-step__icon">👤</div>
                <h3 class="how-step__title">ساخت پروفایل</h3>
                <p class="how-step__desc">
                    پروفایل حرفه‌ای خود را بسازید. نمونه کارها، مهارت‌ها، نرخ‌گذاری و 
                    آمار شبکه‌های اجتماعی خود را اضافه کنید. هرچه پروفایل کامل‌تر، کمپین‌های بیشتری پیدا می‌کنید.
                </p>
            </div>

            <div class="how-step reveal" style="transition-delay:0.15s">
                <div class="how-step__number">۲</div>
                <div class="how-step__icon">✍️</div>
                <h3 class="how-step__title">ارسال پیشنهاد</h3>
                <p class="how-step__desc">
                    کمپین‌های متناسب با تخصص خود را مرور کنید و پیشنهاد ارسال کنید. 
                    نامه پوششی، مبلغ پیشنهادی و مدت زمان تحویل را مشخص کنید.
                </p>
            </div>

            <div class="how-step reveal" style="transition-delay:0.3s">
                <div class="how-step__number">۳</div>
                <div class="how-step__icon">🎥</div>
                <h3 class="how-step__title">اجرای پروژه</h3>
                <p class="how-step__desc">
                    پس از پذیرش پیشنهاد، پروژه را اجرا کنید و تحویلی‌ها را آپلود کنید. 
                    پس از تأیید برند، مبلغ پروژه مستقیماً به حساب شما واریز می‌شود.
                </p>
            </div>
        </div>

        <!-- Bottom CTA -->
        <div style="text-align:center;margin-top:3rem;">
            <a href="<?php echo esc_url(home_url('/register/')); ?>" class="btn btn-primary btn-lg">
                همین الان شروع کنید
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </a>
        </div>
    </div>
</section>
