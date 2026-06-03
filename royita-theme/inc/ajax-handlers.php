<?php
/**
 * Royita AJAX Handlers
 *
 * @package Royita
 */

defined('ABSPATH') || exit;

// =====================================================
// TOGGLE FAVORITE
// =====================================================
add_action('wp_ajax_royita_toggle_favorite', 'royita_ajax_toggle_favorite');
function royita_ajax_toggle_favorite() {
    check_ajax_referer('royita_nonce', 'nonce');

    if (!is_user_logged_in()) {
        wp_send_json_error(['message' => __('برای این عملیات باید وارد شوید.', 'royita')], 401);
    }

    $post_id = (int) sanitize_text_field($_POST['post_id'] ?? 0);
    $type    = sanitize_key($_POST['type'] ?? 'campaign'); // campaign | creator

    if ($post_id <= 0) {
        wp_send_json_error(['message' => __('شناسه نامعتبر است.', 'royita')]);
    }

    $user_id    = get_current_user_id();
    $meta_key   = 'royita_favorite_' . $type . 's';
    $favorites  = get_user_meta($user_id, $meta_key, true);

    if (!is_array($favorites)) {
        $favorites = [];
    }

    $is_favorite = in_array($post_id, $favorites, true);

    if ($is_favorite) {
        $favorites = array_values(array_diff($favorites, [$post_id]));
        $message = __('از علاقه‌مندی‌ها حذف شد', 'royita');
    } else {
        $favorites[] = $post_id;
        $message = __('به علاقه‌مندی‌ها اضافه شد', 'royita');
    }

    update_user_meta($user_id, $meta_key, $favorites);

    wp_send_json_success([
        'is_favorite' => !$is_favorite,
        'message'     => $message,
        'count'       => count($favorites),
    ]);
}

// =====================================================
// LOAD MORE CAMPAIGNS
// =====================================================
add_action('wp_ajax_royita_load_more_campaigns',        'royita_ajax_load_more_campaigns');
add_action('wp_ajax_nopriv_royita_load_more_campaigns', 'royita_ajax_load_more_campaigns');
function royita_ajax_load_more_campaigns() {
    check_ajax_referer('royita_nonce', 'nonce');

    $page     = (int) sanitize_text_field($_POST['page'] ?? 1);
    $category = sanitize_key($_POST['category'] ?? '');
    $platform = sanitize_key($_POST['platform'] ?? '');
    $sort     = sanitize_key($_POST['sort'] ?? 'newest');
    $budget_min = (int) sanitize_text_field($_POST['budget_min'] ?? 0);
    $budget_max = (int) sanitize_text_field($_POST['budget_max'] ?? 0);

    $args = [
        'post_type'      => 'royita_campaign',
        'post_status'    => 'publish',
        'posts_per_page' => 6,
        'paged'          => $page,
        'meta_query'     => [
            [
                'key'     => 'campaign_status',
                'value'   => 'active',
                'compare' => '=',
            ],
        ],
    ];

    // Sorting
    switch ($sort) {
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

    // Tax filters
    $tax_query = [];
    if (!empty($category)) {
        $tax_query[] = [
            'taxonomy' => 'campaign_category',
            'field'    => 'slug',
            'terms'    => $category,
        ];
    }
    if (!empty($platform)) {
        $tax_query[] = [
            'taxonomy' => 'campaign_platform',
            'field'    => 'slug',
            'terms'    => $platform,
        ];
    }
    if (!empty($tax_query)) {
        $tax_query['relation'] = 'AND';
        $args['tax_query'] = $tax_query;
    }

    // Budget filter
    if ($budget_min > 0) {
        $args['meta_query'][] = [
            'key'     => 'campaign_budget_min',
            'value'   => $budget_min,
            'compare' => '>=',
            'type'    => 'NUMERIC',
        ];
    }
    if ($budget_max > 0) {
        $args['meta_query'][] = [
            'key'     => 'campaign_budget_max',
            'value'   => $budget_max,
            'compare' => '<=',
            'type'    => 'NUMERIC',
        ];
    }

    $query = new WP_Query($args);
    $html  = '';

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $html .= royita_campaign_card(get_the_ID());
        }
        wp_reset_postdata();
    }

    wp_send_json_success([
        'html'      => $html,
        'has_more'  => $page < $query->max_num_pages,
        'total'     => $query->found_posts,
        'next_page' => $page + 1,
    ]);
}

