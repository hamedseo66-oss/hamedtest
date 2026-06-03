<?php
/**
 * Template Name: داشبورد کریتور
 *
 * @package Royita
 */

// Access control
royita_require_login();
if (!royita_is_creator() && !current_user_can('manage_options')) {
    wp_redirect(home_url('/'));
    exit;
}

get_header();

$user_id = get_current_user_id();
$user    = wp_get_current_user();

// Get creator post
$creator_query = new WP_Query([
    'post_type'      => 'royita_creator',
    'author'         => $user_id,
    'posts_per_page' => 1,
]);
$creator_id = $creator_query->have_posts() ? $creator_query->posts[0]->ID : 0;
wp_reset_postdata();

// Stats
$active_proposals = get_posts([
    'post_type'      => 'royita_proposal',
    'author'         => $user_id,
    'posts_per_page' => -1,
    'fields'         => 'ids',
    'meta_query'     => [['key' => 'proposal_status', 'value' => 'pending']],
]);

$active_projects = get_posts([
    'post_type'      => 'royita_project',
    'posts_per_page' => -1,
    'fields'         => 'ids',
    'meta_query'     => [
        ['key' => 'project_creator', 'value' => $creator_id],
        ['key' => 'project_status', 'value' => ['active','in_review','revision_requested'], 'compare' => 'IN'],
    ],
]);

$completed_projects = get_posts([
    'post_type'      => 'royita_project',
    'posts_per_page' => -1,
    'fields'         => 'ids',
    'meta_query'     => [
        ['key' => 'project_creator', 'value' => $creator_id],
        ['key' => 'project_status', 'value' => 'completed'],
    ],
]);

$total_earned = 0;
foreach ($completed_projects as $pid) {
    $total_earned += (int) get_post_meta($pid, 'project_price', true);
}

$avg_rating = royita_get_creator_rating($creator_id);

// Available campaigns for creator's specialties
$creator_specialties = $creator_id ? get_the_terms($creator_id, 'creator_specialty') : [];
$specialty_slugs     = (!empty($creator_specialties) && !is_wp_error($creator_specialties))
    ? wp_list_pluck($creator_specialties, 'slug')
    : [];

$available_campaigns_args = [
    'post_type'      => 'royita_campaign',
    'post_status'    => 'publish',
    'posts_per_page' => 6,
    'meta_query'     => [['key' => 'campaign_status', 'value' => 'active']],
    'orderby'        => 'date',
    'order'          => 'DESC',
];

if (!empty($specialty_slugs)) {
    $available_campaigns_args['tax_query'] = [
        ['taxonomy' => 'campaign_category', 'field' => 'slug', 'terms' => $specialty_slugs, 'operator' => 'IN'],
    ];
}

$available_campaigns = new WP_Query($available_campaigns_args);

// My proposals
$my_proposals = get_posts([
    'post_type'      => 'royita_proposal',
    'author'         => $user_id,
    'posts_per_page' => 5,
    'orderby'        => 'date',
    'order'          => 'DESC',
]);
?>

