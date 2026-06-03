<?php
/**
 * Testimonials Section
 *
 * @package Royita
 */

$brand_testimonials = get_posts([
    'post_type'      => 'royita_testimonial',
    'posts_per_page' => 3,
    'meta_query'     => [['key' => 'testimonial_type', 'value' => 'brand', 'compare' => '=']],
    'meta_key'       => 'testimonial_order',
    'orderby'        => 'meta_value_num',
    'order'          => 'ASC',
]);

$creator_testimonials = get_posts([
    'post_type'      => 'royita_testimonial',
    'posts_per_page' => 3,
    'meta_query'     => [['key' => 'testimonial_type', 'value' => 'creator', 'compare' => '=']],
    'meta_key'       => 'testimonial_order',
    'orderby'        => 'meta_value_num',
    'order'          => 'ASC',
]);

// Fallback sample testimonials if none exist
$sample_brand = [
    ['name' => 'علی محمدی', 'role' => 'مدیر بازاریابی', 'company' => 'شرکت تکنو', 'rating' => 5, 'text' => 'رویتا تجربه کاری فوق‌العاده‌ای برایمان بود. در کمتر از ۴۸ ساعت بهترین کریتورهای مرتبط با صنعت ما را پیدا کردیم. کیفیت محتوا واقعاً بالاتر از انتظارمان بود.'],
    ['name' => 'سارا کریمی', 'role' => 'مدیر برند', 'company' => 'دیجی‌فشن', 'rating' => 5, 'text' => 'سیستم امانت‌داری رویتا به ما اطمینان کامل داد. می‌دانستیم که پولمان در امنیت است تا وقتی کار به خوبی انجام شود. این شفافیت در پرداخت واقعاً ارزشمند است.'],
    ['name' => 'رضا احمدی', 'role' => 'بنیان‌گذار', 'company' => 'استارتاپ فودتک', 'rating' => 5, 'text' => 'به عنوان یک استارتاپ، بودجه محدودی داشتیم. رویتا کمک کرد در بودجه‌ای مناسب، کریتورهای باکیفیت پیدا کنیم. نتایج فروشمان پس از کمپین ۴۰٪ افزایش یافت.'],
];

$sample_creator = [
    ['name' => 'آرش موسوی', 'role' => 'ویدیوگرافر', 'company' => 'فریلنسر', 'rating' => 5, 'text' => 'رویتا درآمد من را به عنوان کریتور متحول کرد. هم‌اکنون ماهانه با ۵ الی ۸ برند کار می‌کنم. سیستم پرداخت مطمئن و پشتیبانی عالی دارد.'],
    ['name' => 'نیلوفر رحیمی', 'role' => 'موشن دیزاینر', 'company' => 'استودیو خلاق', 'rating' => 5, 'text' => 'قبل از رویتا برای پیدا کردن پروژه خیلی وقت می‌گذاشتم. الان کمپین‌های مناسب به صورت خودکار پیشنهاد می‌شوند. درآمدم سه برابر شده!'],
    ['name' => 'کیان صادقی', 'role' => 'اینفلوئنسر', 'company' => '۱۲۰K فالوور', 'rating' => 5, 'text' => 'رویتا به من کمک کرد با برندهای معتبر ارتباط مستقیم برقرار کنم. دیگر نیازی به واسطه‌ها ندارم. مبلغ قراردادها شفاف است و پرداخت به موقع انجام می‌شود.'],
];
?>

