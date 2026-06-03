<?php
/**
 * Royita Theme - functions.php
 * Child theme of Hello Elementor
 *
 * @package Royita
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

define('ROYITA_VERSION', '1.0.0');
define('ROYITA_DIR', get_stylesheet_directory());
define('ROYITA_URI', get_stylesheet_directory_uri());

// =====================================================
// THEME SETUP
// =====================================================
add_action('after_setup_theme', 'royita_setup');
function royita_setup() {
    load_textdomain('royita', ROYITA_DIR . '/languages');

    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', [
        'search-form', 'comment-form', 'comment-list',
        'gallery', 'caption', 'style', 'script',
    ]);
    add_theme_support('custom-logo', [
        'height'      => 60,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ]);
    add_theme_support('customize-selective-refresh-widgets');
    add_theme_support('responsive-embeds');
    add_theme_support('align-wide');

    register_nav_menus([
        'primary' => __('منوی اصلی', 'royita'),
        'footer'  => __('منوی فوتر', 'royita'),
        'mobile'  => __('منوی موبایل', 'royita'),
    ]);

    // Image Sizes
    add_image_size('royita-avatar',        150, 150, true);
    add_image_size('royita-avatar-large',  300, 300, true);
    add_image_size('royita-campaign',      800, 450, true);
    add_image_size('royita-campaign-thumb',400, 225, true);
    add_image_size('royita-portfolio',     600, 400, true);
    add_image_size('royita-logo',          200, 80, false);
    add_image_size('royita-brand-logo',    100, 100, true);
}

// =====================================================
// ENQUEUE SCRIPTS & STYLES
// =====================================================
add_action('wp_enqueue_scripts', 'royita_enqueue_assets');
function royita_enqueue_assets() {
    // Parent theme stylesheet
    wp_enqueue_style(
        'hello-elementor-style',
        get_template_directory_uri() . '/style.css',
        [],
        wp_get_theme('hello-elementor')->get('Version')
    );

    // Child theme stylesheet
    wp_enqueue_style(
        'royita-style',
        get_stylesheet_uri(),
        ['hello-elementor-style'],
        ROYITA_VERSION
    );

    // Main CSS
    wp_enqueue_style(
        'royita-main',
        ROYITA_URI . '/assets/css/main.css',
        ['royita-style'],
        ROYITA_VERSION
    );

    // RTL support
    if (is_rtl()) {
        wp_enqueue_style(
            'royita-rtl',
            ROYITA_URI . '/assets/css/rtl.css',
            ['royita-main'],
            ROYITA_VERSION
        );
    }

    // Main JS
    wp_enqueue_script(
        'royita-main',
        ROYITA_URI . '/assets/js/main.js',
        [],
        ROYITA_VERSION,
        true
    );

    wp_localize_script('royita-main', 'royitaVars', [
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('royita_nonce'),
        'homeUrl' => home_url('/'),
        'strings' => [
            'loading'        => __('در حال بارگذاری...', 'royita'),
            'loadMore'       => __('بارگذاری بیشتر', 'royita'),
            'noMore'         => __('محتوای بیشتری وجود ندارد', 'royita'),
            'error'          => __('خطایی رخ داد. لطفاً دوباره تلاش کنید.', 'royita'),
            'success'        => __('عملیات با موفقیت انجام شد', 'royita'),
            'confirmDelete'  => __('آیا از حذف این مورد اطمینان دارید؟', 'royita'),
            'favoriteAdd'    => __('به علاقه‌مندی‌ها اضافه شد', 'royita'),
            'favoriteRemove' => __('از علاقه‌مندی‌ها حذف شد', 'royita'),
            'loginRequired'  => __('برای این عملیات باید وارد شوید', 'royita'),
        ],
        'isLoggedIn' => is_user_logged_in(),
        'userRole'   => royita_get_user_role(),
    ]);

    // Dashboard JS — conditional
    $dashboard_templates = [
        'page-templates/page-dashboard-brand.php',
        'page-templates/page-dashboard-creator.php',
    ];

    $current_template = get_page_template_slug();
    if (in_array($current_template, $dashboard_templates, true)) {
        wp_enqueue_script(
            'royita-dashboard',
            ROYITA_URI . '/assets/js/dashboard.js',
            ['royita-main'],
            ROYITA_VERSION,
            true
        );
        wp_localize_script('royita-dashboard', 'royitaDashboard', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('royita_dashboard_nonce'),
        ]);
    }

    // Google Fonts (Vazirmatn as RTL fallback)
    wp_enqueue_style(
        'royita-fonts',
        'https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700;800&display=swap',
        [],
        null
    );
}

// =====================================================
// BODY CLASSES
// =====================================================
add_filter('body_class', 'royita_body_classes');
function royita_body_classes($classes) {
    $role = royita_get_user_role();
    if ($role !== 'guest') {
        $classes[] = 'role-' . $role;
    }
    if (is_front_page()) {
        $classes[] = 'is-front-page';
    }
    $classes[] = 'royita-site';
    return $classes;
}

// =====================================================
// ELEMENTOR WIDGET CATEGORIES
// =====================================================
add_action('elementor/elements/categories_registered', 'royita_register_elementor_categories');
function royita_register_elementor_categories($elements_manager) {
    $elements_manager->add_category('royita-widgets', [
        'title' => __('رویتا', 'royita'),
        'icon'  => 'fa fa-plug',
    ]);
}

// =====================================================
// INCLUDE FILES
// =====================================================
$royita_includes = [
    '/inc/helpers.php',
    '/inc/post-types.php',
    '/inc/taxonomies.php',
    '/inc/roles.php',
    '/inc/acf-fields.php',
    '/inc/enqueue.php',
    '/inc/menus.php',
    '/inc/ajax-handlers.php',
    '/inc/notifications.php',
];

foreach ($royita_includes as $file) {
    $path = ROYITA_DIR . $file;
    if (file_exists($path)) {
        require_once $path;
    }
}

// =====================================================
// ADMIN BAR: hide for non-admins on front
// =====================================================
add_action('after_setup_theme', 'royita_admin_bar');
function royita_admin_bar() {
    if (!current_user_can('manage_options') && !is_admin()) {
        show_admin_bar(false);
    }
}

// =====================================================
// DISABLE COMMENTS (optional - marketplace doesn't need)
// =====================================================
add_action('init', 'royita_disable_comments');
function royita_disable_comments() {
    // Keep comments on only for testimonials / reviews via ACF
    $post_types = get_post_types();
    foreach ($post_types as $post_type) {
        if (post_type_supports($post_type, 'comments')) {
            remove_post_type_support($post_type, 'comments');
            remove_post_type_support($post_type, 'trackbacks');
        }
    }
}

// =====================================================
// EXCERPT LENGTH
// =====================================================
add_filter('excerpt_length', function() { return 25; });
add_filter('excerpt_more', function() { return '...'; });

// =====================================================
// ALLOW SVG UPLOADS
// =====================================================
add_filter('upload_mimes', 'royita_allow_svg');
function royita_allow_svg($mimes) {
    if (current_user_can('manage_options')) {
        $mimes['svg'] = 'image/svg+xml';
    }
    return $mimes;
}

// =====================================================
// DEFER NON-CRITICAL SCRIPTS
// =====================================================
add_filter('script_loader_tag', 'royita_defer_scripts', 10, 3);
function royita_defer_scripts($tag, $handle, $src) {
    $defer_scripts = ['royita-main', 'royita-dashboard'];
    if (in_array($handle, $defer_scripts, true)) {
        return '<script src="' . esc_url($src) . '" defer></script>' . "\n";
    }
    return $tag;
}

// =====================================================
// REDIRECT AFTER LOGIN
// =====================================================
add_filter('login_redirect', 'royita_login_redirect', 10, 3);
function royita_login_redirect($redirect_to, $request, $user) {
    if (isset($user->roles) && is_array($user->roles)) {
        if (in_array('brand_owner', $user->roles, true)) {
            return home_url('/dashboard-brand/');
        }
        if (in_array('creator_user', $user->roles, true)) {
            return home_url('/dashboard-creator/');
        }
    }
    return $redirect_to;
}

// =====================================================
// CUSTOM LOGIN PAGE STYLES
// =====================================================
add_action('login_enqueue_scripts', 'royita_login_styles');
function royita_login_styles() {
    wp_enqueue_style(
        'royita-login',
        ROYITA_URI . '/assets/css/login.css',
        [],
        ROYITA_VERSION
    );
}
add_filter('login_headerurl', function() { return home_url(); });
add_filter('login_headertext', function() { return get_bloginfo('name'); });
