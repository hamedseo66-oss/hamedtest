<?php
/**
 * Royita Helper Functions
 *
 * @package Royita
 */

defined('ABSPATH') || exit;

// =====================================================
// USER ROLE HELPERS
// =====================================================

/**
 * Get current user role for Royita context
 *
 * @return string 'brand'|'creator'|'admin'|'guest'
 */
function royita_get_user_role(): string {
    if (!is_user_logged_in()) return 'guest';

    $user = wp_get_current_user();
    $roles = (array) $user->roles;

    if (in_array('administrator', $roles, true)) return 'admin';
    if (in_array('brand_owner', $roles, true))   return 'brand';
    if (in_array('creator_user', $roles, true))  return 'creator';

    return 'guest';
}

/**
 * Is current user a brand owner?
 */
function royita_is_brand(): bool {
    return royita_get_user_role() === 'brand';
}

/**
 * Is current user a creator?
 */
function royita_is_creator(): bool {
    return royita_get_user_role() === 'creator';
}

// =====================================================
// FORMATTING HELPERS
// =====================================================

/**
 * Format a price amount in Persian style
 *
 * @param int|float $amount
 * @return string formatted price with تومان
 */
function royita_format_price($amount): string {
    $amount   = (int) $amount;
    $formatted = number_format($amount);

    // Convert Western digits to Eastern Arabic (Persian)
    $western = ['0','1','2','3','4','5','6','7','8','9',','];
    $eastern = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹','،'];
    $formatted = str_replace($western, $eastern, $formatted);

    return $formatted . ' تومان';
}

/**
 * Truncate text with ellipsis
 *
 * @param string $text
 * @param int    $length Character limit
 * @return string
 */
function royita_truncate_text(string $text, int $length = 100): string {
    $text = wp_strip_all_tags($text);
    if (mb_strlen($text) <= $length) return $text;
    return mb_substr($text, 0, $length) . '...';
}

// =====================================================
// CREATOR HELPERS
// =====================================================

/**
 * Get creator average rating from reviews
 *
 * @param int $post_id Creator post ID
 * @return float Average rating (0-5)
 */
function royita_get_creator_rating(int $post_id): float {
    $reviews = get_posts([
        'post_type'      => 'royita_review',
        'posts_per_page' => -1,
        'meta_query'     => [
            [
                'key'     => 'review_creator',
                'value'   => $post_id,
                'compare' => '=',
            ],
        ],
        'fields' => 'ids',
    ]);

    if (empty($reviews)) {
        // Fall back to stored rating
        return (float) get_post_meta($post_id, 'creator_rating', true) ?: 0.0;
    }

    $total = 0;
    $count = 0;
    foreach ($reviews as $review_id) {
        $rating = (float) get_post_meta($review_id, 'review_rating', true);
        if ($rating > 0) {
            $total += $rating;
            $count++;
        }
    }

    return $count > 0 ? round($total / $count, 1) : 0.0;
}

/**
 * Get creator avatar img tag
 *
 * @param int    $post_id Creator post ID
 * @param string $size    Image size
 * @return string HTML img tag or placeholder
 */
function royita_get_creator_avatar(int $post_id, string $size = 'royita-avatar'): string {
    if (has_post_thumbnail($post_id)) {
        return get_the_post_thumbnail($post_id, $size, [
            'class' => 'creator-card__avatar',
            'alt'   => get_the_title($post_id),
        ]);
    }

    $name    = get_the_title($post_id);
    $initial = mb_substr($name, 0, 1, 'UTF-8');
    return sprintf(
        '<div class="creator-avatar-placeholder" aria-label="%s"><span>%s</span></div>',
        esc_attr($name),
        esc_html($initial)
    );
}

// =====================================================
// STATUS LABEL HELPERS
// =====================================================

/**
 * Get campaign status Persian label and CSS class
 *
 * @param string $status
 * @return array ['label' => string, 'class' => string]
 */
