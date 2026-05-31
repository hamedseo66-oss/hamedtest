<?php
if ( ! defined( 'ABSPATH' ) ) exit;

define( 'PHYSIO_VERSION', '1.0.0' );
define( 'PHYSIO_DIR', get_template_directory() );
define( 'PHYSIO_URI', get_template_directory_uri() );

function physio_setup() {
    load_theme_textdomain( 'physio-clinic', PHYSIO_DIR . '/languages' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [ 'search-form', 'comment-form', 'gallery', 'caption' ] );
    add_theme_support( 'custom-logo', [
        'height'      => 80,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ] );

    register_nav_menus( [
        'primary' => __( 'منوی اصلی', 'physio-clinic' ),
        'footer'  => __( 'منوی فوتر', 'physio-clinic' ),
    ] );
}
add_action( 'after_setup_theme', 'physio_setup' );

function physio_enqueue_assets() {
    wp_enqueue_style( 'google-fonts', 'https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700;800&display=swap', [], null );
    wp_enqueue_style( 'physio-main', PHYSIO_URI . '/assets/css/main.css', [], PHYSIO_VERSION );
    wp_enqueue_script( 'physio-main', PHYSIO_URI . '/assets/js/main.js', [], PHYSIO_VERSION, true );
}
add_action( 'wp_enqueue_scripts', 'physio_enqueue_assets' );

function physio_widgets_init() {
    register_sidebar( [
        'name'          => __( 'ستون کناری', 'physio-clinic' ),
        'id'            => 'sidebar-1',
        'before_widget' => '<div class="widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ] );
    register_sidebar( [
        'name'          => __( 'فوتر ستون ۱', 'physio-clinic' ),
        'id'            => 'footer-1',
        'before_widget' => '<div class="footer-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="footer-widget-title">',
        'after_title'   => '</h4>',
    ] );
    register_sidebar( [
        'name'          => __( 'فوتر ستون ۲', 'physio-clinic' ),
        'id'            => 'footer-2',
        'before_widget' => '<div class="footer-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="footer-widget-title">',
        'after_title'   => '</h4>',
    ] );
    register_sidebar( [
        'name'          => __( 'فوتر ستون ۳', 'physio-clinic' ),
        'id'            => 'footer-3',
        'before_widget' => '<div class="footer-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="footer-widget-title">',
        'after_title'   => '</h4>',
    ] );
}
add_action( 'widgets_init', 'physio_widgets_init' );

function physio_customizer( $wp_customize ) {
    $wp_customize->add_section( 'physio_hero', [
        'title'    => __( 'بخش هیرو', 'physio-clinic' ),
        'priority' => 30,
    ] );
    $wp_customize->add_setting( 'hero_title', [ 'default' => 'سلامتی شما، اولویت ماست' ] );
    $wp_customize->add_control( 'hero_title', [
        'label'   => __( 'عنوان هیرو', 'physio-clinic' ),
        'section' => 'physio_hero',
        'type'    => 'text',
    ] );
    $wp_customize->add_setting( 'hero_subtitle', [ 'default' => 'کلینیک تخصصی فیزیوتراپی با بهره‌گیری از پیشرفته‌ترین روش‌های درمانی' ] );
    $wp_customize->add_control( 'hero_subtitle', [
        'label'   => __( 'زیرعنوان هیرو', 'physio-clinic' ),
        'section' => 'physio_hero',
        'type'    => 'textarea',
    ] );
    $wp_customize->add_setting( 'phone_number', [ 'default' => '021-88888888' ] );
    $wp_customize->add_control( 'phone_number', [
        'label'   => __( 'شماره تماس', 'physio-clinic' ),
        'section' => 'physio_hero',
        'type'    => 'text',
    ] );
}
add_action( 'customize_register', 'physio_customizer' );

require_once PHYSIO_DIR . '/inc/post-types.php';
require_once PHYSIO_DIR . '/inc/helpers.php';
