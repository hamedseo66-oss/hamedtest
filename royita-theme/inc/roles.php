<?php
/**
 * Royita Custom Roles & Capabilities
 *
 * @package Royita
 */

defined('ABSPATH') || exit;

// =====================================================
// REGISTER CUSTOM ROLES
// =====================================================
add_action('after_setup_theme', 'royita_register_roles');
function royita_register_roles() {

    // Brand Owner Role
    if (!get_role('brand_owner')) {
        add_role('brand_owner', __('صاحب برند', 'royita'), [
            // Standard WP caps
            'read'         => true,
            'edit_posts'   => true,
            'publish_posts'=> true,
            'upload_files' => true,
            'delete_posts' => false,
            // Custom caps
            'manage_campaigns' => true,
            'view_proposals'   => true,
            'hire_creators'    => true,
            'make_payments'    => true,
        ]);
    }

    // Creator User Role
    if (!get_role('creator_user')) {
        add_role('creator_user', __('کریتور', 'royita'), [
            // Standard WP caps
            'read'         => true,
            'upload_files' => true,
            // Custom caps
            'submit_proposals' => true,
            'manage_portfolio' => true,
            'view_earnings'    => true,
            'request_payout'   => true,
        ]);
    }

    // Grant caps to admin
    royita_sync_admin_caps();
}

// =====================================================
// SYNC ADMIN CAPABILITIES
// =====================================================
function royita_sync_admin_caps() {
    static $done = false;
    if ($done) return;
    $done = true;

    $admin = get_role('administrator');
    if (!$admin) return;

    $custom_caps = [
        'manage_campaigns',
        'view_proposals',
        'hire_creators',
        'make_payments',
        'submit_proposals',
        'manage_portfolio',
        'view_earnings',
        'request_payout',
    ];

    foreach ($custom_caps as $cap) {
        if (!isset($admin->capabilities[$cap])) {
            $admin->add_cap($cap, true);
        }
    }
}

// On theme activation, ensure caps are set
add_action('after_switch_theme', function() {
    royita_sync_admin_caps();
    // Reset terms_populated to re-run on next load if needed
    // delete_option('royita_terms_populated');
});

// =====================================================
// CAPABILITY CHECK HELPERS
// =====================================================

/**
 * Check if the current user can manage campaigns
 */
function royita_can_manage_campaigns(): bool {
    return current_user_can('manage_campaigns') || current_user_can('manage_options');
}

/**
 * Check if the current user can submit proposals
 */
function royita_can_submit_proposals(): bool {
    return current_user_can('submit_proposals') || current_user_can('manage_options');
}

/**
 * Check if the current user can view proposals for a campaign
 */
function royita_can_view_proposals(int $campaign_id = 0): bool {
    if (current_user_can('manage_options')) return true;
    if (!current_user_can('view_proposals')) return false;

    // Brand owner can only view their own campaign proposals
    if ($campaign_id > 0) {
        return (int) get_post_field('post_author', $campaign_id) === get_current_user_id();
    }
    return true;
}

/**
 * Check if user can access a specific project
 */
function royita_can_access_project(int $project_id): bool {
    if (current_user_can('manage_options')) return true;

    $user_id    = get_current_user_id();
    $creator_id = (int) get_post_meta($project_id, 'project_creator', true);
    $brand_id   = get_post_field('post_author',
        (int) get_post_meta($project_id, 'project_campaign', true)
    );

    return $user_id === $creator_id || $user_id === (int) $brand_id;
}

/**
 * Ensure a user has a specific role
 */
function royita_require_role(string $role, string $redirect = ''): void {
    if (!is_user_logged_in()) {
        wp_redirect(wp_login_url(get_permalink()));
        exit;
    }

    $user = wp_get_current_user();
    if (!in_array($role, (array) $user->roles, true) && !current_user_can('manage_options')) {
        wp_redirect($redirect ?: home_url('/'));
        exit;
    }
}

/**
 * Require login — redirect to login page if not logged in
 */
function royita_require_login(): void {
    if (!is_user_logged_in()) {
        wp_redirect(wp_login_url(get_permalink()));
        exit;
    }
}

// =====================================================
// REMOVE ADMIN BAR FOR CUSTOM ROLES
// =====================================================
add_action('wp_loaded', 'royita_admin_bar_access');
function royita_admin_bar_access() {
    if (!is_admin() && !current_user_can('manage_options')) {
        add_filter('show_admin_bar', '__return_false');
    }
}

// =====================================================
// REDIRECT DASHBOARD FOR WRONG ROLE
// =====================================================
add_action('template_redirect', 'royita_dashboard_redirect');
function royita_dashboard_redirect() {
    $template = get_page_template_slug();

    if ($template === 'page-templates/page-dashboard-brand.php') {
        royita_require_login();
        if (!royita_is_brand() && !current_user_can('manage_options')) {
            wp_redirect(home_url('/'));
            exit;
        }
    }

    if ($template === 'page-templates/page-dashboard-creator.php') {
        royita_require_login();
        if (!royita_is_creator() && !current_user_can('manage_options')) {
            wp_redirect(home_url('/'));
            exit;
        }
    }
}