// =====================================================
// LOAD MORE CREATORS
// =====================================================
add_action('wp_ajax_royita_load_more_creators',        'royita_ajax_load_more_creators');
add_action('wp_ajax_nopriv_royita_load_more_creators', 'royita_ajax_load_more_creators');
function royita_ajax_load_more_creators() {
    check_ajax_referer('royita_nonce', 'nonce');

    $page         = (int) sanitize_text_field($_POST['page'] ?? 1);
    $specialty    = sanitize_key($_POST['specialty'] ?? '');
    $industry     = sanitize_key($_POST['industry'] ?? '');
    $availability = sanitize_key($_POST['availability'] ?? '');
    $min_rate     = (int) sanitize_text_field($_POST['min_rate'] ?? 0);
    $max_rate     = (int) sanitize_text_field($_POST['max_rate'] ?? 0);

    $args = [
        'post_type'      => 'royita_creator',
        'post_status'    => 'publish',
        'posts_per_page' => 9,
        'paged'          => $page,
        'meta_key'       => 'creator_rating',
        'orderby'        => 'meta_value_num',
        'order'          => 'DESC',
        'meta_query'     => [],
    ];

    if (!empty($availability)) {
        $args['meta_query'][] = [
            'key'     => 'creator_availability',
            'value'   => $availability,
            'compare' => '=',
        ];
    }

    if ($min_rate > 0) {
        $args['meta_query'][] = [
            'key'     => 'creator_rate_reel',
            'value'   => $min_rate,
            'compare' => '>=',
            'type'    => 'NUMERIC',
        ];
    }
    if ($max_rate > 0) {
        $args['meta_query'][] = [
            'key'     => 'creator_rate_reel',
            'value'   => $max_rate,
            'compare' => '<=',
            'type'    => 'NUMERIC',
        ];
    }

    $tax_query = [];
    if (!empty($specialty)) {
        $tax_query[] = [
            'taxonomy' => 'creator_specialty',
            'field'    => 'slug',
            'terms'    => $specialty,
        ];
    }
    if (!empty($industry)) {
        $tax_query[] = [
            'taxonomy' => 'creator_industry',
            'field'    => 'slug',
            'terms'    => $industry,
        ];
    }
    if (!empty($tax_query)) {
        $tax_query['relation'] = 'AND';
        $args['tax_query'] = $tax_query;
    }

    $query = new WP_Query($args);
    $html  = '';

    if ($query->have_posts()) {
        ob_start();
        while ($query->have_posts()) {
            $query->the_post();
            $post_id       = get_the_ID();
            $name          = get_the_title();
            $permalink     = get_permalink();
            $rating        = royita_get_creator_rating($post_id);
            $projects      = (int) get_post_meta($post_id, 'creator_total_projects', true);
            $rate_reel     = (int) get_post_meta($post_id, 'creator_rate_reel', true);
            $verified      = get_post_meta($post_id, 'creator_verified', true);
            $badge         = get_post_meta($post_id, 'creator_badge', true) ?: 'none';
            $badge_data    = royita_get_badge_label($badge);
            $specialty_terms = get_the_terms($post_id, 'creator_specialty');
            $spec_name     = (!empty($specialty_terms) && !is_wp_error($specialty_terms)) ? $specialty_terms[0]->name : '';

            echo '<article class="creator-card">';
            echo '<div class="creator-card__avatar-wrap">';
            echo royita_get_creator_avatar($post_id, 'royita-avatar');
            if ($verified) {
                echo '<span class="creator-card__verified" title="تأیید شده">✓</span>';
            }
            echo '</div>';

            if ($badge !== 'none' && !empty($badge_data['label'])) {
                echo '<span class="badge ' . esc_attr($badge_data['class']) . ' mb-2">' . esc_html($badge_data['icon'] . ' ' . $badge_data['label']) . '</span>';
            }

            echo '<h3 class="creator-card__name"><a href="' . esc_url($permalink) . '">' . esc_html($name) . '</a></h3>';
            if ($spec_name) {
                echo '<p class="creator-card__specialty">' . esc_html($spec_name) . '</p>';
            }

            echo '<div class="creator-card__stats">';
            echo '<div class="creator-card__stat"><div class="creator-card__stat-value">' . esc_html($projects) . '</div><div class="creator-card__stat-label">پروژه</div></div>';
            echo '<div class="creator-card__stat"><div class="creator-card__stat-value">' . esc_html(number_format($rating, 1)) . '</div><div class="creator-card__stat-label">امتیاز</div></div>';
            echo '</div>';

            echo royita_rating_stars($rating, false);

            if ($rate_reel > 0) {
                echo '<p class="creator-card__price">از <strong>' . esc_html(royita_format_price($rate_reel)) . '</strong></p>';
            }

            echo '<a href="' . esc_url($permalink) . '" class="btn btn-primary btn-sm w-full">مشاهده پروفایل</a>';
            echo '</article>';
        }
        $html = ob_get_clean();
        wp_reset_postdata();
    }

    wp_send_json_success([
        'html'      => $html,
        'has_more'  => $page < $query->max_num_pages,
        'total'     => $query->found_posts,
        'next_page' => $page + 1,
    ]);
}

