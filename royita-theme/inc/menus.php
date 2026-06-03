<?php
/**
 * Royita Menus & Walker
 *
 * @package Royita
 */

defined('ABSPATH') || exit;

/**
 * Custom Walker for mobile menu with Persian aria-labels
 */
class Royita_Mobile_Walker extends Walker_Nav_Menu {

    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';
        $classes = empty($item->classes) ? [] : (array) $item->classes;
        $classes[] = 'mobile-menu-item';

        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';

        $output .= $indent . '<li' . $id . $class_names . '>';

        $atts = [];
        $atts['title']  = !empty($item->attr_title) ? $item->attr_title : '';
        $atts['target'] = !empty($item->target) ? $item->target : '';
        $atts['rel']    = !empty($item->xfn) ? $item->xfn : '';
        $atts['href']   = !empty($item->url) ? $item->url : '';
        $atts['aria-label'] = esc_attr($item->title) . ' - ' . __('منو', 'royita');

        if (!empty($atts['target']) && '_blank' === $atts['target']) {
            $atts['rel'] = 'noopener noreferrer';
        }

        $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args, $depth);

        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (!empty($value)) {
                $value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        $item_output = isset($args->before) ? $args->before : '';
        $item_output .= '<a' . $attributes . '>';
        $item_output .= (isset($args->link_before) ? $args->link_before : '') . apply_filters('the_title', $item->title, $item->ID) . (isset($args->link_after) ? $args->link_after : '');
        $item_output .= '</a>';
        $item_output .= isset($args->after) ? $args->after : '';

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
}

/**
 * Add active class to menu items for current page
 */
add_filter('nav_menu_css_class', 'royita_menu_active_class', 10, 4);
function royita_menu_active_class($classes, $item, $args, $depth) {
    if (isset($args->theme_location)) {
        if (in_array('current-menu-item', $classes, true)) {
            $classes[] = 'is-active';
        }
        if (in_array('current-menu-ancestor', $classes, true)) {
            $classes[] = 'is-active-parent';
        }
    }
    return $classes;
}

/**
 * Fallback for when no menu is assigned
 */
function royita_menu_fallback($args) {
    $defaults = [
        'container'       => 'ul',
        'container_class' => 'nav-menu nav-menu-fallback',
        'depth'           => 2,
        'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
    ];

    extract($defaults);

    echo '<ul class="' . esc_attr($container_class) . '">';
    echo '<li><a href="' . esc_url(home_url('/')) . '">' . esc_html__('خانه', 'royita') . '</a></li>';

    if (is_user_logged_in()) {
        $role = royita_get_user_role();
        if ($role === 'brand') {
            echo '<li><a href="' . esc_url(home_url('/dashboard-brand/')) . '">' . esc_html__('داشبورد', 'royita') . '</a></li>';
        } elseif ($role === 'creator') {
            echo '<li><a href="' . esc_url(home_url('/dashboard-creator/')) . '">' . esc_html__('داشبورد', 'royita') . '</a></li>';
        }
        echo '<li><a href="' . esc_url(wp_logout_url(home_url('/'))) . '">' . esc_html__('خروج', 'royita') . '</a></li>';
    } else {
        echo '<li><a href="' . esc_url(wp_login_url()) . '">' . esc_html__('ورود', 'royita') . '</a></li>';
    }

    echo '</ul>';
}