function royita_get_campaign_status_label(string $status): array {
    $statuses = [
        'draft'     => ['label' => 'پیش‌نویس',  'class' => 'badge-gray'],
        'active'    => ['label' => 'فعال',       'class' => 'badge-success'],
        'paused'    => ['label' => 'متوقف',      'class' => 'badge-warning'],
        'completed' => ['label' => 'تکمیل شده',  'class' => 'badge-primary'],
        'cancelled' => ['label' => 'لغو شده',    'class' => 'badge-danger'],
    ];

    return $statuses[$status] ?? ['label' => $status, 'class' => 'badge-gray'];
}

/**
 * Get proposal status Persian label
 *
 * @param string $status
 * @return array ['label' => string, 'class' => string]
 */
function royita_get_proposal_status_label(string $status): array {
    $statuses = [
        'pending'   => ['label' => 'در انتظار بررسی', 'class' => 'badge-warning'],
        'accepted'  => ['label' => 'پذیرفته شده',     'class' => 'badge-success'],
        'rejected'  => ['label' => 'رد شده',          'class' => 'badge-danger'],
        'withdrawn' => ['label' => 'پس گرفته شده',    'class' => 'badge-gray'],
    ];

    return $statuses[$status] ?? ['label' => $status, 'class' => 'badge-gray'];
}

/**
 * Get project status Persian label
 *
 * @param string $status
 * @return array ['label' => string, 'class' => string]
 */
function royita_get_project_status_label(string $status): array {
    $statuses = [
        'active'            => ['label' => 'در حال انجام',   'class' => 'badge-primary'],
        'in_review'         => ['label' => 'در حال بررسی',   'class' => 'badge-warning'],
        'revision_requested'=> ['label' => 'درخواست ویرایش', 'class' => 'badge-cta'],
        'completed'         => ['label' => 'تکمیل شده',      'class' => 'badge-success'],
        'disputed'          => ['label' => 'مورد اختلاف',    'class' => 'badge-danger'],
        'cancelled'         => ['label' => 'لغو شده',        'class' => 'badge-gray'],
    ];

    return $statuses[$status] ?? ['label' => $status, 'class' => 'badge-gray'];
}

/**
 * Get creator badge Persian label
 *
 * @param string $badge
 * @return array ['label' => string, 'class' => string, 'icon' => string]
 */
function royita_get_badge_label(string $badge): array {
    $badges = [
        'none'   => ['label' => '',             'class' => '',            'icon' => ''],
        'rising' => ['label' => 'در حال رشد',  'class' => 'badge-rising','icon' => '🚀'],
        'top'    => ['label' => 'برتر',         'class' => 'badge-top',   'icon' => '⭐'],
        'pro'    => ['label' => 'حرفه‌ای',      'class' => 'badge-pro',   'icon' => '💎'],
        'elite'  => ['label' => 'الیت',         'class' => 'badge-elite', 'icon' => '👑'],
    ];

    return $badges[$badge] ?? ['label' => '', 'class' => '', 'icon' => ''];
}

// =====================================================
// QUERY HELPERS
// =====================================================

/**
 * Get active campaigns
 *
 * @param int    $limit    Number of campaigns (-1 for all)
 * @param string $category Campaign category slug
 * @param int    $page     Page number for pagination
 * @return WP_Query
 */
function royita_get_active_campaigns(int $limit = 6, string $category = '', int $page = 1): WP_Query {
    $args = [
        'post_type'      => 'royita_campaign',
        'post_status'    => 'publish',
        'posts_per_page' => $limit,
        'paged'          => $page,
        'meta_query'     => [
            [
                'key'     => 'campaign_status',
                'value'   => 'active',
                'compare' => '=',
            ],
        ],
        'orderby'  => 'date',
        'order'    => 'DESC',
    ];

    if (!empty($category)) {
        $args['tax_query'] = [
            [
                'taxonomy' => 'campaign_category',
                'field'    => 'slug',
                'terms'    => $category,
            ],
        ];
    }

    return new WP_Query($args);
}

/**
 * Get top creators sorted by rating
 *
 * @param int $limit Number of creators
 * @return WP_Query
 */