// =====================================================
// SUBMIT PROPOSAL
// =====================================================
add_action('wp_ajax_royita_submit_proposal', 'royita_ajax_submit_proposal');
function royita_ajax_submit_proposal() {
    check_ajax_referer('royita_nonce', 'nonce');

    if (!is_user_logged_in()) {
        wp_send_json_error(['message' => __('برای ارسال پیشنهاد باید وارد شوید.', 'royita')], 401);
    }

    if (!royita_can_submit_proposals()) {
        wp_send_json_error(['message' => __('شما مجاز به ارسال پیشنهاد نیستید.', 'royita')], 403);
    }

    $campaign_id   = (int) sanitize_text_field($_POST['campaign_id'] ?? 0);
    $price         = (int) sanitize_text_field($_POST['price'] ?? 0);
    $delivery_days = (int) sanitize_text_field($_POST['delivery_days'] ?? 0);
    $revisions     = (int) sanitize_text_field($_POST['revisions'] ?? 2);
    $cover_letter  = sanitize_textarea_field($_POST['cover_letter'] ?? '');

    // Validation
    $errors = [];
    if ($campaign_id <= 0)     $errors[] = __('کمپین نامعتبر است.', 'royita');
    if ($price <= 0)           $errors[] = __('مبلغ پیشنهادی باید بیشتر از صفر باشد.', 'royita');
    if ($delivery_days <= 0)   $errors[] = __('مدت تحویل باید بیشتر از صفر باشد.', 'royita');
    if (empty($cover_letter))  $errors[] = __('نامه پوششی الزامی است.', 'royita');
    if (mb_strlen($cover_letter) < 50) $errors[] = __('نامه پوششی باید حداقل ۵۰ کاراکتر باشد.', 'royita');

    if (!empty($errors)) {
        wp_send_json_error(['message' => implode('<br>', $errors)]);
    }

    // Check if campaign is active
    $campaign_status = get_post_meta($campaign_id, 'campaign_status', true);
    if ($campaign_status !== 'active') {
        wp_send_json_error(['message' => __('این کمپین در حال حاضر فعال نیست.', 'royita')]);
    }

    $user_id = get_current_user_id();

    // Find creator post for this user
    $creator_query = new WP_Query([
        'post_type'      => 'royita_creator',
        'author'         => $user_id,
        'posts_per_page' => 1,
        'fields'         => 'ids',
    ]);
    $creator_id = $creator_query->have_posts() ? $creator_query->posts[0] : 0;
    wp_reset_postdata();

    // Check for duplicate
    $existing = get_posts([
        'post_type'  => 'royita_proposal',
        'author'     => $user_id,
        'meta_query' => [
            [
                'key'     => 'proposal_campaign',
                'value'   => $campaign_id,
                'compare' => '=',
            ],
        ],
        'posts_per_page' => 1,
        'fields'         => 'ids',
    ]);

    if (!empty($existing)) {
        wp_send_json_error(['message' => __('شما قبلاً برای این کمپین پیشنهاد ارسال کرده‌اید.', 'royita')]);
    }

    // Create proposal
    $proposal_id = wp_insert_post([
        'post_type'   => 'royita_proposal',
        'post_status' => 'publish',
        'post_title'  => sprintf('پیشنهاد برای کمپین #%d', $campaign_id),
        'post_author' => $user_id,
    ]);

    if (is_wp_error($proposal_id)) {
        wp_send_json_error(['message' => __('خطا در ذخیره‌سازی پیشنهاد.', 'royita')]);
    }

    // Save meta
    update_post_meta($proposal_id, 'proposal_campaign',     $campaign_id);
    update_post_meta($proposal_id, 'proposal_creator',      $creator_id);
    update_post_meta($proposal_id, 'proposal_status',       'pending');
    update_post_meta($proposal_id, 'proposal_price',        $price);
    update_post_meta($proposal_id, 'proposal_delivery_days',$delivery_days);
    update_post_meta($proposal_id, 'proposal_revisions',    $revisions);
    update_post_meta($proposal_id, 'proposal_cover_letter', $cover_letter);

    // Increment proposal count on campaign
    $count = (int) get_post_meta($campaign_id, 'proposals_count', true);
    update_post_meta($campaign_id, 'proposals_count', $count + 1);

    // Send notification
    royita_notify_new_proposal($proposal_id);

    wp_send_json_success([
        'message'     => __('پیشنهاد شما با موفقیت ارسال شد.', 'royita'),
        'proposal_id' => $proposal_id,
    ]);
}

// =====================================================
// UPDATE PROJECT STATUS
// =====================================================
add_action('wp_ajax_royita_update_project_status', 'royita_ajax_update_project_status');
function royita_ajax_update_project_status() {
    check_ajax_referer('royita_dashboard_nonce', 'nonce');

    if (!is_user_logged_in()) {
        wp_send_json_error(['message' => __('دسترسی غیر مجاز.', 'royita')], 401);
    }

    $project_id = (int) sanitize_text_field($_POST['project_id'] ?? 0);
    $new_status = sanitize_key($_POST['status'] ?? '');

    $allowed_statuses = ['active','in_review','revision_requested','completed','disputed','cancelled'];
    if (!in_array($new_status, $allowed_statuses, true)) {
        wp_send_json_error(['message' => __('وضعیت نامعتبر است.', 'royita')]);
    }

    if (!royita_can_access_project($project_id)) {
        wp_send_json_error(['message' => __('شما مجاز به تغییر وضعیت این پروژه نیستید.', 'royita')], 403);
    }

    update_post_meta($project_id, 'project_status', $new_status);

    // Send notification if completed
    if ($new_status === 'completed') {
        royita_notify_project_completed($project_id);
    }

    $status_data = royita_get_project_status_label($new_status);

    wp_send_json_success([
        'message'      => __('وضعیت پروژه به‌روز شد.', 'royita'),
        'status'       => $new_status,
        'status_label' => $status_data['label'],
        'status_class' => $status_data['class'],
    ]);
}

