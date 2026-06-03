<?php
/**
 * Royita Custom Post Types
 *
 * @package Royita
 */

defined('ABSPATH') || exit;

add_action('init', 'royita_register_post_types', 0);
function royita_register_post_types() {

    // =====================================================
    // 1. CAMPAIGN (کمپین)
    // =====================================================
    register_post_type('royita_campaign', [
        'labels' => [
            'name'               => __('کمپین‌ها', 'royita'),
            'singular_name'      => __('کمپین', 'royita'),
            'add_new'            => __('افزودن کمپین', 'royita'),
            'add_new_item'       => __('افزودن کمپین جدید', 'royita'),
            'edit_item'          => __('ویرایش کمپین', 'royita'),
            'new_item'           => __('کمپین جدید', 'royita'),
            'view_item'          => __('مشاهده کمپین', 'royita'),
            'search_items'       => __('جستجو کمپین‌ها', 'royita'),
            'not_found'          => __('کمپینی یافت نشد', 'royita'),
            'not_found_in_trash' => __('کمپینی در سطل زباله یافت نشد', 'royita'),
            'parent_item_colon'  => __('کمپین والد:', 'royita'),
            'menu_name'          => __('کمپین‌ها', 'royita'),
            'all_items'          => __('همه کمپین‌ها', 'royita'),
        ],
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_rest'       => true,
        'query_var'          => true,
        'rewrite'            => [
            'slug'       => 'کمپین',
            'with_front' => false,
            'feeds'      => false,
        ],
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-megaphone',
        'supports'           => ['title', 'thumbnail', 'author', 'revisions'],
        'taxonomies'         => ['campaign_category', 'campaign_platform'],
        'show_in_nav_menus'  => true,
        'delete_with_user'   => false,
    ]);

    // =====================================================
    // 2. CREATOR (کریتور)
    // =====================================================
    register_post_type('royita_creator', [
        'labels' => [
            'name'               => __('کریتورها', 'royita'),
            'singular_name'      => __('کریتور', 'royita'),
            'add_new'            => __('افزودن کریتور', 'royita'),
            'add_new_item'       => __('افزودن کریتور جدید', 'royita'),
            'edit_item'          => __('ویرایش کریتور', 'royita'),
            'new_item'           => __('کریتور جدید', 'royita'),
            'view_item'          => __('مشاهده کریتور', 'royita'),
            'search_items'       => __('جستجو کریتورها', 'royita'),
            'not_found'          => __('کریتوری یافت نشد', 'royita'),
            'not_found_in_trash' => __('کریتوری در سطل زباله یافت نشد', 'royita'),
            'menu_name'          => __('کریتورها', 'royita'),
            'all_items'          => __('همه کریتورها', 'royita'),
        ],
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_rest'       => true,
        'query_var'          => true,
        'rewrite'            => [
            'slug'       => 'کریتور',
            'with_front' => false,
        ],
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 6,
        'menu_icon'          => 'dashicons-video-alt3',
        'supports'           => ['title', 'thumbnail', 'author', 'revisions'],
        'taxonomies'         => ['creator_specialty', 'creator_industry'],
        'show_in_nav_menus'  => true,
    ]);

    // =====================================================
    // 3. BRAND (برند) — not public
    // =====================================================
    register_post_type('royita_brand', [
        'labels' => [
            'name'               => __('برندها', 'royita'),
            'singular_name'      => __('برند', 'royita'),
            'add_new'            => __('افزودن برند', 'royita'),
            'add_new_item'       => __('افزودن برند جدید', 'royita'),
            'edit_item'          => __('ویرایش برند', 'royita'),
            'new_item'           => __('برند جدید', 'royita'),
            'view_item'          => __('مشاهده برند', 'royita'),
            'search_items'       => __('جستجو برندها', 'royita'),
            'not_found'          => __('برندی یافت نشد', 'royita'),
            'not_found_in_trash' => __('برندی در سطل زباله یافت نشد', 'royita'),
            'menu_name'          => __('برندها', 'royita'),
            'all_items'          => __('همه برندها', 'royita'),
        ],
        'public'             => false,
        'publicly_queryable' => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_rest'       => true,
        'query_var'          => false,
        'rewrite'            => false,
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => 7,
        'menu_icon'          => 'dashicons-store',
        'supports'           => ['title', 'thumbnail', 'author'],
    ]);

    // =====================================================
    // 4. PROPOSAL (پیشنهاد) — not public
    // =====================================================
    register_post_type('royita_proposal', [
        'labels' => [
            'name'               => __('پیشنهادها', 'royita'),
            'singular_name'      => __('پیشنهاد', 'royita'),
            'add_new'            => __('افزودن پیشنهاد', 'royita'),
            'add_new_item'       => __('افزودن پیشنهاد جدید', 'royita'),
            'edit_item'          => __('ویرایش پیشنهاد', 'royita'),
            'new_item'           => __('پیشنهاد جدید', 'royita'),
            'view_item'          => __('مشاهده پیشنهاد', 'royita'),
            'search_items'       => __('جستجو پیشنهادها', 'royita'),
            'not_found'          => __('پیشنهادی یافت نشد', 'royita'),
            'not_found_in_trash' => __('پیشنهادی در سطل زباله یافت نشد', 'royita'),
            'menu_name'          => __('پیشنهادها', 'royita'),
            'all_items'          => __('همه پیشنهادها', 'royita'),
        ],
        'public'             => false,
        'publicly_queryable' => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_rest'       => true,
        'query_var'          => false,
        'rewrite'            => false,
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => 8,
        'menu_icon'          => 'dashicons-clipboard',
        'supports'           => ['title', 'author'],
    ]);

    // =====================================================
    // 5. PROJECT (پروژه) — not public
    // =====================================================
    register_post_type('royita_project', [
        'labels' => [
            'name'               => __('پروژه‌ها', 'royita'),
            'singular_name'      => __('پروژه', 'royita'),
            'add_new'            => __('افزودن پروژه', 'royita'),
            'add_new_item'       => __('افزودن پروژه جدید', 'royita'),
            'edit_item'          => __('ویرایش پروژه', 'royita'),
            'new_item'           => __('پروژه جدید', 'royita'),
            'view_item'          => __('مشاهده پروژه', 'royita'),
            'search_items'       => __('جستجو پروژه‌ها', 'royita'),
            'not_found'          => __('پروژه‌ای یافت نشد', 'royita'),
            'not_found_in_trash' => __('پروژه‌ای در سطل زباله یافت نشد', 'royita'),
            'menu_name'          => __('پروژه‌ها', 'royita'),
            'all_items'          => __('همه پروژه‌ها', 'royita'),
        ],
        'public'             => false,
        'publicly_queryable' => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_rest'       => true,
        'query_var'          => false,
        'rewrite'            => false,
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => 9,
        'menu_icon'          => 'dashicons-portfolio',
        'supports'           => ['title', 'author'],
    ]);

    // =====================================================
    // 6. REVIEW (نظر) — not public
    // =====================================================
    register_post_type('royita_review', [
        'labels' => [
            'name'               => __('نظرات', 'royita'),
            'singular_name'      => __('نظر', 'royita'),
            'add_new'            => __('افزودن نظر', 'royita'),
            'add_new_item'       => __('افزودن نظر جدید', 'royita'),
            'edit_item'          => __('ویرایش نظر', 'royita'),
            'new_item'           => __('نظر جدید', 'royita'),
            'view_item'          => __('مشاهده نظر', 'royita'),
            'search_items'       => __('جستجو نظرات', 'royita'),
            'not_found'          => __('نظری یافت نشد', 'royita'),
            'not_found_in_trash' => __('نظری در سطل زباله یافت نشد', 'royita'),
            'menu_name'          => __('نظرات', 'royita'),
            'all_items'          => __('همه نظرات', 'royita'),
        ],
        'public'             => false,
        'publicly_queryable' => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_rest'       => true,
        'query_var'          => false,
        'rewrite'            => false,
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => 10,
        'menu_icon'          => 'dashicons-star-filled',
        'supports'           => ['title', 'author'],
    ]);

    // =====================================================
    // 7. TESTIMONIAL (توصیه) — public
    // =====================================================
    register_post_type('royita_testimonial', [
        'labels' => [
            'name'               => __('توصیه‌نامه‌ها', 'royita'),
            'singular_name'      => __('توصیه‌نامه', 'royita'),
            'add_new'            => __('افزودن توصیه‌نامه', 'royita'),
            'add_new_item'       => __('افزودن توصیه‌نامه جدید', 'royita'),
            'edit_item'          => __('ویرایش توصیه‌نامه', 'royita'),
            'new_item'           => __('توصیه‌نامه جدید', 'royita'),
            'view_item'          => __('مشاهده توصیه‌نامه', 'royita'),
            'search_items'       => __('جستجو توصیه‌نامه‌ها', 'royita'),
            'not_found'          => __('توصیه‌نامه‌ای یافت نشد', 'royita'),
            'not_found_in_trash' => __('توصیه‌نامه‌ای در سطل زباله یافت نشد', 'royita'),
            'menu_name'          => __('توصیه‌نامه‌ها', 'royita'),
            'all_items'          => __('همه توصیه‌نامه‌ها', 'royita'),
        ],
        'public'             => true,
        'publicly_queryable' => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_rest'       => true,
        'query_var'          => false,
        'rewrite'            => [
            'slug'       => 'توصیه',
            'with_front' => false,
        ],
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => 11,
        'menu_icon'          => 'dashicons-format-quote',
        'supports'           => ['title', 'thumbnail', 'author', 'page-attributes'],
    ]);
}
