<?php
/**
 * Royita Taxonomies
 *
 * @package Royita
 */

defined('ABSPATH') || exit;

add_action('init', 'royita_register_taxonomies', 0);
function royita_register_taxonomies() {

    // =====================================================
    // CAMPAIGN CATEGORY
    // =====================================================
    register_taxonomy('campaign_category', ['royita_campaign'], [
        'labels' => [
            'name'              => __('دسته‌بندی کمپین', 'royita'),
            'singular_name'     => __('دسته‌بندی', 'royita'),
            'search_items'      => __('جستجو دسته‌بندی‌ها', 'royita'),
            'all_items'         => __('همه دسته‌بندی‌ها', 'royita'),
            'parent_item'       => __('دسته‌بندی والد', 'royita'),
            'parent_item_colon' => __('دسته‌بندی والد:', 'royita'),
            'edit_item'         => __('ویرایش دسته‌بندی', 'royita'),
            'update_item'       => __('بروزرسانی دسته‌بندی', 'royita'),
            'add_new_item'      => __('افزودن دسته‌بندی جدید', 'royita'),
            'new_item_name'     => __('نام دسته‌بندی جدید', 'royita'),
            'menu_name'         => __('دسته‌بندی‌ها', 'royita'),
            'not_found'         => __('دسته‌بندی یافت نشد', 'royita'),
        ],
        'hierarchical'      => true,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_rest'      => true,
        'query_var'         => true,
        'rewrite'           => ['slug' => 'campaign-category'],
    ]);

    // =====================================================
    // CAMPAIGN PLATFORM
    // =====================================================
    register_taxonomy('campaign_platform', ['royita_campaign'], [
        'labels' => [
            'name'          => __('پلتفرم', 'royita'),
            'singular_name' => __('پلتفرم', 'royita'),
            'search_items'  => __('جستجو پلتفرم‌ها', 'royita'),
            'all_items'     => __('همه پلتفرم‌ها', 'royita'),
            'edit_item'     => __('ویرایش پلتفرم', 'royita'),
            'update_item'   => __('بروزرسانی پلتفرم', 'royita'),
            'add_new_item'  => __('افزودن پلتفرم جدید', 'royita'),
            'new_item_name' => __('نام پلتفرم جدید', 'royita'),
            'menu_name'     => __('پلتفرم‌ها', 'royita'),
        ],
        'hierarchical'      => false,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_rest'      => true,
        'query_var'         => true,
        'rewrite'           => ['slug' => 'platform'],
    ]);

    // =====================================================
    // CREATOR SPECIALTY
    // =====================================================
    register_taxonomy('creator_specialty', ['royita_creator'], [
        'labels' => [
            'name'          => __('تخصص کریتور', 'royita'),
            'singular_name' => __('تخصص', 'royita'),
            'search_items'  => __('جستجو تخصص‌ها', 'royita'),
            'all_items'     => __('همه تخصص‌ها', 'royita'),
            'edit_item'     => __('ویرایش تخصص', 'royita'),
            'update_item'   => __('بروزرسانی تخصص', 'royita'),
            'add_new_item'  => __('افزودن تخصص جدید', 'royita'),
            'new_item_name' => __('نام تخصص جدید', 'royita'),
            'menu_name'     => __('تخصص‌ها', 'royita'),
        ],
        'hierarchical'      => false,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_rest'      => true,
        'query_var'         => true,
        'rewrite'           => ['slug' => 'specialty'],
    ]);

    // =====================================================
    // CREATOR INDUSTRY
    // =====================================================
    register_taxonomy('creator_industry', ['royita_creator'], [
        'labels' => [
            'name'          => __('صنعت', 'royita'),
            'singular_name' => __('صنعت', 'royita'),
            'search_items'  => __('جستجو صنایع', 'royita'),
            'all_items'     => __('همه صنایع', 'royita'),
            'edit_item'     => __('ویرایش صنعت', 'royita'),
            'update_item'   => __('بروزرسانی صنعت', 'royita'),
            'add_new_item'  => __('افزودن صنعت جدید', 'royita'),
            'new_item_name' => __('نام صنعت جدید', 'royita'),
            'menu_name'     => __('صنایع', 'royita'),
        ],
        'hierarchical'      => false,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_rest'      => true,
        'query_var'         => true,
        'rewrite'           => ['slug' => 'industry'],
    ]);
}

// =====================================================
// PRE-POPULATE TERMS
// =====================================================
add_action('init', 'royita_populate_taxonomy_terms');
function royita_populate_taxonomy_terms() {
    if (get_option('royita_terms_populated')) {
        return;
    }

    // Campaign Categories
    $campaign_categories = [
        'محصول'      => 'product',
        'خدمات'      => 'services',
        'لایف‌استایل' => 'lifestyle',
        'تکنولوژی'   => 'technology',
        'غذا'        => 'food',
        'سلامت'      => 'health',
        'فشن'        => 'fashion',
        'آموزش'      => 'education',
    ];

    foreach ($campaign_categories as $name => $slug) {
        if (!term_exists($name, 'campaign_category')) {
            wp_insert_term($name, 'campaign_category', ['slug' => $slug]);
        }
    }

    // Campaign Platforms
    $platforms = [
        'اینستاگرام' => 'instagram',
        'یوتیوب'     => 'youtube',
        'تیک‌تاک'    => 'tiktok',
        'آپارات'     => 'aparat',
    ];

    foreach ($platforms as $name => $slug) {
        if (!term_exists($name, 'campaign_platform')) {
            wp_insert_term($name, 'campaign_platform', ['slug' => $slug]);
        }
    }

    // Creator Specialties
    $specialties = [
        'ویدیوگرافی'    => 'videography',
        'موشن‌گرافیک'   => 'motion-graphic',
        'انیمیشن'       => 'animation',
        'اینفلوئنسر'    => 'influencer',
        'تدوین'         => 'editing',
    ];

    foreach ($specialties as $name => $slug) {
        if (!term_exists($name, 'creator_specialty')) {
            wp_insert_term($name, 'creator_specialty', ['slug' => $slug]);
        }
    }

    // Creator Industries
    $industries = [
        'آرایشی'    => 'beauty',
        'الکترونیک' => 'electronics',
        'فشن'       => 'fashion',
        'غذا'       => 'food',
        'ورزش'      => 'sports',
        'فین‌تک'    => 'fintech',
        'سلامت'     => 'health',
    ];

    foreach ($industries as $name => $slug) {
        if (!term_exists($name, 'creator_industry')) {
            wp_insert_term($name, 'creator_industry', ['slug' => $slug]);
        }
    }

    update_option('royita_terms_populated', true);
}
