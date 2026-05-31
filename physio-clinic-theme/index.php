<?php get_header(); ?>

<main id="main-content" class="main-content">
    <div class="container">
        <div class="content-area">
            <?php if ( have_posts() ) : ?>
                <div class="posts-grid">
                    <?php while ( have_posts() ) : the_post(); ?>
                    <article <?php post_class('post-card'); ?>>
                        <?php if ( has_post_thumbnail() ) : ?>
                        <a href="<?php the_permalink(); ?>" class="post-card__thumb">
                            <?php the_post_thumbnail('medium_large'); ?>
                        </a>
                        <?php endif; ?>
                        <div class="post-card__body">
                            <div class="post-card__meta">
                                <time><?php echo get_the_date(); ?></time>
                                <span><?php the_category(', '); ?></span>
                            </div>
                            <h2 class="post-card__title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>
                            <p class="post-card__excerpt"><?php the_excerpt(); ?></p>
                            <a href="<?php the_permalink(); ?>" class="btn btn--outline btn--sm">ادامه مطلب</a>
                        </div>
                    </article>
                    <?php endwhile; ?>
                </div>
                <div class="pagination"><?php the_posts_pagination(); ?></div>
            <?php else : ?>
                <p class="no-posts">مطلبی یافت نشد.</p>
            <?php endif; ?>
        </div>
        <aside class="sidebar"><?php dynamic_sidebar('sidebar-1'); ?></aside>
    </div>
</main>

<?php get_footer(); ?>