// =====================================================
// ACCEPT PROPOSAL (برند — تایید کریتور)
// =====================================================
add_action('wp_ajax_royita_accept_proposal', 'royita_ajax_accept_proposal');
function royita_ajax_accept_proposal() {
    check_ajax_referer('royita_dashboard_nonce', 'nonce');

    if (!royita_is_brand()) {
        wp_send_json_error(['message' => __('فقط برندها می‌توانند پیشنهاد تایید کنند.', 'royita')], 403);
    }

    $proposal_id = (int) sanitize_text_field($_POST['proposal_id'] ?? 0);
    if ($proposal_id <= 0) {
        wp_send_json_error(['message' => __('شناسه پیشنهاد نامعتبر است.', 'royita')]);
    }

    $campaign_id = (int) get_post_meta($proposal_id, 'proposal_campaign', true);
    $campaign    = get_post($campaign_id);

    // بررسی ownership کمپین
    if (!$campaign || (int) $campaign->post_author !== get_current_user_id()) {
        wp_send_json_error(['message' => __('شما مجاز به تایید این پیشنهاد نیستید.', 'royita')], 403);
    }

    // بررسی وضعیت فعلی
    $current_status = get_post_meta($proposal_id, 'proposal_status', true);
    if ($current_status !== 'pending') {
        wp_send_json_error(['message' => __('این پیشنهاد قابل تایید نیست.', 'royita')]);
    }

    $creator_id    = (int) get_post_meta($proposal_id, 'proposal_creator', true);
    $price         = (int) get_post_meta($proposal_id, 'proposal_price', true);
    $delivery_days = (int) get_post_meta($proposal_id, 'proposal_delivery_days', true);
    $revisions_max = (int) get_post_meta($proposal_id, 'proposal_revisions', true);
    $creator_post  = get_post($creator_id);
    $creator_user_id = $creator_post ? (int) $creator_post->post_author : 0;

    // ۱. تایید این پیشنهاد
    update_post_meta($proposal_id, 'proposal_status', 'accepted');

    // ۲. رد بقیه پیشنهادهای همین کمپین
    $other_proposals = get_posts([
        'post_type'      => 'royita_proposal',
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'meta_query'     => [
            ['key' => 'proposal_campaign', 'value' => $campaign_id, 'compare' => '='],
            ['key' => 'proposal_status',   'value' => 'pending',    'compare' => '='],
        ],
        'exclude' => [$proposal_id],
    ]);
    foreach ($other_proposals as $other_id) {
        update_post_meta($other_id, 'proposal_status', 'rejected');
    }

    // ۳. ساخت Project
    $start_date = date('Y-m-d');
    $end_date   = date('Y-m-d', strtotime("+{$delivery_days} days"));

    $project_id = wp_insert_post([
        'post_type'   => 'royita_project',
        'post_status' => 'publish',
        'post_title'  => get_the_title($campaign_id),
        'post_author' => get_current_user_id(),
    ]);

    if (is_wp_error($project_id)) {
        wp_send_json_error(['message' => __('خطا در ایجاد پروژه.', 'royita')]);
    }

    update_post_meta($project_id, 'project_campaign',      $campaign_id);
    update_post_meta($project_id, 'project_proposal',      $proposal_id);
    update_post_meta($project_id, 'project_brand_id',      get_current_user_id());
    update_post_meta($project_id, 'project_creator_id',    $creator_user_id);
    update_post_meta($project_id, 'project_amount',        $price);
    update_post_meta($project_id, 'project_status',        'active');
    update_post_meta($project_id, 'project_start_date',    $start_date);
    update_post_meta($project_id, 'project_end_date',      $end_date);
    update_post_meta($project_id, 'project_escrow_status', 'pending');
    update_post_meta($project_id, 'project_revisions_max', $revisions_max ?: 2);
    update_post_meta($project_id, 'project_revisions_used', 0);

    // ۴. آپدیت وضعیت کمپین
    update_post_meta($campaign_id, 'campaign_status', 'in_progress');

    // ۵. ارسال اعلان
    royita_notify_proposal_accepted($proposal_id);

    wp_send_json_success([
        'message'    => __('پیشنهاد تایید شد و پروژه ایجاد گردید.', 'royita'),
        'project_id' => $project_id,
        'redirect'   => home_url('/brand/project/?id=' . $project_id),
    ]);
}

// =====================================================
// REJECT PROPOSAL (برند — رد کریتور)
// =====================================================
add_action('wp_ajax_royita_reject_proposal', 'royita_ajax_reject_proposal');
function royita_ajax_reject_proposal() {
    check_ajax_referer('royita_dashboard_nonce', 'nonce');

    if (!royita_is_brand()) {
        wp_send_json_error(['message' => __('دسترسی غیرمجاز.', 'royita')], 403);
    }

    $proposal_id = (int) sanitize_text_field($_POST['proposal_id'] ?? 0);
    $campaign_id = (int) get_post_meta($proposal_id, 'proposal_campaign', true);
    $campaign    = get_post($campaign_id);

    if (!$campaign || (int) $campaign->post_author !== get_current_user_id()) {
        wp_send_json_error(['message' => __('شما مجاز به رد این پیشنهاد نیستید.', 'royita')], 403);
    }

    $current = get_post_meta($proposal_id, 'proposal_status', true);
    if ($current !== 'pending') {
        wp_send_json_error(['message' => __('این پیشنهاد قابل رد نیست.', 'royita')]);
    }

    update_post_meta($proposal_id, 'proposal_status', 'rejected');

    wp_send_json_success([
        'message' => __('پیشنهاد رد شد.', 'royita'),
    ]);
}

// =====================================================
// WITHDRAW PROPOSAL (کریتور — پس گرفتن پیشنهاد)
// =====================================================
add_action('wp_ajax_royita_withdraw_proposal', 'royita_ajax_withdraw_proposal');
function royita_ajax_withdraw_proposal() {
    check_ajax_referer('royita_dashboard_nonce', 'nonce');

    if (!royita_is_creator()) {
        wp_send_json_error(['message' => __('دسترسی غیرمجاز.', 'royita')], 403);
    }

    $proposal_id = (int) sanitize_text_field($_POST['proposal_id'] ?? 0);
    $proposal    = get_post($proposal_id);

    if (!$proposal || (int) $proposal->post_author !== get_current_user_id()) {
        wp_send_json_error(['message' => __('شما مجاز به پس گرفتن این پیشنهاد نیستید.', 'royita')], 403);
    }

    $current = get_post_meta($proposal_id, 'proposal_status', true);
    if ($current !== 'pending') {
        wp_send_json_error(['message' => __('فقط پیشنهادهای در انتظار قابل پس گرفتن هستند.', 'royita')]);
    }

    update_post_meta($proposal_id, 'proposal_status', 'withdrawn');

    // کاهش شمارنده پیشنهادها
    $campaign_id = (int) get_post_meta($proposal_id, 'proposal_campaign', true);
    $count = (int) get_post_meta($campaign_id, 'proposals_count', true);
    if ($count > 0) {
        update_post_meta($campaign_id, 'proposals_count', $count - 1);
    }

    wp_send_json_success([
        'message' => __('پیشنهاد شما پس گرفته شد.', 'royita'),
    ]);
}

