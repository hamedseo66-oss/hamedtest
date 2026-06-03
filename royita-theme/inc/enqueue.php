<?php
/**
 * Royita Enqueue — extra asset management (Google Fonts, RTL, etc.)
 * Note: main enqueuing is in functions.php wp_enqueue_scripts hook.
 *
 * @package Royita
 */

defined('ABSPATH') || exit;

/**
 * Preconnect to Google Fonts for performance
 */
add_action('wp_head', 'royita_google_fonts_preconnect', 1);
function royita_google_fonts_preconnect() {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
}

/**
 * Add font-display: swap to Google Fonts link
 */
add_filter('style_loader_tag', 'royita_font_display_swap', 10, 4);
function royita_font_display_swap($html, $handle, $href, $media) {
    if ($handle === 'royita-fonts') {
        $html = str_replace("rel='stylesheet'", "rel='stylesheet' media='print' onload=\"this.media='all'\"", $html);
        $html .= '<noscript>' . str_replace('media=\'print\' onload="this.media=\'all\'"', '', $html) . '</noscript>';
    }
    return $html;
}

/**
 * Remove emoji scripts (not needed for RTL Persian site)
 */
add_action('init', 'royita_disable_emojis');
function royita_disable_emojis() {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
}

/**
 * Add theme-color meta tag
 */
add_action('wp_head', 'royita_theme_color_meta', 2);
function royita_theme_color_meta() {
    echo '<meta name="theme-color" content="#1A6DC7">' . "\n";
}

/**
 * Enqueue block editor styles
 */
add_action('enqueue_block_editor_assets', 'royita_block_editor_styles');
function royita_block_editor_styles() {
    wp_enqueue_style(
        'royita-editor',
        ROYITA_URI . '/assets/css/editor.css',
        [],
        ROYITA_VERSION
    );
}

/**
 * Remove query strings from static resources for caching
 */
add_filter('script_loader_src', 'royita_remove_query_strings', 15);
add_filter('style_loader_src', 'royita_remove_query_strings', 15);
function royita_remove_query_strings($src) {
    if (strpos($src, '?ver=')) {
        $src = remove_query_arg('ver', $src);
    }
    return $src;
}

/**
 * Inline critical CSS for above-the-fold
 */
add_action('wp_head', 'royita_critical_css', 5);
function royita_critical_css() {
    if (!is_front_page()) return;
    ?>
    <style id="royita-critical">
    body{font-family:'IRANSansX','Vazirmatn',Tahoma,Arial,sans-serif;direction:rtl;margin:0}
    .royita-header{position:fixed;top:0;right:0;left:0;z-index:1020;padding:1rem 0;transition:all 250ms ease}
    .container{max-width:1280px;margin:0 auto;padding:0 1.5rem}
    </style>
    <?php
}
