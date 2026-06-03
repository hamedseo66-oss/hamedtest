<?php
/**
 * Royita Schema.org Structured Data
 *
 * @package Royita
 */

defined('ABSPATH') || exit;

// =====================================================
// WEBSITE SCHEMA — hooked on wp_head for homepage
// =====================================================
add_action('wp_head', 'royita_schema_website');
function royita_schema_website(): void {
    if (!is_front_page()) return;

    $schema = [
        '@context'        => 'https://schema.org',
        '@type'           => 'WebSite',
        'name'            => get_bloginfo('name'),
        'description'     => get_bloginfo('description'),
        'url'             => home_url('/'),
        'potentialAction' => [
            '@type'       => 'SearchAction',
            'target'      => [
                '@type'       => 'EntryPoint',
                'urlTemplate' => home_url('/?s={search_term_string}'),
            ],
            'query-input' => 'required name=search_term_string',
        ],
    ];

    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
}

// =====================================================
// CREATOR (PERSON) SCHEMA
// =====================================================

/**
 * Output Person schema for a creator post.
 *
 * @param int $post_id Creator post ID
 */
function royita_schema_creator(int $post_id): void {
    $name        = get_the_title($post_id);
    $url         = get_permalink($post_id);
    $rating      = royita_get_creator_rating($post_id);
    $review_count = (int) get_post_meta($post_id, 'review_count', true);
    $image_url   = get_the_post_thumbnail_url($post_id, 'royita-avatar-large');

    $schema = [
        '@context' => 'https://schema.org',
        '@type'    => 'Person',
        'name'     => $name,
        'url'      => $url,
    ];

    if ($image_url) {
        $schema['image'] = $image_url;
    }

    if ($rating > 0 && $review_count > 0) {
        $schema['aggregateRating'] = [
            '@type'       => 'AggregateRating',
            'ratingValue' => $rating,
            'reviewCount' => $review_count,
            'bestRating'  => 5,
            'worstRating' => 1,
        ];
    }

    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
}

// =====================================================
// CAMPAIGN (SERVICE) SCHEMA
// =====================================================

/**
 * Output Service schema for a campaign post.
 *
 * @param int $post_id Campaign post ID
 */
function royita_schema_campaign(int $post_id): void {
    $title      = get_the_title($post_id);
    $url        = get_permalink($post_id);
    $excerpt    = get_the_excerpt($post_id);
    $budget_min = (float) get_post_meta($post_id, 'campaign_budget_min', true);
    $budget_max = (float) get_post_meta($post_id, 'campaign_budget_max', true);
    $deadline   = get_post_meta($post_id, 'campaign_deadline', true);

    $schema = [
        '@context'    => 'https://schema.org',
        '@type'       => 'Service',
        'name'        => $title,
        'url'         => $url,
        'description' => $excerpt,
        'provider'    => [
            '@type' => 'Organization',
            'name'  => get_bloginfo('name'),
            'url'   => home_url('/'),
        ],
    ];

    if ($budget_min > 0 || $budget_max > 0) {
        $schema['offers'] = [
            '@type'         => 'Offer',
            'priceCurrency' => 'IRR',
            'priceSpecification' => [
                '@type'    => 'PriceSpecification',
                'minPrice' => $budget_min ?: null,
                'maxPrice' => $budget_max ?: null,
            ],
        ];
    }

    if ($deadline) {
        $schema['validThrough'] = str_replace('/', '-', $deadline);
    }

    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
}

// =====================================================
// FAQ PAGE SCHEMA
// =====================================================

/**
 * Output FAQPage schema.
 *
 * @param array $faqs Array of ['question' => string, 'answer' => string]
 */
function royita_schema_faq(array $faqs): void {
    if (empty($faqs)) return;

    $entities = [];
    foreach ($faqs as $faq) {
        if (empty($faq['question']) || empty($faq['answer'])) continue;
        $entities[] = [
            '@type'          => 'Question',
            'name'           => $faq['question'],
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text'  => $faq['answer'],
            ],
        ];
    }

    if (empty($entities)) return;

    $schema = [
        '@context'   => 'https://schema.org',
        '@type'      => 'FAQPage',
        'mainEntity' => $entities,
    ];

    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
}