// =====================================================
// SUBMIT DELIVERABLE (کریتور — آپلود فایل تحویلی)
// =====================================================
add_action('wp_ajax_royita_submit_deliverable', 'royita_ajax_submit_deliverable');
function royita_ajax_submit_deliverable() {
    check_ajax_referer('royita_dashboard_nonce', 'nonce');

    if (!royita_is_creator()) {
        wp_send_json_error(['message' => __('فقط کریتورها می‌توانند فایل آپلود کنند.', 'royita')], 403);
    }

    $project_id = (int) sanitize_text_field($_POST['project_id'] ?? 0);
    $note       = sanitize_textarea_field($_POST['note'] ?? '');

    // بررسی ownership پروژه
    $creator_user_id = (int) get_post_meta($project_id, 'project_creator_id', true);
    if ($creator_user_id !== get_current_user_id()) {
        wp_send_json_error(['message' => __('شما مجاز به آپلود برای این پروژه نیستید.', 'royita')], 403);
    }

    $project_status = get_post_meta($project_id, 'project_status', true);
    if (!in_array($project_status, ['active', 'revision_requested'], true)) {
        wp_send_json_error(['message' => __('در وضعیت فعلی امکان آپلود وجود ندارد.', 'royita')]);
    }

    // آپلود فایل
    if (empty($_FILES['deliverable_file']['name'])) {
        wp_send_json_error(['message' => __('فایلی انتخاب نشده است.', 'royita')]);
    }

    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';

    $attachment_id = media_handle_upload('deliverable_file', $project_id);

    if (is_wp_error($attachment_id)) {
        wp_send_json_error(['message' => __('خطا در آپلود فایل: ', 'royita') . $attachment_id->get_error_message()]);
    }

    // ذخیره در deliverables repeater
    $deliverables   = get_post_meta($project_id, 'project_deliverables', true) ?: [];
    $deliverables[] = [
        'file'        => $attachment_id,
        'version'     => 'v' . (count($deliverables) + 1),
        'note'        => $note,
        'uploaded_at' => current_time('mysql'),
        'status'      => 'pending',
    ];
    update_post_meta($project_id, 'project_deliverables', $deliverables);

    // آپدیت وضعیت پروژه
    update_post_meta($project_id, 'project_status', 'in_review');

    // ارسال اعلان به برند
    royita_notify_deliverable_submitted($project_id);

    wp_send_json_success([
        'message'       => __('فایل با موفقیت آپلود شد. منتظر تایید برند باشید.', 'royita'),
        'file_url'      => wp_get_attachment_url($attachment_id),
        'attachment_id' => $attachment_id,
    ]);
}

// =====================================================
// APPROVE DELIVERABLE (برند — تایید فایل نهایی)
// =====================================================
add_action('wp_ajax_royita_approve_deliverable', 'royita_ajax_approve_deliverable');
function royita_ajax_approve_deliverable() {
    check_ajax_referer('royita_dashboard_nonce', 'nonce');

    if (!royita_is_brand()) {
        wp_send_json_error(['message' => __('فقط برندها می‌توانند فایل را تایید کنند.', 'royita')], 403);
    }

    $project_id = (int) sanitize_text_field($_POST['project_id'] ?? 0);

    // بررسی ownership
    $brand_user_id = (int) get_post_meta($project_id, 'project_brand_id', true);
    if ($brand_user_id !== get_current_user_id()) {
        wp_send_json_error(['message' => __('شما مجاز به تایید این پروژه نیستید.', 'royita')], 403);
    }

    $project_status = get_post_meta($project_id, 'project_status', true);
    if ($project_status !== 'in_review') {
        wp_send_json_error(['message' => __('پروژه در وضعیت بررسی نیست.', 'royita')]);
    }

    // آپدیت آخرین deliverable به approved
    $deliverables = get_post_meta($project_id, 'project_deliverables', true) ?: [];
    if (!empty($deliverables)) {
        $last = count($deliverables) - 1;
        $deliverables[$last]['status'] = 'approved';
        update_post_meta($project_id, 'project_deliverables', $deliverables);
    }

    // تکمیل پروژه
    update_post_meta($project_id, 'project_status',        'completed');
    update_post_meta($project_id, 'project_escrow_status', 'released');

    // آپدیت وضعیت کمپین
    $campaign_id = (int) get_post_meta($project_id, 'project_campaign', true);
    update_post_meta($campaign_id, 'campaign_status', 'done');

    // آپدیت آمار کریتور
    $creator_user_id  = (int) get_post_meta($project_id, 'project_creator_id', true);
    $amount           = (int) get_post_meta($project_id, 'project_amount', true);
    $total_earned     = (int) get_user_meta($creator_user_id, 'creator_total_earned', true);
    $total_projects   = (int) get_user_meta($creator_user_id, 'creator_total_projects', true);
    update_user_meta($creator_user_id, 'creator_total_earned',   $total_earned + $amount);
    update_user_meta($creator_user_id, 'creator_total_projects', $total_projects + 1);

    // ارسال اعلان
    royita_notify_project_completed($project_id);

    wp_send_json_success([
        'message'  => __('پروژه تایید و تکمیل شد. پرداخت آزاد خواهد شد.', 'royita'),
        'redirect' => home_url('/brand/projects/'),
    ]);
}

