<!DOCTYPE html>
<html <?php language_attributes(); ?> dir="rtl">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="top-bar" class="top-bar">
    <div class="container">
        <div class="top-bar__inner">
            <div class="top-bar__contact">
                <a href="tel:<?php echo physio_phone(); ?>" class="top-bar__item">
                    <?php echo physio_svg('phone'); ?>
                    <span><?php echo physio_phone(); ?></span>
                </a>
                <span class="top-bar__item">
                    <?php echo physio_svg('map'); ?>
                    <span>تهران، خیابان ولیعصر، پلاک ۱۲۳</span>
                </span>
                <span class="top-bar__item">
                    <?php echo physio_svg('clock'); ?>
                    <span>شنبه تا پنج‌شنبه: ۸ تا ۲۰</span>
                </span>
            </div>
            <div class="top-bar__socials">
                <a href="#" aria-label="اینستاگرام" class="top-bar__social">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                </a>
                <a href="#" aria-label="واتساپ" class="top-bar__social">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                </a>
                <a href="#" aria-label="تلگرام" class="top-bar__social">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                </a>
            </div>
        </div>
    </div>
</div>

<header id="site-header" class="site-header">
    <div class="container">
        <div class="site-header__inner">
            <div class="site-header__logo">
                <?php if ( has_custom_logo() ) : ?>
                    <?php the_custom_logo(); ?>
                <?php else : ?>
                    <a href="<?php echo esc_url( home_url('/') ); ?>" class="site-header__logo-text">
                        <span class="logo-icon">
                            <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="24" cy="24" r="23" fill="#0EA5E9" stroke="white" stroke-width="2"/>
                                <path d="M24 10v28M10 24h28" stroke="white" stroke-width="4" stroke-linecap="round"/>
                            </svg>
                        </span>
                        <div>
                            <strong>کلینیک فیزیوتراپی</strong>
                            <small>مرکز تخصصی درمان</small>
                        </div>
                    </a>
                <?php endif; ?>
            </div>

            <nav class="site-header__nav" id="main-nav" aria-label="منوی اصلی">
                <?php
                wp_nav_menu([
                    'theme_location' => 'primary',
                    'menu_class'     => 'nav-menu',
                    'container'      => false,
                    'fallback_cb'    => function() {
                        echo '<ul class="nav-menu">
                            <li><a href="#">خانه</a></li>
                            <li><a href="#">خدمات</a></li>
                            <li><a href="#">تیم درمانی</a></li>
                            <li><a href="#">درباره ما</a></li>
                            <li><a href="#">مقالات</a></li>
                            <li><a href="#">تماس</a></li>
                        </ul>';
                    }
                ]);
                ?>
            </nav>

            <div class="site-header__cta">
                <a href="#appointment" class="btn btn--primary">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    نوبت‌دهی
                </a>
                <button class="hamburger" id="hamburger" aria-label="منو" aria-expanded="false">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </div>
    </div>
</header>
