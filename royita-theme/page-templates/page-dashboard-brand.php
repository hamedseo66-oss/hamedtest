<?php
/**
 * Template Name: داشبورد برند
 *
 * @package Royita
 */

// Access control
royita_require_login();
if (!royita_is_brand() && !current_user_can('manage_options')) {
    wp_redirect(home_url('/'));
    exit;
}

get_header();

$user_id = get_current_user_id();
$user    = wp_get_current_user();

// Stats
$active_campaigns = new WP_Query([
    'post_type'      => 'royita_campaign',
    'author'         => $user_id,
    'posts_per_page' => -1,
    'fields'         => 'ids',
    'meta_query'     => [['key' => 'campaign_status', 'value' => 'active']],
]);

$all_campaigns_ids = get_posts([
    'post_type'      => 'royita_campaign',
    'author'         => $user_id,
    'posts_per_page' => -1,
    'fields'         => 'ids',
]);

$total_proposals = 0;
foreach ($all_campaigns_ids as $cid) {
    $total_proposals += (int) get_post_meta($cid, 'proposals_count', true);
}

$active_projects = get_posts([
    'post_type'      => 'royita_project',
    'posts_per_page' => -1,
    'fields'         => 'ids',
    'meta_query'     => [
        ['key' => 'project_brand', 'value' => $user_id],
        ['key' => 'project_status', 'value' => ['active','in_review','revision_requested'], 'compare' => 'IN'],
    ],
]);

$completed_projects = get_posts([
    'post_type'      => 'royita_project',
    'posts_per_page' => -1,
    'fields'         => 'ids',
    'meta_query'     => [
        ['key' => 'project_brand', 'value' => $user_id],
        ['key' => 'project_status', 'value' => 'completed'],
    ],
]);

$total_spent = 0;
foreach ($completed_projects as $pid) {
    $total_spent += (int) get_post_meta($pid, 'project_price', true);
}

// Recent campaigns
$recent_campaigns = new WP_Query([
    'post_type'      => 'royita_campaign',
    'author'         => $user_id,
    'posts_per_page' => 5,
    'orderby'        => 'date',
    'order'          => 'DESC',
]);

// Recent proposals
$recent_proposals = get_posts([
    'post_type'      => 'royita_proposal',
    'posts_per_page' => 5,
    'meta_query'     => [
        [
            'key'     => 'proposal_campaign',
            'value'   => $all_campaigns_ids,
            'compare' => 'IN',
        ],
    ],
    'meta_key'  => 'proposal_status',
    'meta_value'=> 'pending',
    'orderby'   => 'date',
    'order'     => 'DESC',
]);
?>

