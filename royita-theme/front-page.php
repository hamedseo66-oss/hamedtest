<?php
/**
 * Royita Front Page — Elementor Compatible
 *
 * @package Royita
 */

get_header();

while (have_posts()) {
    the_post();
    the_content();
}

get_footer();
