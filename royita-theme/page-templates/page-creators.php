<?php
/**
 * Template Name: صفحه کریتورها
 *
 * @package Royita
 */

get_header();

$current_specialty    = sanitize_key($_GET['specialty'] ?? '');
$current_industry     = sanitize_key($_GET['industry'] ?? '');
$current_availability = sanitize_key($_GET['availability'] ?? '');
$min_rate             = (int) ($_GET['min_rate'] ?? 0);
$max_rate             = (int) ($_GET['max_rate'] ?? 0);
$min_rating           = (float) ($_GET['min_rating'] ?? 0);
$paged                = max(1, (int) ($_GET['paged'] ?? 1));

// Build query
$args = [
    'post_type'      => 'royita_creator',
    'post_status'    => 'publish',
    'posts_per_page' => 9,
    'paged'          => $paged,
    'meta_key'       => 'creator_rating',
    'orderby'        => 'meta_value_num',
    'order'          => 'DESC',
    'meta_query'     => [],
];

if ($current_availability) {
    $args['meta_query'][] = ['key' => 'creator_availability', 'value' => $current_availability, 'compare' => '='];
}
if ($min_rate > 0) {
    $args['meta_query'][] = ['key' => 'creator_rate_reel', 'value' => $min_rate, 'compare' => '>=', 'type' => 'NUMERIC'];
}
if ($max_rate > 0) {
    $args['meta_query'][] = ['key' => 'creator_rate_reel', 'value' => $max_rate, 'compare' => '<=', 'type' => 'NUMERIC'];
}
if ($min_rating > 0) {
    $args['meta_query'][] = ['key' => 'creator_rating', 'value' => $min_rating, 'compare' => '>=', 'type' => 'NUMERIC'];
}

$tax_query = [];
if ($current_specialty) {
    $tax_query[] = ['taxonomy' => 'creator_specialty', 'field' => 'slug', 'terms' => $current_specialty];
}
if ($current_industry) {
    $tax_query[] = ['taxonomy' => 'creator_industry', 'field' => 'slug', 'terms' => $current_industry];
}
if (!empty($tax_query)) {
    $tax_query['relation'] = 'AND';
    $args['tax_query'] = $tax_query;
}

$query = new WP_Query($args);

$specialties = get_terms(['taxonomy' => 'creator_specialty', 'hide_empty' => true]);
$industries  = get_terms(['taxonomy' => 'creator_industry',  'hide_empty' => true]);
?>

<!-- Page Hero -->
<div style="background:linear-gradient(135deg,#0f4c8a 0%,var(--royita-primary) 100%);padding:140px 0 4rem;">
    <div class="container">
        <h1 style="font-size:2.5rem;font-weight:800;color:#fff;margin-bottom:0.5rem;">کریتورهای حرفه‌ای</h1>
        <p style="color:rgba(255,255,255,0.8);font-size:1.125rem;">
            از میان <strong><?php echo esc_html($query->found_posts); ?></strong> کریتور حرفه‌ای بهترین را انتخاب کنید
        </p>
    </div>
</div>