function royita_get_top_creators(int $limit = 8): WP_Query {
    return new WP_Query([
        'post_type'      => 'royita_creator',
        'post_status'    => 'publish',
        'posts_per_page' => $limit,
        'meta_key'       => 'creator_rating',
        'orderby'        => 'meta_value_num',
        'order'          => 'DESC',
        'meta_query'     => [
            'relation' => 'AND',
            [
                'key'     => 'creator_rating',
                'compare' => 'EXISTS',
            ],
            [
                'key'     => 'creator_availability',
                'value'   => 'available',
                'compare' => '=',
            ],
        ],
    ]);
}

// =====================================================
// RATING STARS HTML
// =====================================================

/**
 * Render rating stars HTML
 *
 * @param float $rating Rating out of 5
 * @param bool  $show_number Show numeric rating
 * @return string HTML
 */
function royita_rating_stars(float $rating, bool $show_number = true): string {
    $html = '<div class="rating-stars" aria-label="امتیاز: ' . esc_attr($rating) . ' از ۵">';

    for ($i = 1; $i <= 5; $i++) {
        if ($i <= floor($rating)) {
            $html .= '<span class="rating-star filled" aria-hidden="true">★</span>';
        } elseif ($i - 0.5 <= $rating) {
            $html .= '<span class="rating-star half-filled" aria-hidden="true">★</span>';
        } else {
            $html .= '<span class="rating-star" aria-hidden="true">★</span>';
        }
    }

    $html .= '</div>';

    if ($show_number && $rating > 0) {
        $html .= '<span class="rating-number">' . number_format($rating, 1) . '</span>';
    }

    return $html;
}

// =====================================================
// CAMPAIGN CARD HTML
// =====================================================

/**
 * Render a campaign card
 *
 * @param int $post_id Campaign post ID
 * @return string HTML
 */
function royita_campaign_card(int $post_id): string {
    $title          = get_the_title($post_id);
    $permalink      = get_permalink($post_id);
    $budget_min     = get_post_meta($post_id, 'campaign_budget_min', true);
    $budget_max     = get_post_meta($post_id, 'campaign_budget_max', true);
    $deadline       = get_post_meta($post_id, 'campaign_deadline', true);
    $status         = get_post_meta($post_id, 'campaign_status', true);
    $proposals      = (int) get_post_meta($post_id, 'proposals_count', true);
    $status_data    = royita_get_campaign_status_label($status);
    $platforms      = get_the_terms($post_id, 'campaign_platform');
    $is_featured    = get_post_meta($post_id, 'is_featured', true);
    $author_id      = (int) get_post_field('post_author', $post_id);
    $brand_logo_id  = (int) get_user_meta($author_id, 'brand_logo', true);
    $author_name    = get_the_author_meta('display_name', $author_id);

    ob_start();
    ?>
    <article class="campaign-card <?php echo $is_featured ? 'campaign-card--featured' : ''; ?>">
        <div class="campaign-card__header">
            <div class="campaign-card__brand">
                <?php if ($brand_logo_id): ?>
                    <img src="<?php echo esc_url(wp_get_attachment_image_url($brand_logo_id, 'royita-brand-logo')); ?>"
                         alt="<?php echo esc_attr($author_name); ?>"
                         class="campaign-card__brand-logo">
                <?php else: ?>
                    <div class="campaign-card__brand-logo-placeholder">
                        <?php echo esc_html(mb_substr($author_name, 0, 1, 'UTF-8')); ?>
                    </div>
                <?php endif; ?>
                <div>
                    <div class="text-sm font-semibold text-dark"><?php echo esc_html($author_name); ?></div>
                    <?php if ($deadline): ?>
                        <div class="text-xs text-gray">مهلت: <?php echo esc_html($deadline); ?></div>
                    <?php endif; ?>
                </div>
            </div>
            <span class="badge <?php echo esc_attr($status_data['class']); ?>">
                <?php echo esc_html($status_data['label']); ?>
            </span>
        </div>

        <div class="campaign-card__body">
            <h3 class="campaign-card__title">
                <a href="<?php echo esc_url($permalink); ?>"><?php echo esc_html($title); ?></a>
            </h3>

            <div class="campaign-card__meta">
                <?php if ($budget_min || $budget_max): ?>
                    <div class="campaign-card__meta-item">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                        <span>
                            <?php
                            if ($budget_min && $budget_max) {
                                echo esc_html(royita_format_price($budget_min)) . ' تا ' . esc_html(royita_format_price($budget_max));
                            } elseif ($budget_min) {
                                echo 'از ' . esc_html(royita_format_price($budget_min));
                            } else {
                                echo 'تا ' . esc_html(royita_format_price($budget_max));
                            }
                            ?>
                        </span>
                    </div>
                <?php endif; ?>
            </div>

            <?php if (!empty($platforms) && !is_wp_error($platforms)): ?>
                <div class="campaign-card__platforms">
                    <?php foreach ($platforms as $platform): ?>
                        <span class="platform-badge"><?php echo esc_html($platform->name); ?></span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="campaign-card__footer">
            <div class="campaign-card__proposals">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                <?php echo esc_html($proposals); ?> پیشنهاد
            </div>
            <a href="<?php echo esc_url($permalink); ?>" class="btn btn-primary btn-sm">
                مشاهده کمپین
            </a>
        </div>
    </article>
    <?php
    return ob_get_clean();
}

