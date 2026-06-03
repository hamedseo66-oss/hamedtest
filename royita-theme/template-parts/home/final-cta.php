<?php
/**
 * Final CTA Section
 *
 * @package Royita
 */
?>
<section class="cta-section" id="final-cta">
    <div class="container">
        <div style="max-width:700px;margin:0 auto;">
            <span class="section-label" style="background:rgba(255,255,255,0.15);color:rgba(255,255,255,0.9);">همین حالا شروع کنید</span>
            <h2 class="cta-section__title" style="margin-top:1rem;">
                آماده همکاری با بهترین کریتورها هستید؟
            </h2>
            <p class="cta-section__subtitle">
                هزاران برند ایرانی از رویتا برای تولید محتوای ویدیویی باکیفیت استفاده می‌کنند. 
                شما هم همین الان شروع کنید.
            </p>

            <div class="cta-section__btns">
                <a href="<?php echo esc_url(home_url('/register/?role=brand')); ?>" class="btn btn-cta btn-xl">
                    🏢 برند هستم — کمپین می‌سازم
                </a>
                <a href="<?php echo esc_url(home_url('/register/?role=creator')); ?>" class="btn btn-outline-white btn-xl">
                    🎬 کریتور هستم — پروژه می‌گیرم
                </a>
            </div>

            <!-- Social proof -->
            <div style="display:flex;align-items:center;justify-content:center;gap:2rem;margin-top:3rem;flex-wrap:wrap;">
                <div style="display:flex;align-items:center;gap:0.5rem;color:rgba(255,255,255,0.7);font-size:0.875rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="rgba(16,185,129,0.8)" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                    ثبت‌نام رایگان
                </div>
                <div style="display:flex;align-items:center;gap:0.5rem;color:rgba(255,255,255,0.7);font-size:0.875rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="rgba(16,185,129,0.8)" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                    بدون نیاز به کارت اعتباری
                </div>
                <div style="display:flex;align-items:center;gap:0.5rem;color:rgba(255,255,255,0.7);font-size:0.875rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="rgba(16,185,129,0.8)" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                    پشتیبانی ۲۴/۷
                </div>
            </div>

            <!-- Stats row -->
            <div style="display:flex;justify-content:center;gap:3rem;margin-top:2.5rem;padding-top:2rem;border-top:1px solid rgba(255,255,255,0.15);flex-wrap:wrap;">
                <div style="text-align:center;">
                    <div style="font-size:1.75rem;font-weight:800;color:#fff;">+۲۰۰۰</div>
                    <div style="font-size:0.8rem;color:rgba(255,255,255,0.6);">کریتور فعال</div>
                </div>
                <div style="text-align:center;">
                    <div style="font-size:1.75rem;font-weight:800;color:#fff;">+۵۰۰</div>
                    <div style="font-size:0.8rem;color:rgba(255,255,255,0.6);">برند همکار</div>
                </div>
                <div style="text-align:center;">
                    <div style="font-size:1.75rem;font-weight:800;color:#fff;">۹۸٪</div>
                    <div style="font-size:0.8rem;color:rgba(255,255,255,0.6);">رضایت مشتری</div>
                </div>
            </div>
        </div>
    </div>
</section>
