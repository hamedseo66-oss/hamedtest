<?php
/**
 * Generic Page Template — Elementor Compatible
 *
 * @package Royita
 */

get_header();
?>
<div class="royita-page-wrap container" style="padding-top:6rem;padding-bottom:4rem;">
    <?php while (have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class('royita-page'); ?>>
            <?php if (!is_front_page()) : ?>
                <header class="royita-page__header" style="margin-bottom:2rem;">
                    <h1 class="royita-page__title"><?php the_title(); ?></h1>
                </header>
            <?php endif; ?>
            <div class="royita-page__content">
                <?php the_content(); ?>
            </div>
        </article>
    <?php endwhile; ?>
</div>
<?php get_footer(); ?>