// =====================================================
// DATE HELPERS
// =====================================================

/**
 * Get remaining days until a deadline
 *
 * @param string $deadline Date string Y/m/d or Y-m-d
 * @return int|null Days remaining or null if invalid
 */
function royita_days_remaining(string $deadline): ?int {
    if (empty($deadline)) return null;

    $deadline = str_replace('/', '-', $deadline);
    $deadline_ts = strtotime($deadline);

    if (!$deadline_ts) return null;

    $diff = $deadline_ts - time();
    return (int) ceil($diff / DAY_IN_SECONDS);
}

/**
 * Format days remaining to Persian string
 */
function royita_format_deadline(string $deadline): string {
    $days = royita_days_remaining($deadline);

    if ($days === null) return '';
    if ($days < 0)     return '<span class="text-danger">منقضی شده</span>';
    if ($days === 0)   return '<span class="text-danger">امروز</span>';
    if ($days === 1)   return '<span class="text-warning">فردا</span>';
    if ($days <= 7)    return '<span class="text-warning">' . $days . ' روز دیگر</span>';
    return $days . ' روز دیگر';
}

// =====================================================
// PERMISSION HELPERS
// =====================================================

/**
 * Can the current user submit proposals?
 */
function royita_can_submit_proposals(): bool {
    return is_user_logged_in() && (current_user_can('submit_proposals') || royita_is_creator());
}

/**
 * Can the current user access a specific project?
 *
 * @param int $project_id Project post ID
 */
function royita_can_access_project(int $project_id): bool {
    if (!is_user_logged_in()) return false;
    if (current_user_can('manage_options')) return true;
    $user_id      = get_current_user_id();
    $brand_user   = (int) get_post_meta($project_id, 'project_brand_user', true);
    $creator_user = (int) get_post_field('post_author', $project_id);
    $brand_post   = get_posts(['post_type' => 'royita_brand', 'author' => $user_id, 'posts_per_page' => 1, 'fields' => 'ids']);
    $brand_post_id  = !empty($brand_post) ? (int) $brand_post[0] : 0;
    $project_brand  = (int) get_post_meta($project_id, 'project_brand', true);
    return ($creator_user === $user_id) || ($brand_post_id > 0 && $project_brand === $brand_post_id);
}

// =====================================================
// CAMPAIGN COUNT HELPER
// =====================================================

/**
 * Get count of active campaigns
 */
function royita_get_active_campaign_count(): int {
    $q = new WP_Query([
        'post_type'      => 'royita_campaign',
        'post_status'    => 'publish',
        'posts_per_page' => 1,
        'meta_query'     => [['key' => 'campaign_status', 'value' => 'active', 'compare' => '=']],
        'fields'         => 'ids',
    ]);
    return (int) $q->found_posts;
}