<!-- Main Content -->
<div style="padding:2rem 0 5rem;">
    <div class="container">
        <div class="page-layout">

            <!-- Filter Sidebar -->
            <aside class="filter-sidebar" aria-label="فیلترهای کریتور">
                <h2 class="filter-sidebar__title">فیلترها</h2>

                <form id="creatorFilterForm" method="GET">

                    <!-- Specialty -->
                    <?php if (!empty($specialties) && !is_wp_error($specialties)): ?>
                    <div class="filter-group">
                        <div class="filter-group__label">تخصص</div>
                        <select name="specialty" id="filterSpecialty" class="form-select">
                            <option value="">همه تخصص‌ها</option>
                            <?php foreach ($specialties as $spec): ?>
                            <option value="<?php echo esc_attr($spec->slug); ?>" <?php selected($current_specialty, $spec->slug); ?>>
                                <?php echo esc_html($spec->name); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php endif; ?>

                    <!-- Industry -->
                    <?php if (!empty($industries) && !is_wp_error($industries)): ?>
                    <div class="filter-group">
                        <div class="filter-group__label">صنعت</div>
                        <select name="industry" id="filterIndustry" class="form-select">
                            <option value="">همه صنایع</option>
                            <?php foreach ($industries as $ind): ?>
                            <option value="<?php echo esc_attr($ind->slug); ?>" <?php selected($current_industry, $ind->slug); ?>>
                                <?php echo esc_html($ind->name); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php endif; ?>

                    <!-- Availability -->
                    <div class="filter-group">
                        <div class="filter-group__label">وضعیت دسترسی</div>
                        <select name="availability" id="filterAvailability" class="form-select">
                            <option value="">همه</option>
                            <option value="available" <?php selected($current_availability, 'available'); ?>>آزاد برای کار</option>
                            <option value="busy"      <?php selected($current_availability, 'busy'); ?>>مشغول</option>
                            <option value="vacation"  <?php selected($current_availability, 'vacation'); ?>>تعطیل</option>
                        </select>
                    </div>

                    <!-- Price Range -->
                    <div class="filter-group">
                        <div class="filter-group__label">نرخ ریلز (تومان)</div>
                        <div class="form-group">
                            <label class="form-label">حداقل</label>
                            <input type="number" name="min_rate" value="<?php echo esc_attr($min_rate ?: ''); ?>"
                                   class="form-control" placeholder="مثال: 500000">
                        </div>
                        <div class="form-group">
                            <label class="form-label">حداکثر</label>
                            <input type="number" name="max_rate" value="<?php echo esc_attr($max_rate ?: ''); ?>"
                                   class="form-control" placeholder="مثال: 5000000">
                        </div>
                    </div>

                    <!-- Min Rating -->
                    <div class="filter-group">
                        <div class="filter-group__label">حداقل امتیاز</div>
                        <select name="min_rating" class="form-select">
                            <option value="0" <?php selected($min_rating, 0); ?>>همه</option>
                            <option value="3" <?php selected($min_rating, 3); ?>>★★★ به بالا</option>
                            <option value="4" <?php selected($min_rating, 4); ?>>★★★★ به بالا</option>
                            <option value="4.5" <?php selected($min_rating, 4.5); ?>>★★★★★ (۴.۵+)</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">اعمال فیلتر</button>
                    <a href="<?php echo esc_url(get_permalink()); ?>" class="btn btn-ghost btn-block" style="margin-top:0.5rem;">پاک کردن</a>
                </form>
            </aside>

            <!-- Creators Grid -->
            <div class="creators-content">
                <div class="filter-sort-bar">
                    <p class="results-count mb-0">
                        نمایش <strong><?php echo esc_html($query->post_count); ?></strong> از <strong><?php echo esc_html($query->found_posts); ?></strong> کریتور
                    </p>
                    <div style="display:flex;align-items:center;gap:0.75rem;">
                        <label for="creatorsSort" class="text-sm text-gray">مرتب‌سازی:</label>
                        <select id="creatorsSort" class="form-select" style="width:auto;">
                            <option value="rating">بهترین امتیاز</option>
                            <option value="projects">بیشترین پروژه</option>
                            <option value="newest">جدیدترین</option>
                        </select>
                    </div>
                </div>

                <div class="grid-3" id="creatorsGrid" style="margin-bottom:2rem;">
                    <?php
                    if ($query->have_posts()):
                        while ($query->have_posts()):
                            $query->the_post();
                            $post_id      = get_the_ID();
                            $permalink    = get_permalink();
                            $name         = get_the_title();
                            $rating       = royita_get_creator_rating($post_id);
                            $projects     = (int) get_post_meta($post_id, 'creator_total_projects', true);
                            $rate_reel    = (int) get_post_meta($post_id, 'creator_rate_reel', true);
                            $verified     = get_post_meta($post_id, 'creator_verified', true);
                            $badge        = get_post_meta($post_id, 'creator_badge', true) ?: 'none';
                            $badge_data   = royita_get_badge_label($badge);
                            $availability = get_post_meta($post_id, 'creator_availability', true);
                            $spec_terms   = get_the_terms($post_id, 'creator_specialty');
                            $spec_name    = (!empty($spec_terms) && !is_wp_error($spec_terms)) ? $spec_terms[0]->name : '';
                    ?>
                    <article class="creator-card">
                        <div class="creator-card__avatar-wrap">
                            <?php echo royita_get_creator_avatar($post_id, 'royita-avatar'); ?>
                            <?php if ($verified): ?>
                            <span class="creator-card__verified" title="تأیید شده">
                                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                            </span>
                            <?php endif; ?>
                        </div>

                        <div style="display:flex;gap:0.5rem;flex-wrap:wrap;justify-content:center;margin-bottom:0.5rem;">
                            <?php if ($availability === 'available'): ?>
                            <span class="badge badge-success" style="font-size:10px">آزاد</span>
                            <?php elseif ($availability === 'busy'): ?>
                            <span class="badge badge-warning" style="font-size:10px">مشغول</span>
                            <?php endif; ?>

                            <?php if ($badge !== 'none' && !empty($badge_data['label'])): ?>
                            <span class="badge <?php echo esc_attr($badge_data['class']); ?>" style="font-size:10px">
                                <?php echo esc_html($badge_data['icon'] . ' ' . $badge_data['label']); ?>
                            </span>
                            <?php endif; ?>
                        </div>

                        <h3 class="creator-card__name">
                            <a href="<?php echo esc_url($permalink); ?>"><?php echo esc_html($name); ?></a>
                        </h3>

                        <?php if ($spec_name): ?>
                        <p class="creator-card__specialty"><?php echo esc_html($spec_name); ?></p>
                        <?php endif; ?>

                        <div style="margin-bottom:0.75rem;"><?php echo royita_rating_stars($rating, true); ?></div>

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

                        <div style="display:flex;gap:0.5rem;width:100%;">
                            <a href="<?php echo esc_url($permalink); ?>" class="btn btn-primary btn-sm" style="flex:1;">مشاهده پروفایل</a>
                            <button class="btn-favorite" data-post-id="<?php echo esc_attr($post_id); ?>" data-type="creator" aria-label="افزودن به علاقه‌مندی‌ها">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                            </button>
                        </div>
                    </article>
                    <?php
                        endwhile;
                        wp_reset_postdata();
                    else:
                    ?>
                    <div class="no-results" style="grid-column:1/-1;">
                        <div class="no-results__icon">🔍</div>
                        <h3>کریتوری یافت نشد</h3>
                        <p>فیلترهای خود را تغییر دهید.</p>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Load More -->
                <?php if ($query->max_num_pages > 1): ?>
                <div style="text-align:center;">
                    <button id="loadMoreCreators" class="btn btn-outline btn-lg">
                        <span class="btn-text">بارگذاری کریتورهای بیشتر</span>
                    </button>
                </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>

<?php get_footer(); ?>
