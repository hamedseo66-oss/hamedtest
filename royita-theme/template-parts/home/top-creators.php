<?php
/**
 * Top Creators Section
 *
 * @package Royita
 */

$creators_query = royita_get_top_creators(8);
?>
<section class="section bg-alt" id="top-creators">
    <div class="container">
        <!-- Header -->
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:2.5rem;flex-wrap:wrap;gap:1rem;">
            <div>
                <span class="section-label">بهترین‌های رویتا</span>
                <h2 class="section-title mb-0">کریتورهای برتر</h2>
                <p style="color:var(--royita-gray);font-size:1rem;margin-top:0.5rem;">با بهترین تولیدکنندگان ویدیو ایران همکاری کنید</p>
            </div>
            <a href="<?php echo esc_url(home_url('/creators/')); ?>" class="btn btn-outline">
                مشاهده همه کریتورها
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </a>
        </div>

        <!-- Horizontal Scroll Container -->
        <div class="creators-scroll-container" style="position:relative;">
            <!-- Scroll Buttons -->
            <button class="creators-scroll-btn creators-scroll-btn--right" id="creatorsScrollRight" aria-label="اسکرول به راست"
                style="position:absolute;right:-16px;top:50%;transform:translateY(-50%);z-index:10;width:44px;height:44px;border-radius:50%;background:#fff;border:2px solid var(--royita-border);display:flex;align-items:center;justify-content:center;cursor:pointer;box-shadow:var(--shadow-md);transition:all 0.2s;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
            </button>
            <button class="creators-scroll-btn creators-scroll-btn--left" id="creatorsScrollLeft" aria-label="اسکرول به چپ"
                style="position:absolute;left:-16px;top:50%;transform:translateY(-50%);z-index:10;width:44px;height:44px;border-radius:50%;background:#fff;border:2px solid var(--royita-border);display:flex;align-items:center;justify-content:center;cursor:pointer;box-shadow:var(--shadow-md);transition:all 0.2s;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
            </button>

            <!-- Creators Row -->
            <div class="creators-row" id="creatorsRow"
                style="display:flex;gap:1.5rem;overflow-x:auto;scroll-behavior:smooth;padding:1rem 0.5rem;scrollbar-width:none;-ms-overflow-style:none;">
                <?php
                if ($creators_query->have_posts()):
                    while ($creators_query->have_posts()):
                        $creators_query->the_post();
                        $post_id       = get_the_ID();
                        $name          = get_the_title();
                        $permalink     = get_permalink();
                        $rating        = royita_get_creator_rating($post_id);
                        $projects      = (int) get_post_meta($post_id, 'creator_total_projects', true);
                        $rate_reel     = (int) get_post_meta($post_id, 'creator_rate_reel', true);
                        $verified      = get_post_meta($post_id, 'creator_verified', true);
                        $badge         = get_post_meta($post_id, 'creator_badge', true) ?: 'none';
                        $badge_data    = royita_get_badge_label($badge);
                        $availability  = get_post_meta($post_id, 'creator_availability', true);
                        $specialty_terms = get_the_terms($post_id, 'creator_specialty');
                        $spec_name     = (!empty($specialty_terms) && !is_wp_error($specialty_terms)) ? $specialty_terms[0]->name : '';
                ?>
                <article class="creator-card" style="flex-shrink:0;">
                    <div class="creator-card__avatar-wrap">
                        <?php echo royita_get_creator_avatar($post_id, 'royita-avatar'); ?>
                        <?php if ($verified): ?>
                        <span class="creator-card__verified" title="تأیید شده">
                            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                        </span>
                        <?php endif; ?>
                    </div>

                    <?php if ($availability === 'available'): ?>
                    <span class="badge badge-success mb-2" style="font-size:10px">
                        <span style="width:6px;height:6px;background:var(--royita-success);border-radius:50%;display:inline-block;"></span>
                        آزاد برای کار
                    </span>
                    <?php endif; ?>

                    <?php if ($badge !== 'none' && !empty($badge_data['label'])): ?>
                    <span class="badge <?php echo esc_attr($badge_data['class']); ?> mb-2">
                        <?php echo esc_html($badge_data['icon'] . ' ' . $badge_data['label']); ?>
                    </span>
                    <?php endif; ?>

                    <h3 class="creator-card__name">
                        <a href="<?php echo esc_url($permalink); ?>"><?php echo esc_html($name); ?></a>
                    </h3>

                    <?php if ($spec_name): ?>
                    <p class="creator-card__specialty"><?php echo esc_html($spec_name); ?></p>
                    <?php endif; ?>

                    <!-- Rating Stars -->
                    <div style="margin-bottom:0.75rem;">
                        <?php echo royita_rating_stars($rating, true); ?>
                    </div>

                    <div class="creator-card__stats">
                        <div class="creator-card__stat">
                            <div class="creator-card__stat-value"><?php echo esc_html($projects); ?></div>
                            <div class="creator-card__stat-label">پروژه</div>
                        </div>
                        <div class="creator-card__stat" style="border-right:1px solid var(--royita-border);padding-right:1rem;">
                            <div class="creator-card__stat-value"><?php echo number_format($rating, 1); ?></div>
                            <div class="creator-card__stat-label">امتیاز</div>
                        </div>
                    </div>

                    <?php if ($rate_reel > 0): ?>
                    <p class="creator-card__price">از <strong><?php echo esc_html(royita_format_price($rate_reel)); ?></strong></p>
                    <?php endif; ?>

                    <a href="<?php echo esc_url($permalink); ?>" class="btn btn-primary btn-sm w-full">
                        مشاهده پروفایل
                    </a>
                </article>
                <?php
                    endwhile;
                    wp_reset_postdata();
                else:
                ?>
                <div style="text-align:center;width:100%;padding:4rem 2rem;">
                    <p style="color:var(--royita-gray);">کریتوری یافت نشد.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</section>

<style>
.creators-row::-webkit-scrollbar { display: none; }
.creators-scroll-btn:hover { border-color: var(--royita-primary); color: var(--royita-primary); }
</style>

<script>
(function() {
    const row   = document.getElementById('creatorsRow');
    const btnR  = document.getElementById('creatorsScrollRight');
    const btnL  = document.getElementById('creatorsScrollLeft');
    if (!row) return;

    const scroll = (dir) => {
        row.scrollBy({ left: dir === 'right' ? -280 : 280, behavior: 'smooth' });
    };

    btnR && btnR.addEventListener('click', () => scroll('right'));
    btnL && btnL.addEventListener('click', () => scroll('left'));
})();
</script>