<div class="dashboard-layout" style="margin-top:80px;">

    <!-- Sidebar -->
    <aside class="dashboard-sidebar" role="navigation" aria-label="منوی داشبورد کریتور">
        <div class="dashboard-sidebar__user">
            <?php echo get_avatar($user_id, 48, '', '', ['class' => 'dashboard-sidebar__avatar']); ?>
            <div>
                <div class="dashboard-sidebar__name"><?php echo esc_html($user->display_name); ?></div>
                <div class="dashboard-sidebar__role">
                    <?php
                    if ($creator_id) {
                        $badge = get_post_meta($creator_id, 'creator_badge', true);
                        $badge_data = royita_get_badge_label($badge ?: 'none');
                        if (!empty($badge_data['label'])) {
                            echo '<span class="badge ' . esc_attr($badge_data['class']) . '" style="font-size:10px">' . esc_html($badge_data['icon'] . ' ' . $badge_data['label']) . '</span>';
                        } else {
                            echo '<span class="badge badge-cta" style="font-size:10px">کریتور</span>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>

        <nav class="dashboard-sidebar__nav">
            <div class="dashboard-sidebar__section-label">اصلی</div>

            <a href="#overview" class="dashboard-sidebar__nav-item active">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                نمای کلی
            </a>

            <a href="#campaigns" class="dashboard-sidebar__nav-item">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                کمپین‌های مناسب
            </a>

            <a href="#proposals" class="dashboard-sidebar__nav-item">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                پیشنهادهایم
                <?php if (!empty($active_proposals)): ?>
                <span class="dashboard-sidebar__nav-badge"><?php echo esc_html(count($active_proposals)); ?></span>
                <?php endif; ?>
            </a>

            <a href="#projects" class="dashboard-sidebar__nav-item">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                پروژه‌هایم
                <?php if (!empty($active_projects)): ?>
                <span class="dashboard-sidebar__nav-badge"><?php echo esc_html(count($active_projects)); ?></span>
                <?php endif; ?>
            </a>

            <a href="#portfolio" class="dashboard-sidebar__nav-item">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                پورتفولیو
            </a>

            <a href="#earnings" class="dashboard-sidebar__nav-item">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                درآمدها
            </a>

            <div class="dashboard-sidebar__section-label">تنظیمات</div>

            <a href="#profile" class="dashboard-sidebar__nav-item">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                پروفایل من
            </a>

            <a href="<?php echo esc_url(wp_logout_url(home_url('/'))); ?>" class="dashboard-sidebar__nav-item" style="color:rgba(255,100,100,0.7);">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                خروج
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="dashboard-content">

        <!-- Overview -->
        <section id="overview" class="dashboard-section active">
            <div class="dashboard-content__header">
                <h1 class="dashboard-content__title">داشبورد کریتور 🎬</h1>
                <a href="<?php echo esc_url(home_url('/campaigns/')); ?>" class="btn btn-cta">
                    پیدا کردن کمپین
                </a>
            </div>

            <!-- Stats -->
            <div class="dashboard-stats-grid">
                <div class="dashboard-stat-card">
                    <div class="dashboard-stat-card__icon orange">📬</div>
                    <div>
                        <div class="dashboard-stat-card__value"><?php echo esc_html(count($active_proposals)); ?></div>
                        <div class="dashboard-stat-card__label">پیشنهاد در انتظار</div>
                    </div>
                </div>
                <div class="dashboard-stat-card">
                    <div class="dashboard-stat-card__icon blue">⚙️</div>
                    <div>
                        <div class="dashboard-stat-card__value"><?php echo esc_html(count($active_projects)); ?></div>
                        <div class="dashboard-stat-card__label">پروژه فعال</div>
                    </div>
                </div>
                <div class="dashboard-stat-card">
                    <div class="dashboard-stat-card__icon green">💰</div>
                    <div>
                        <div class="dashboard-stat-card__value" style="font-size:0.9rem;"><?php echo esc_html(royita_format_price($total_earned)); ?></div>
                        <div class="dashboard-stat-card__label">کل درآمد</div>
                    </div>
                </div>
                <div class="dashboard-stat-card">
                    <div class="dashboard-stat-card__icon purple">⭐</div>
                    <div>
                        <div class="dashboard-stat-card__value"><?php echo number_format($avg_rating, 1); ?></div>
                        <div class="dashboard-stat-card__label">میانگین امتیاز</div>
                    </div>
                </div>
            </div>

            <!-- Availability Toggle -->
            <?php if ($creator_id): ?>
            <div class="dashboard-widget" style="margin-bottom:1.5rem;">
                <div class="dashboard-widget__header">
                    <h2 class="dashboard-widget__title">وضعیت دسترسی</h2>
                </div>
                <div class="dashboard-widget__body">
                    <?php
                    $availability = get_post_meta($creator_id, 'creator_availability', true);
                    $availability_labels = ['available' => 'آزاد برای کار', 'busy' => 'مشغول', 'vacation' => 'تعطیل'];
                    $availability_colors = ['available' => 'badge-success', 'busy' => 'badge-warning', 'vacation' => 'badge-gray'];
                    ?>
                    <div style="display:flex;align-items:center;gap:1rem;flex-wrap:wrap;">
                        <span class="badge <?php echo esc_attr($availability_colors[$availability] ?? 'badge-gray'); ?>">
                            <?php echo esc_html($availability_labels[$availability] ?? 'نامشخص'); ?>
                        </span>
                        <div data-inline-select data-project-id="<?php echo esc_attr($creator_id); ?>" style="display:flex;gap:0.5rem;align-items:center;">
                            <select class="form-select" style="width:auto;" name="creator_availability">
                                <option value="available" <?php selected($availability, 'available'); ?>>آزاد برای کار</option>
                                <option value="busy"      <?php selected($availability, 'busy'); ?>>مشغول</option>
                                <option value="vacation"  <?php selected($availability, 'vacation'); ?>>تعطیل</option>
                            </select>
                            <button type="button" class="btn btn-sm btn-primary" data-save style="display:none;">ذخیره</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Available Campaigns -->
            <div class="dashboard-widget">
                <div class="dashboard-widget__header">
                    <h2 class="dashboard-widget__title">کمپین‌های مناسب شما</h2>
                    <a href="<?php echo esc_url(home_url('/campaigns/')); ?>" class="btn btn-ghost btn-sm">مشاهده همه</a>
                </div>
                <div class="dashboard-widget__body" style="padding:1.5rem;">
                    <?php if ($available_campaigns->have_posts()): ?>
                    <div class="grid-2">
                        <?php
                        while ($available_campaigns->have_posts()):
                            $available_campaigns->the_post();
                            echo royita_campaign_card(get_the_ID());
                        endwhile;
                        wp_reset_postdata();
                        ?>
                    </div>
                    <?php else: ?>
                    <div class="empty-state">
                        <span class="empty-state__icon">🔍</span>
                        <h3 class="empty-state__title">کمپینی یافت نشد</h3>
                        <p class="empty-state__text">پروفایل خود را کامل کنید تا کمپین‌های بیشتری ببینید</p>
                        <a href="<?php echo esc_url(home_url('/campaigns/')); ?>" class="btn btn-primary">مشاهده همه کمپین‌ها</a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- My Recent Proposals -->
            <?php if (!empty($my_proposals)): ?>
            <div class="dashboard-widget">
                <div class="dashboard-widget__header">
                    <h2 class="dashboard-widget__title">پیشنهادهای اخیر من</h2>
                </div>
                <div class="dashboard-widget__body" style="padding:0;">
                    <table class="dashboard-table">
                        <thead>
                            <tr>
                                <th>کمپین</th>
                                <th>وضعیت</th>
                                <th>مبلغ</th>
                                <th>تاریخ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($my_proposals as $proposal):
                                $campaign_id    = (int) get_post_meta($proposal->ID, 'proposal_campaign', true);
                                $status         = get_post_meta($proposal->ID, 'proposal_status', true);
                                $price          = (int) get_post_meta($proposal->ID, 'proposal_price', true);
                                $status_data    = royita_get_proposal_status_label($status);
                                $campaign_title = get_the_title($campaign_id);
                            ?>
                            <tr>
                                <td><strong><?php echo esc_html($campaign_title ?: '—'); ?></strong></td>
                                <td><span class="badge <?php echo esc_attr($status_data['class']); ?>"><?php echo esc_html($status_data['label']); ?></span></td>
                                <td><?php echo esc_html(royita_format_price($price)); ?></td>
                                <td style="font-size:0.8rem;"><?php echo esc_html(get_the_date('Y/m/d', $proposal->ID)); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>

        </section>

    </main>
</div>

<?php get_footer(); ?>