// =====================================================
// REQUEST REVISION (برند — درخواست ویرایش)
// =====================================================
add_action('wp_ajax_royita_request_revision', 'royita_ajax_request_revision');
function royita_ajax_request_revision() {
    check_ajax_referer('royita_dashboard_nonce', 'nonce');

    if (!royita_is_brand()) {
        wp_send_json_error(['message' => __('دسترسی غیرمجاز.', 'royita')], 403);
    }

    $project_id = (int) sanitize_text_field($_POST['project_id'] ?? 0);
    $note       = sanitize_textarea_field($_POST['note'] ?? '');

    $brand_user_id = (int) get_post_meta($project_id, 'project_brand_id', true);
    if ($brand_user_id !== get_current_user_id()) {
        wp_send_json_error(['message' => __('شما مجاز به این عملیات نیستید.', 'royita')], 403);
    }

    $project_status = get_post_meta($project_id, 'project_status', true);
    if ($project_status !== 'in_review') {
        wp_send_json_error(['message' => __('پروژه در وضعیت بررسی نیست.', 'royita')]);
    }

    // بررسی تعداد ویرایش مجاز
    $used = (int) get_post_meta($project_id, 'project_revisions_used', true);
    $max  = (int) get_post_meta($project_id, 'project_revisions_max', true);

    if ($used >= $max) {
        wp_send_json_error(['message' => sprintf(
            __('حداکثر تعداد ویرایش (%d بار) استفاده شده است.', 'royita'), $max
        )]);
    }

    update_post_meta($project_id, 'project_status',          'revision_requested');
    update_post_meta($project_id, 'project_revisions_used',  $used + 1);
    update_post_meta($project_id, 'project_revision_note',   $note);

    // اعلان به کریتور
    royita_notify_revision_requested($project_id, $note);

    wp_send_json_success([
        'message'        => __('درخواست ویرایش ارسال شد.', 'royita'),
        'revisions_used' => $used + 1,
        'revisions_max'  => $max,
    ]);
}

// =====================================================
// OPEN DISPUTE (اختلاف — هر دو طرف)
// =====================================================
add_action('wp_ajax_royita_open_dispute', 'royita_ajax_open_dispute');
function royita_ajax_open_dispute() {
    check_ajax_referer('royita_dashboard_nonce', 'nonce');

    if (!is_user_logged_in()) {
        wp_send_json_error(['message' => __('دسترسی غیرمجاز.', 'royita')], 401);
    }

    $project_id = (int) sanitize_text_field($_POST['project_id'] ?? 0);
    $reason     = sanitize_textarea_field($_POST['reason'] ?? '');

    if (empty($reason)) {
        wp_send_json_error(['message' => __('دلیل اختلاف الزامی است.', 'royita')]);
    }

    if (!royita_can_access_project($project_id)) {
        wp_send_json_error(['message' => __('شما مجاز به این عملیات نیستید.', 'royita')], 403);
    }

    $allowed = ['active', 'in_review', 'revision_requested'];
    $current = get_post_meta($project_id, 'project_status', true);
    if (!in_array($current, $allowed, true)) {
        wp_send_json_error(['message' => __('در وضعیت فعلی امکان ثبت اختلاف وجود ندارد.', 'royita')]);
    }

    update_post_meta($project_id, 'project_status',         'disputed');
    update_post_meta($project_id, 'project_dispute_reason', $reason);
    update_post_meta($project_id, 'project_dispute_by',     get_current_user_id());
    update_post_meta($project_id, 'project_dispute_date',   current_time('mysql'));

    // اعلان به ادمین
    royita_notify_dispute_opened($project_id, $reason);

    wp_send_json_success([
        'message' => __('اختلاف ثبت شد. تیم پشتیبانی رویتا پیگیری خواهد کرد.', 'royita'),
    ]);
}

