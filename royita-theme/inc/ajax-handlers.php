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