<section class="section testimonials-section" id="testimonials">
    <div class="container">
        <div class="section-header">
            <span class="section-label">نظرات مشتریان</span>
            <h2 class="section-title">آنچه کاربران ما می‌گویند</h2>
            <p class="section-subtitle">هزاران برند و کریتور به رویتا اعتماد کرده‌اند</p>
        </div>

        <!-- Tabs -->
        <div class="testimonial-tabs" role="tablist" aria-label="نوع کاربر">
            <button class="testimonial-tab-btn active" 
                    role="tab" 
                    aria-selected="true" 
                    aria-controls="brand-testimonials"
                    id="tab-brands"
                    data-tab="brands">
                🏢 برندها
            </button>
            <button class="testimonial-tab-btn" 
                    role="tab" 
                    aria-selected="false" 
                    aria-controls="creator-testimonials"
                    id="tab-creators"
                    data-tab="creators">
                🎬 کریتورها
            </button>
        </div>

        <!-- Brand Testimonials -->
        <div class="testimonial-slide active" id="brand-testimonials" role="tabpanel" aria-labelledby="tab-brands">
            <?php
            $items = !empty($brand_testimonials) ? $brand_testimonials : null;
            if ($items):
                foreach ($items as $item):
                    $rating  = (int) get_post_meta($item->ID, 'testimonial_rating', true);
                    $text    = get_post_meta($item->ID, 'testimonial_text', true);
                    $name    = get_post_meta($item->ID, 'testimonial_name', true);
                    $role    = get_post_meta($item->ID, 'testimonial_role', true);
                    $company = get_post_meta($item->ID, 'testimonial_company', true);
                    $avatar  = get_post_meta($item->ID, 'testimonial_avatar', true);
            ?>
            <div class="testimonial-card">
                <div class="testimonial-card__stars">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                    <span class="testimonial-card__star <?php echo $i <= $rating ? '' : 'empty'; ?>"><?php echo $i <= $rating ? '★' : '☆'; ?></span>
                    <?php endfor; ?>
                </div>
                <p class="testimonial-card__text">"<?php echo esc_html($text); ?>"</p>
                <div class="testimonial-card__author">
                    <?php if (!empty($avatar['url'])): ?>
                    <img src="<?php echo esc_url($avatar['url']); ?>" alt="<?php echo esc_attr($name); ?>" class="testimonial-card__avatar">
                    <?php else: ?>
                    <div class="testimonial-card__avatar" style="background:var(--royita-primary-xlight);display:flex;align-items:center;justify-content:center;color:var(--royita-primary);font-weight:700;"><?php echo esc_html(mb_substr($name, 0, 1)); ?></div>
                    <?php endif; ?>
                    <div>
                        <div class="testimonial-card__author-name"><?php echo esc_html($name); ?></div>
                        <div class="testimonial-card__author-role"><?php echo esc_html($role); ?> — <?php echo esc_html($company); ?></div>
                    </div>
                </div>
            </div>
            <?php
                endforeach;
            else:
                foreach ($sample_brand as $t):
            ?>
            <div class="testimonial-card">
                <div class="testimonial-card__stars">★★★★★</div>
                <p class="testimonial-card__text">"<?php echo esc_html($t['text']); ?>"</p>
                <div class="testimonial-card__author">
                    <div class="testimonial-card__avatar" style="background:var(--royita-primary-xlight);display:flex;align-items:center;justify-content:center;color:var(--royita-primary);font-weight:700;width:48px;height:48px;border-radius:50%;"><?php echo esc_html(mb_substr($t['name'], 0, 1)); ?></div>
                    <div>
                        <div class="testimonial-card__author-name"><?php echo esc_html($t['name']); ?></div>
                        <div class="testimonial-card__author-role"><?php echo esc_html($t['role']); ?> — <?php echo esc_html($t['company']); ?></div>
                    </div>
                </div>
            </div>
            <?php endforeach; endif; ?>
        </div>

        <!-- Creator Testimonials -->
        <div class="testimonial-slide" id="creator-testimonials" role="tabpanel" aria-labelledby="tab-creators">
            <?php
            $items = !empty($creator_testimonials) ? $creator_testimonials : null;
            if ($items):
                foreach ($items as $item):
                    $rating  = (int) get_post_meta($item->ID, 'testimonial_rating', true);
                    $text    = get_post_meta($item->ID, 'testimonial_text', true);
                    $name    = get_post_meta($item->ID, 'testimonial_name', true);
                    $role    = get_post_meta($item->ID, 'testimonial_role', true);
                    $company = get_post_meta($item->ID, 'testimonial_company', true);
                    $avatar  = get_post_meta($item->ID, 'testimonial_avatar', true);
            ?>
            <div class="testimonial-card">
                <div class="testimonial-card__stars">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                    <span class="testimonial-card__star"><?php echo $i <= $rating ? '★' : '☆'; ?></span>
                    <?php endfor; ?>
                </div>
                <p class="testimonial-card__text">"<?php echo esc_html($text); ?>"</p>
                <div class="testimonial-card__author">
                    <?php if (!empty($avatar['url'])): ?>
                    <img src="<?php echo esc_url($avatar['url']); ?>" alt="<?php echo esc_attr($name); ?>" class="testimonial-card__avatar">
                    <?php else: ?>
                    <div class="testimonial-card__avatar" style="background:rgba(233,98,24,0.1);display:flex;align-items:center;justify-content:center;color:var(--royita-cta);font-weight:700;width:48px;height:48px;border-radius:50%;"><?php echo esc_html(mb_substr($name, 0, 1)); ?></div>
                    <?php endif; ?>
                    <div>
                        <div class="testimonial-card__author-name"><?php echo esc_html($name); ?></div>
                        <div class="testimonial-card__author-role"><?php echo esc_html($role); ?> — <?php echo esc_html($company); ?></div>
                    </div>
                </div>
            </div>
            <?php
                endforeach;
            else:
                foreach ($sample_creator as $t):
            ?>
            <div class="testimonial-card">
                <div class="testimonial-card__stars">★★★★★</div>
                <p class="testimonial-card__text">"<?php echo esc_html($t['text']); ?>"</p>
                <div class="testimonial-card__author">
                    <div class="testimonial-card__avatar" style="background:rgba(233,98,24,0.1);display:flex;align-items:center;justify-content:center;color:var(--royita-cta);font-weight:700;width:48px;height:48px;border-radius:50%;"><?php echo esc_html(mb_substr($t['name'], 0, 1)); ?></div>
                    <div>
                        <div class="testimonial-card__author-name"><?php echo esc_html($t['name']); ?></div>
                        <div class="testimonial-card__author-role"><?php echo esc_html($t['role']); ?> — <?php echo esc_html($t['company']); ?></div>
                    </div>
                </div>
            </div>
            <?php endforeach; endif; ?>
        </div>

        <!-- Controls -->
        <div class="slider-controls">
            <button class="slider-btn" id="testimonialPrev" aria-label="قبلی">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
            </button>
            <div class="slider-dots" role="tablist" aria-label="ناوبری اسلاید">
                <button class="slider-dot active" aria-label="اسلاید ۱" data-index="0"></button>
                <button class="slider-dot" aria-label="اسلاید ۲" data-index="1"></button>
                <button class="slider-dot" aria-label="اسلاید ۳" data-index="2"></button>
            </div>
            <button class="slider-btn" id="testimonialNext" aria-label="بعدی">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
            </button>
        </div>
    </div>
</section>