// =====================================================
// SUBMIT REVIEW (ثبت نظر و امتیاز)
// =====================================================
add_action('wp_ajax_royita_submit_review', 'royita_ajax_submit_review');
function royita_ajax_submit_review() {
    check_ajax_referer('royita_dashboard_nonce', 'nonce');

    if (!is_user_logged_in()) {
        wp_send_json_error(['message' => __('برای ثبت نظر باید وارد شوید.', 'royita')], 401);
    }

    $project_id    = (int) sanitize_text_field($_POST['project_id'] ?? 0);
    $rating        = (int) sanitize_text_field($_POST['rating'] ?? 0);
    $communication = (int) sanitize_text_field($_POST['communication'] ?? 0);
    $quality       = (int) sanitize_text_field($_POST['quality'] ?? 0);
    $delivery      = (int) sanitize_text_field($_POST['delivery'] ?? 0);
    $text          = sanitize_textarea_field($_POST['text'] ?? '');

    // اعتبارسنجی
    foreach ([$rating, $communication, $quality, $delivery] as $r) {
        if ($r < 1 || $r > 5) {
            wp_send_json_error(['message' => __('امتیاز باید بین ۱ تا ۵ باشد.', 'royita')]);
        }
    }

    $project_status = get_post_meta($project_id, 'project_status', true);
    if ($project_status !== 'completed') {
        wp_send_json_error(['message' => __('فقط برای پروژه‌های تکمیل‌شده می‌توان نظر ثبت کرد.', 'royita')]);
    }

    $user_id       = get_current_user_id();
    $brand_user_id = (int) get_post_meta($project_id, 'project_brand_id', true);
    $creator_user_id = (int) get_post_meta($project_id, 'project_creator_id', true);

    // تعیین نوع و طرف مقابل
    if ($user_id === $brand_user_id) {
        $review_type = 'brand_to_creator';
        $to_user_id  = $creator_user_id;
    } elseif ($user_id === $creator_user_id) {
        $review_type = 'creator_to_brand';
        $to_user_id  = $brand_user_id;
    } else {
        wp_send_json_error(['message' => __('شما در این پروژه نیستید.', 'royita')], 403);
    }

    // بررسی تکراری بودن
    $existing = get_posts([
        'post_type'      => 'royita_review',
        'posts_per_page' => 1,
        'fields'         => 'ids',
        'meta_query'     => [
            ['key' => 'review_project',   'value' => $project_id, 'compare' => '='],
            ['key' => 'review_from_user', 'value' => $user_id,    'compare' => '='],
        ],
    ]);
    if (!empty($existing)) {
        wp_send_json_error(['message' => __('شما قبلاً برای این پروژه نظر ثبت کرده‌اید.', 'royita')]);
    }

    // ذخیره نظر
    $review_id = wp_insert_post([
        'post_type'   => 'royita_review',
        'post_status' => 'publish',
        'post_title'  => sprintf('نظر پروژه #%d', $project_id),
        'post_author' => $user_id,
    ]);

    if (is_wp_error($review_id)) {
        wp_send_json_error(['message' => __('خطا در ثبت نظر.', 'royita')]);
    }

    update_post_meta($review_id, 'review_project',       $project_id);
    update_post_meta($review_id, 'review_from_user',     $user_id);
    update_post_meta($review_id, 'review_to_user',       $to_user_id);
    update_post_meta($review_id, 'review_type',          $review_type);
    update_post_meta($review_id, 'review_rating',        $rating);
    update_post_meta($review_id, 'review_communication', $communication);
    update_post_meta($review_id, 'review_quality',       $quality);
    update_post_meta($review_id, 'review_delivery',      $delivery);
    update_post_meta($review_id, 'review_text',          $text);
    update_post_meta($review_id, 'review_is_public',     1);

    // آپدیت میانگین امتیاز کریتور
    if ($review_type === 'brand_to_creator') {
        $creator_posts = get_posts([
            'post_type'      => 'royita_creator',
            'author'         => $creator_user_id,
            'posts_per_page' => 1,
            'fields'         => 'ids',
        ]);
        if (!empty($creator_posts)) {
            $new_avg = royita_get_creator_rating($creator_posts[0]);
            update_post_meta($creator_posts[0], 'creator_rating', $new_avg);
            update_user_meta($creator_user_id, 'creator_rating_avg', $new_avg);
        }
    }

    wp_send_json_success([
        'message' => __('نظر شما با موفقیت ثبت شد.', 'royita'),
    ]);
}

// =====================================================
// GET CAMPAIGN PROPOSALS (لیست پیشنهادها برای برند)
// =====================================================
add_action('wp_ajax_royita_get_proposals', 'royita_ajax_get_proposals');
function royita_ajax_get_proposals() {
    check_ajax_referer('royita_dashboard_nonce', 'nonce');

    if (!royita_is_brand()) {
        wp_send_json_error(['message' => __('دسترسی غیرمجاز.', 'royita')], 403);
    }

    $campaign_id = (int) sanitize_text_field($_POST['campaign_id'] ?? 0);
    $campaign    = get_post($campaign_id);

    if (!$campaign || (int) $campaign->post_author !== get_current_user_id()) {
        wp_send_json_error(['message' => __('کمپین یافت نشد.', 'royita')], 403);
    }

    $proposals = get_posts([
        'post_type'      => 'royita_proposal',
        'posts_per_page' => -1,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'meta_query'     => [
            ['key' => 'proposal_campaign', 'value' => $campaign_id, 'compare' => '='],
        ],
    ]);

    $data = [];
    foreach ($proposals as $proposal) {
        $creator_id      = (int) get_post_meta($proposal->ID, 'proposal_creator', true);
        $creator_name    = get_the_title($creator_id);
        $creator_rating  = royita_get_creator_rating($creator_id);
        $creator_url     = get_permalink($creator_id);

        $data[] = [
            'id'            => $proposal->ID,
            'status'        => get_post_meta($proposal->ID, 'proposal_status', true),
            'price'         => (int) get_post_meta($proposal->ID, 'proposal_price', true),
            'price_formatted' => royita_format_price((int) get_post_meta($proposal->ID, 'proposal_price', true)),
            'delivery_days' => (int) get_post_meta($proposal->ID, 'proposal_delivery_days', true),
            'cover_letter'  => get_post_meta($proposal->ID, 'proposal_cover_letter', true),
            'creator_name'  => $creator_name,
            'creator_rating'=> $creator_rating,
            'creator_url'   => $creator_url,
            'submitted_at'  => get_the_date('Y/m/d', $proposal->ID),
        ];
    }

    wp_send_json_success(['proposals' => $data]);
}

