<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function physio_register_post_types() {
    register_post_type( 'service', [
        'labels'      => [
            'name'          => __( 'خدمات', 'physio-clinic' ),
            'singular_name' => __( 'خدمت', 'physio-clinic' ),
        ],
        'public'      => true,
        'has_archive' => true,
        'supports'    => [ 'title', 'editor', 'thumbnail', 'excerpt' ],
        'menu_icon'   => 'dashicons-heart',
        'rewrite'     => [ 'slug' => 'services' ],
    ] );

    register_post_type( 'doctor', [
        'labels'      => [
            'name'          => __( 'پزشکان', 'physio-clinic' ),
            'singular_name' => __( 'پزشک', 'physio-clinic' ),
        ],
        'public'      => true,
        'has_archive' => true,
        'supports'    => [ 'title', 'editor', 'thumbnail', 'excerpt' ],
        'menu_icon'   => 'dashicons-admin-users',
        'rewrite'     => [ 'slug' => 'doctors' ],
    ] );

    register_post_type( 'testimonial', [
        'labels'      => [
            'name'          => __( 'نظرات بیماران', 'physio-clinic' ),
            'singular_name' => __( 'نظر', 'physio-clinic' ),
        ],
        'public'      => false,
        'show_ui'     => true,
        'supports'    => [ 'title', 'editor' ],
        'menu_icon'   => 'dashicons-format-quote',
    ] );
}
add_action( 'init', 'physio_register_post_types' );
