<?php
/**
 * Royita Header Template
 *
 * @package Royita
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">

    <!-- Mobile Menu Overlay -->
    <div class="royita-mobile-menu__overlay" id="mobileMenuOverlay" role="presentation" aria-hidden="true"></div>

    <!-- Mobile Menu -->
    <nav class="royita-mobile-menu" id="mobileMenu" role="navigation" aria-label="<?php esc_attr_e('منوی موبایل', 'royita'); ?>">
        <button class="royita-mobile-menu__close" id="mobileMenuClose" aria-label="<?php esc_attr_e('بستن منو', 'royita'); ?>" type="button">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>

        <?php
        if (has_nav_menu('mobile')) {
            wp_nav_menu([
                'theme_location' => 'mobile',
                'container'      => false,
                'menu_class'     => 'mobile-nav-list',
                'fallback_cb'    => false,
                'walker'         => new Royita_Mobile_Walker(),
                'depth'          => 2,
            ]);
        } else {
            wp_nav_menu([
                'theme_location' => 'primary',
                'container'      => false,
                'menu_class'     => 'mobile-nav-list',
                'fallback_cb'    => 'royita_menu_fallback',
                'walker'         => new Royita_Mobile_Walker(),
                'depth'          => 2,
            ]);
        }
        ?>

        <?php if (is_user_logged_in()): ?>
            <div class="mobile-menu-user">
                <?php
                $user      = wp_get_current_user();
                $role      = royita_get_user_role();
                $role_labels = ['brand' => 'برند', 'creator' => 'کریتور', 'admin' => 'مدیر'];
                $role_label  = $role_labels[$role] ?? 'کاربر';
                $dash_url    = $role === 'brand' ? home_url('/dashboard-brand/') : home_url('/dashboard-creator/');
                ?>
                <div class="mobile-menu-user__info">
                    <?php echo get_avatar($user->ID, 48, '', '', ['class' => 'mobile-menu-user__avatar']); ?>
                    <div>
                        <div class="font-semibold text-dark"><?php echo esc_html($user->display_name); ?></div>
                        <div class="text-sm text-gray"><?php echo esc_html($role_label); ?></div>
                    </div>
                </div>
                <a href="<?php echo esc_url($dash_url); ?>" class="btn btn-primary btn-block mb-3">داشبورد</a>
                <a href="<?php echo esc_url(wp_logout_url(home_url('/'))); ?>" class="btn btn-outline btn-block">خروج</a>
            </div>
        <?php else: ?>
            <div class="mobile-menu-actions">
                <a href="<?php echo esc_url(wp_login_url()); ?>" class="btn btn-outline btn-block mb-3">ورود</a>
                <a href="<?php echo esc_url(home_url('/register/')); ?>" class="btn btn-cta btn-block">ثبت‌نام رایگان</a>
            </div>
        <?php endif; ?>
    </nav>

    <!-- Header -->
    <header class="royita-header <?php echo is_front_page() ? 'header-transparent' : ''; ?>" id="royitaHeader" role="banner">
        <div class="container">
            <div class="royita-header__inner">

                <!-- Logo -->
                <a href="<?php echo esc_url(home_url('/')); ?>" class="royita-header__logo" aria-label="<?php bloginfo('name'); ?> - <?php bloginfo('description'); ?>">
                    <?php
                    if (has_custom_logo()) {
                        the_custom_logo();
                    } else {
                        echo '<span class="royita-header__logo-text">رویتا</span>';
                    }
                    ?>
                </a>

                <!-- Primary Navigation -->
                <nav class="royita-header__nav" role="navigation" aria-label="<?php esc_attr_e('منوی اصلی', 'royita'); ?>">
                    <?php
                    wp_nav_menu([
                        'theme_location' => 'primary',
                        'container'      => false,
                        'menu_class'     => 'primary-nav',
                        'fallback_cb'    => 'royita_menu_fallback',
                        'depth'          => 2,
                    ]);
                    ?>
                </nav>

                <!-- Header Actions -->
                <div class="royita-header__actions">
                    <?php if (is_user_logged_in()): ?>
                        <?php
                        $user     = wp_get_current_user();
                        $role     = royita_get_user_role();
                        $role_labels = ['brand' => 'برند', 'creator' => 'کریتور', 'admin' => 'مدیر'];
                        $role_label  = $role_labels[$role] ?? 'کاربر';
                        $dash_url    = $role === 'brand' ? home_url('/dashboard-brand/') : home_url('/dashboard-creator/');
                        ?>
                        <a href="<?php echo esc_url($dash_url); ?>" class="royita-header__user" aria-label="<?php echo esc_attr($user->display_name); ?>">
                            <?php echo get_avatar($user->ID, 30, '', '', ['class' => 'royita-header__user-avatar']); ?>
                            <span class="royita-header__user-name"><?php echo esc_html($user->display_name); ?></span>
                            <span class="badge badge-primary" style="font-size:10px"><?php echo esc_html($role_label); ?></span>
                        </a>
                    <?php else: ?>
                        <a href="<?php echo esc_url(wp_login_url()); ?>" class="btn btn-outline btn-sm">
                            ورود
                        </a>
                        <a href="<?php echo esc_url(home_url('/register/')); ?>" class="btn btn-cta btn-sm">
                            شروع کنید
                        </a>
                    <?php endif; ?>

                    <!-- Hamburger Button -->
                    <button class="royita-header__hamburger"
                            id="hamburgerBtn"
                            aria-label="<?php esc_attr_e('بازکردن منو', 'royita'); ?>"
                            aria-expanded="false"
                            aria-controls="mobileMenu"
                            type="button">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                </div>

            </div>
        </div>
    </header>

    <main id="main" class="site-main" role="main">