// =====================================================
// SEND MESSAGE (پیام بین برند و کریتور)
// =====================================================
add_action('wp_ajax_royita_send_message', 'royita_ajax_send_message');
function royita_ajax_send_message() {
    check_ajax_referer('royita_dashboard_nonce', 'nonce');

    if (!is_user_logged_in()) {
        wp_send_json_error(['message' => __('برای ارسال پیام باید وارد شوید.', 'royita')], 401);
    }

    $to_user_id = (int) sanitize_text_field($_POST['to_user_id'] ?? 0);
    $message    = sanitize_textarea_field($_POST['message'] ?? '');
    $project_id = (int) sanitize_text_field($_POST['project_id'] ?? 0);

    if (empty($message)) {
        wp_send_json_error(['message' => __('متن پیام نمی‌تواند خالی باشد.', 'royita')]);
    }

    if (mb_strlen($message) > 2000) {
        wp_send_json_error(['message' => __('پیام نمی‌تواند بیشتر از ۲۰۰۰ کاراکتر باشد.', 'royita')]);
    }

    global $wpdb;
    $table = $wpdb->prefix . 'royita_messages';

    // ساخت جدول اگر وجود نداشت
    royita_maybe_create_messages_table();

    $wpdb->insert($table, [
        'from_user_id' => get_current_user_id(),
        'to_user_id'   => $to_user_id,
        'project_id'   => $project_id,
        'message'      => $message,
        'is_read'      => 0,
        'created_at'   => current_time('mysql'),
    ], ['%d','%d','%d','%s','%d','%s']);

    if ($wpdb->last_error) {
        wp_send_json_error(['message' => __('خطا در ارسال پیام.', 'royita')]);
    }

    $message_id = $wpdb->insert_id;

    // اعلان ایمیل
    royita_notify_new_message(get_current_user_id(), $to_user_id, $message);

    wp_send_json_success([
        'message_id' => $message_id,
        'message'    => __('پیام ارسال شد.', 'royita'),
        'from_name'  => wp_get_current_user()->display_name,
        'time'       => current_time('H:i'),
    ]);
}

// =====================================================
// GET MESSAGES (دریافت پیام‌های یک پروژه)
// =====================================================
add_action('wp_ajax_royita_get_messages', 'royita_ajax_get_messages');
function royita_ajax_get_messages() {
    check_ajax_referer('royita_dashboard_nonce', 'nonce');

    if (!is_user_logged_in()) {
        wp_send_json_error(['message' => __('دسترسی غیرمجاز.', 'royita')], 401);
    }

    $project_id   = (int) sanitize_text_field($_POST['project_id'] ?? 0);
    $last_message = (int) sanitize_text_field($_POST['last_id'] ?? 0);

    if (!royita_can_access_project($project_id)) {
        wp_send_json_error(['message' => __('دسترسی غیرمجاز.', 'royita')], 403);
    }

    global $wpdb;
    $table = $wpdb->prefix . 'royita_messages';
    royita_maybe_create_messages_table();

    $user_id = get_current_user_id();

    $messages = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM {$table}
         WHERE project_id = %d AND id > %d
         ORDER BY created_at ASC LIMIT 50",
        $project_id, $last_message
    ));

    // علامت‌گذاری پیام‌های خوانده‌نشده
    $wpdb->update($table,
        ['is_read' => 1],
        ['project_id' => $project_id, 'to_user_id' => $user_id, 'is_read' => 0],
        ['%d'], ['%d','%d','%d']
    );

    $formatted = [];
    foreach ($messages as $msg) {
        $from = get_userdata($msg->from_user_id);
        $formatted[] = [
            'id'        => (int) $msg->id,
            'message'   => esc_html($msg->message),
            'from_id'   => (int) $msg->from_user_id,
            'from_name' => $from ? $from->display_name : 'کاربر',
            'is_mine'   => (int) $msg->from_user_id === $user_id,
            'time'      => date('H:i', strtotime($msg->created_at)),
            'date'      => date('Y/m/d', strtotime($msg->created_at)),
        ];
    }

    wp_send_json_success(['messages' => $formatted]);
}

// =====================================================
// CREATE MESSAGES TABLE (helper)
// =====================================================
function royita_maybe_create_messages_table() {
    global $wpdb;
    $table   = $wpdb->prefix . 'royita_messages';
    $charset = $wpdb->get_charset_collate();

    if ($wpdb->get_var("SHOW TABLES LIKE '{$table}'") === $table) {
        return;
    }

    $sql = "CREATE TABLE {$table} (
        id          BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        from_user_id BIGINT(20) UNSIGNED NOT NULL,
        to_user_id  BIGINT(20) UNSIGNED NOT NULL,
        project_id  BIGINT(20) UNSIGNED NOT NULL DEFAULT 0,
        message     TEXT NOT NULL,
        is_read     TINYINT(1) NOT NULL DEFAULT 0,
        created_at  DATETIME NOT NULL,
        PRIMARY KEY (id),
        KEY idx_project (project_id),
        KEY idx_to_user (to_user_id)
    ) {$charset};";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
}

// =====================================================
// NEWSLETTER SUBSCRIBE
// =====================================================
add_action('wp_ajax_royita_newsletter_subscribe',        'royita_ajax_newsletter_subscribe');
add_action('wp_ajax_nopriv_royita_newsletter_subscribe', 'royita_ajax_newsletter_subscribe');
function royita_ajax_newsletter_subscribe() {
    check_ajax_referer('royita_nonce', 'nonce');
    $email = sanitize_email($_POST['email'] ?? '');
    if (!is_email($email)) {
        wp_send_json_error(['message' => 'ایمیل نامعتبر است.']);
    }
    $subscribers = get_option('royita_newsletter_subscribers', []);
    if (in_array($email, $subscribers, true)) {
        wp_send_json_error(['message' => 'این ایمیل قبلاً ثبت شده است.']);
    }
    $subscribers[] = $email;
    update_option('royita_newsletter_subscribers', $subscribers);
    wp_send_json_success(['message' => 'با موفقیت عضو شدید!']);
}
