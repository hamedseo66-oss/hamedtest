<?php
/**
 * Template Name: صفحه کمپین‌ها
 *
 * @package Royita
 */

get_header();

$current_category = sanitize_key($_GET['category'] ?? '');
$current_platform = sanitize_key($_GET['platform'] ?? '');
$current_sort     = sanitize_key($_GET['sort'] ?? 'newest');
$budget_min       = (int) ($_GET['budget_min'] ?? 0);
$budget_max       = (int) ($_GET['budget_max'] ?? 0);
$paged            = max(1, (int) ($_GET['paged'] ?? 1));

// Build query args
$args = [
    'post_type'      => 'royita_campaign',
    'post_status'    => 'publish',
    'posts_per_page' => 12,
    'paged'          => $paged,
    'meta_query'     => [
        [
            'key'     => 'campaign_status',
            'value'   => 'active',
            'compare' => '=',
        ],
    ],
];

switch ($current_sort) {
    case 'budget_high':
        $args['meta_key'] = 'campaign_budget_max';
        $args['orderby']  = 'meta_value_num';
        $args['order']    = 'DESC';
        break;
    case 'budget_low':
        $args['meta_key'] = 'campaign_budget_min';
        $args['orderby']  = 'meta_value_num';
        $args['order']    = 'ASC';
        break;
    case 'proposals':
        $args['meta_key'] = 'proposals_count';
        $args['orderby']  = 'meta_value_num';
        $args['order']    = 'DESC';
        break;
    default:
        $args['orderby'] = 'date';
        $args['order']   = 'DESC';
}

$tax_query = [];
if ($current_category) {
    $tax_query[] = ['taxonomy' => 'campaign_category', 'field' => 'slug', 'terms' => $current_category];
}
if ($current_platform) {
    $tax_query[] = ['taxonomy' => 'campaign_platform', 'field' => 'slug', 'terms' => $current_platform];
}
if (!empty($tax_query)) {
    $tax_query['relation'] = 'AND';
    $args['tax_query'] = $tax_query;
}

if ($budget_min > 0) {
    $args['meta_query'][] = ['key' => 'campaign_budget_min', 'value' => $budget_min, 'compare' => '>=', 'type' => 'NUMERIC'];
}
if ($budget_max > 0) {
    $args['meta_query'][] = ['key' => 'campaign_budget_max', 'value' => $budget_max, 'compare' => '<=', 'type' => 'NUMERIC'];
}

$query = new WP_Query($args);

$categories = get_terms(['taxonomy' => 'campaign_category', 'hide_empty' => true]);
$platforms  = get_terms(['taxonomy' => 'campaign_platform', 'hide_empty' => true]);
?>

