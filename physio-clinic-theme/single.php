<?php get_header(); ?>

<main id="main-content" class="main-content">
    <div class="container">
        <article <?php post_class('single-post'); ?>>
            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
            <header class="single-post__header">
                <h1 class="single-post__title"><?php the_title(); ?></h1>
                <div class="single-post__meta">
                    <time><?php echo get_the_date(); ?></time>
                    <span><?php the_author(); ?></span>
                    <span><?php the_category(', '); ?></span>
                </div>
            </header>
            <?php if ( has_post_thumbnail() ) : ?>
            <div class="single-post__thumb"><?php the_post_thumbnail('large'); ?></div>
            <?php endif; ?>
            <div class="single-post__content"><?php the_content(); ?></div>
            <?php endwhile; endif; ?>
        </article>
        <aside class="sidebar"><?php dynamic_sidebar('sidebar-1'); ?></aside>
    </div>
</main>

<?php get_footer(); ?>