<div class="dashboard-layout" style="margin-top:80px;">

    <!-- Sidebar -->
    <aside class="dashboard-sidebar" role="navigation" aria-label="منوی داشبورد">
        <div class="dashboard-sidebar__user">
            <?php echo get_avatar($user_id, 48, '', '', ['class' => 'dashboard-sidebar__avatar']); ?>
            <div>
                <div class="dashboard-sidebar__name"><?php echo esc_html($user->display_name); ?></div>
                <div class="dashboard-sidebar__role">
                    <span class="badge badge-primary" style="font-size:10px">برند</span>
                </div>
            </div>
        </div>

        <nav class="dashboard-sidebar__nav">
            <div class="dashboard-sidebar__section-label">اصلی</div>

            <a href="#overview" class="dashboard-sidebar__nav-item active" data-section="overview">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                نمای کلی
            </a>

            <a href="#campaigns" class="dashboard-sidebar__nav-item" data-section="campaigns">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                کمپین‌هایم
                <?php if ($active_campaigns->found_posts > 0): ?>
                <span class="dashboard-sidebar__nav-badge"><?php echo esc_html($active_campaigns->found_posts); ?></span>
                <?php endif; ?>
            </a>

            <a href="#proposals" class="dashboard-sidebar__nav-item" data-section="proposals">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                پیشنهادها
                <?php if (count($recent_proposals) > 0): ?>
                <span class="dashboard-sidebar__nav-badge" data-key="proposals"><?php echo esc_html(count($recent_proposals)); ?></span>
                <?php endif; ?>
            </a>

            <a href="#projects" class="dashboard-sidebar__nav-item" data-section="projects">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                پروژه‌ها
                <?php if (count($active_projects) > 0): ?>
                <span class="dashboard-sidebar__nav-badge"><?php echo esc_html(count($active_projects)); ?></span>
                <?php endif; ?>
            </a>

            <div class="dashboard-sidebar__section-label">تنظیمات</div>

            <a href="#profile" class="dashboard-sidebar__nav-item" data-section="profile">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                پروفایل برند
            </a>

            <a href="<?php echo esc_url(wp_logout_url(home_url('/'))); ?>" class="dashboard-sidebar__nav-item" style="color:rgba(255,100,100,0.7);">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                خروج
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="dashboard-content">

        <!-- Overview Section -->
        <section id="overview" class="dashboard-section active">
            <div class="dashboard-content__header">
                <h1 class="dashboard-content__title">خوش آمدید، <?php echo esc_html($user->display_name); ?>! 👋</h1>
                <a href="<?php echo esc_url(admin_url('post-new.php?post_type=royita_campaign')); ?>" class="btn btn-cta">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    ایجاد کمپین جدید
                </a>
            </div>

            <!-- Stats -->
            <div class="dashboard-stats-grid">
                <div class="dashboard-stat-card">
                    <div class="dashboard-stat-card__icon blue">📢</div>
                    <div>
                        <div class="dashboard-stat-card__value"><?php echo esc_html($active_campaigns->found_posts); ?></div>
                        <div class="dashboard-stat-card__label">کمپین فعال</div>
                    </div>
                </div>
                <div class="dashboard-stat-card">
                    <div class="dashboard-stat-card__icon orange">📬</div>
                    <div>
                        <div class="dashboard-stat-card__value"><?php echo esc_html($total_proposals); ?></div>
                        <div class="dashboard-stat-card__label">پیشنهاد دریافتی</div>
                    </div>
                </div>
                <div class="dashboard-stat-card">
                    <div class="dashboard-stat-card__icon green">⚙️</div>
                    <div>
                        <div class="dashboard-stat-card__value"><?php echo esc_html(count($active_projects)); ?></div>
                        <div class="dashboard-stat-card__label">پروژه در حال انجام</div>
                    </div>
                </div>
                <div class="dashboard-stat-card">
                    <div class="dashboard-stat-card__icon purple">💰</div>
                    <div>
                        <div class="dashboard-stat-card__value" style="font-size:1rem;"><?php echo esc_html(royita_format_price($total_spent)); ?></div>
                        <div class="dashboard-stat-card__label">کل هزینه</div>
                    </div>
                </div>
            </div>

            <!-- Recent Campaigns -->
            <div class="dashboard-widget">
                <div class="dashboard-widget__header">
                    <h2 class="dashboard-widget__title">کمپین‌های اخیر</h2>
                    <a href="#campaigns" class="btn btn-ghost btn-sm">مشاهده همه</a>
                </div>
                <div class="dashboard-widget__body" style="padding:0;">
                    <table class="dashboard-table">
                        <thead>
                            <tr>
                                <th>عنوان کمپین</th>
                                <th>وضعیت</th>
                                <th>پیشنهادها</th>
                                <th>بودجه</th>
                                <th>مهلت</th>
                                <th>عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($recent_campaigns->have_posts()):
                                while ($recent_campaigns->have_posts()):
                                    $recent_campaigns->the_post();
                                    $cid         = get_the_ID();
                                    $status      = get_post_meta($cid, 'campaign_status', true);
                                    $status_data = royita_get_campaign_status_label($status);
                                    $proposals   = (int) get_post_meta($cid, 'proposals_count', true);
                                    $budget_min  = (int) get_post_meta($cid, 'campaign_budget_min', true);
                                    $budget_max  = (int) get_post_meta($cid, 'campaign_budget_max', true);
                                    $deadline    = get_post_meta($cid, 'campaign_deadline', true);
                            ?>
                            <tr>
                                <td>
                                    <strong><?php echo esc_html(get_the_title()); ?></strong>
                                </td>
                                <td>
                                    <span class="badge <?php echo esc_attr($status_data['class']); ?>">
                                        <?php echo esc_html($status_data['label']); ?>
                                    </span>
                                </td>
                                <td><?php echo esc_html($proposals); ?></td>
                                <td style="font-size:0.8rem;">
                                    <?php
                                    if ($budget_min && $budget_max) {
                                        echo esc_html(royita_format_price($budget_min)) . ' – ' . esc_html(royita_format_price($budget_max));
                                    } elseif ($budget_min) {
                                        echo 'از ' . esc_html(royita_format_price($budget_min));
                                    }
                                    ?>
                                </td>
                                <td style="font-size:0.8rem;"><?php echo $deadline ? esc_html($deadline) : '—'; ?></td>
                                <td>
                                    <a href="<?php echo esc_url(get_permalink()); ?>" class="btn btn-sm btn-outline">مشاهده</a>
                                </td>
                            </tr>
                            <?php
                                endwhile;
                                wp_reset_postdata();
                            else:
                            ?>
                            <tr>
                                <td colspan="6" style="text-align:center;padding:2rem;color:var(--royita-gray);">
                                    کمپینی وجود ندارد. <a href="<?php echo esc_url(admin_url('post-new.php?post_type=royita_campaign')); ?>">اولین کمپین را بسازید</a>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recent Proposals -->
            <?php if (!empty($recent_proposals)): ?>
            <div class="dashboard-widget">
                <div class="dashboard-widget__header">
                    <h2 class="dashboard-widget__title">پیشنهادهای اخیر</h2>
                </div>
                <div class="dashboard-widget__body">
                    <?php foreach ($recent_proposals as $proposal):
                        $creator_id   = (int) get_post_meta($proposal->ID, 'proposal_creator', true);
                        $price        = (int) get_post_meta($proposal->ID, 'proposal_price', true);
                        $days         = (int) get_post_meta($proposal->ID, 'proposal_delivery_days', true);
                        $cover        = get_post_meta($proposal->ID, 'proposal_cover_letter', true);
                        $creator_name = get_the_title($creator_id);
                        $status_d     = royita_get_proposal_status_label('pending');
                    ?>
                    <div class="proposal-item">
                        <?php echo royita_get_creator_avatar($creator_id, 'royita-avatar'); ?>
                        <div class="proposal-item__info">
                            <div class="proposal-item__name"><?php echo esc_html($creator_name); ?></div>
                            <div class="proposal-item__meta">
                                <span>💰 <?php echo esc_html(royita_format_price($price)); ?></span>
                                <span>📅 <?php echo esc_html($days); ?> روز</span>
                                <span class="badge badge-warning">در انتظار بررسی</span>
                            </div>
                            <p class="proposal-item__cover"><?php echo esc_html(royita_truncate_text($cover, 120)); ?></p>
                        </div>
                        <div class="proposal-item__actions">
                            <button class="btn btn-sm btn-primary" 
                                    data-action="update-status"
                                    data-project-id="<?php echo esc_attr($proposal->ID); ?>"
                                    data-status="accepted">
                                قبول
                            </button>
                            <button class="btn btn-sm btn-outline" style="color:var(--royita-danger);border-color:var(--royita-danger);"
                                    data-action="update-status"
                                    data-project-id="<?php echo esc_attr($proposal->ID); ?>"
                                    data-status="rejected"
                                    data-confirm="آیا از رد این پیشنهاد اطمینان دارید؟">
                                رد
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </section>

    </main>
</div>

<?php get_footer(); ?>