<!-- Page Hero -->
<div class="campaigns-page__header" style="padding-top:140px;">
    <div class="container">
        <div class="hero-section__badge" style="margin-bottom:1rem;">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            کمپین‌های فعال
        </div>
        <h1 class="campaigns-page__header-title">همه کمپین‌های فعال</h1>
        <p class="campaigns-page__header-subtitle">
            <?php echo esc_html($query->found_posts); ?> کمپین فعال منتظر کریتورهای حرفه‌ای
        </p>

        <!-- Search bar -->
        <div class="search-bar" style="max-width:500px;margin-top:1.5rem;">
            <form method="GET" action="">
                <div style="position:relative;">
                    <input type="search" 
                           name="search" 
                           value="<?php echo esc_attr($_GET['search'] ?? ''); ?>"
                           placeholder="جستجو در کمپین‌ها..."
                           class="search-bar__input">
                    <button type="submit" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:rgba(255,255,255,0.7);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="campaigns-page" style="padding-top:2rem;">
    <div class="container">
        <div class="page-layout">

            <!-- Filter Sidebar -->
            <aside class="filter-sidebar" aria-label="فیلترها">
                <h2 class="filter-sidebar__title">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                    فیلترها
                </h2>

                <form id="campaignFilterForm" method="GET">

                    <!-- Category Filter -->
                    <?php if (!empty($categories) && !is_wp_error($categories)): ?>
                    <div class="filter-group">
                        <div class="filter-group__label">دسته‌بندی</div>
                        <?php foreach ($categories as $cat): ?>
                        <label class="form-checkbox">
                            <input type="radio" name="category" value="<?php echo esc_attr($cat->slug); ?>"
                                   <?php checked($current_category, $cat->slug); ?>>
                            <span><?php echo esc_html($cat->name); ?> <small class="text-gray">(<?php echo esc_html($cat->count); ?>)</small></span>
                        </label>
                        <?php endforeach; ?>
                        <label class="form-checkbox">
                            <input type="radio" name="category" value="" <?php checked($current_category, ''); ?>>
                            <span>همه</span>
                        </label>
                    </div>
                    <?php endif; ?>

                    <!-- Platform Filter -->
                    <?php if (!empty($platforms) && !is_wp_error($platforms)): ?>
                    <div class="filter-group">
                        <div class="filter-group__label">پلتفرم</div>
                        <?php foreach ($platforms as $platform): ?>
                        <label class="form-checkbox">
                            <input type="checkbox" name="platform[]" value="<?php echo esc_attr($platform->slug); ?>"
                                   <?php checked(in_array($platform->slug, (array)($_GET['platform'] ?? []))); ?>>
                            <span><?php echo esc_html($platform->name); ?></span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                    <!-- Budget Filter -->
                    <div class="filter-group">
                        <div class="filter-group__label">بودجه (تومان)</div>
                        <div class="form-group">
                            <label class="form-label">حداقل</label>
                            <input type="number" name="budget_min" value="<?php echo esc_attr($budget_min ?: ''); ?>"
                                   class="form-control" placeholder="مثال: 1000000">
                        </div>
                        <div class="form-group">
                            <label class="form-label">حداکثر</label>
                            <input type="number" name="budget_max" value="<?php echo esc_attr($budget_max ?: ''); ?>"
                                   class="form-control" placeholder="مثال: 10000000">
                        </div>
                    </div>

                    <!-- Sort -->
                    <div class="filter-group">
                        <div class="filter-group__label">مرتب‌سازی</div>
                        <select name="sort" class="form-select">
                            <option value="newest" <?php selected($current_sort, 'newest'); ?>>جدیدترین</option>
                            <option value="budget_high" <?php selected($current_sort, 'budget_high'); ?>>بیشترین بودجه</option>
                            <option value="budget_low" <?php selected($current_sort, 'budget_low'); ?>>کمترین بودجه</option>
                            <option value="proposals" <?php selected($current_sort, 'proposals'); ?>>بیشترین پیشنهاد</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">اعمال فیلتر</button>
                    <a href="<?php echo esc_url(get_permalink()); ?>" class="btn btn-ghost btn-block" style="margin-top:0.5rem;">پاک کردن فیلترها</a>
                </form>
            </aside>

            <!-- Campaigns Grid -->
            <div class="campaigns-content">
                <!-- Results Info -->
                <div class="filter-sort-bar">
                    <p class="results-count mb-0">
                        نمایش <strong><?php echo esc_html($query->post_count); ?></strong> از <strong><?php echo esc_html($query->found_posts); ?></strong> کمپین
                    </p>
                </div>

                <div class="campaigns-grid grid-2" id="campaignsGrid" style="margin-bottom:2rem;">
                    <?php
                    if ($query->have_posts()):
                        while ($query->have_posts()):
                            $query->the_post();
                            echo royita_campaign_card(get_the_ID());
                        endwhile;
                        wp_reset_postdata();
                    else:
                    ?>
                    <div class="no-results" style="grid-column:1/-1;">
                        <div class="no-results__icon">🔍</div>
                        <h3>کمپینی یافت نشد</h3>
                        <p>فیلترهای خود را تغییر دهید یا <a href="<?php echo esc_url(get_permalink()); ?>">همه کمپین‌ها</a> را مشاهده کنید.</p>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Pagination -->
                <?php if ($query->max_num_pages > 1): ?>
                <div class="royita-pagination">
                    <?php
                    echo paginate_links([
                        'total'   => $query->max_num_pages,
                        'current' => $paged,
                        'format'  => '?paged=%#%',
                        'prev_text' => '&rarr;',
                        'next_text' => '&larr;',
                    ]);
                    ?>
                </div>
                <?php endif; ?>

                <!-- Load More via AJAX -->
                <?php if ($query->max_num_pages > 1 && $paged === 1): ?>
                <div style="text-align:center;margin-top:2rem;">
                    <button id="loadMoreCampaigns" 
                            class="btn btn-outline btn-lg"
                            data-page="2"
                            data-max-pages="<?php echo esc_attr($query->max_num_pages); ?>">
                        <span class="btn-text">بارگذاری بیشتر</span>
                    </button>
                </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>

<?php get_footer(); ?>
