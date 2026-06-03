<?php
/**
 * Single Blog Post Template
 *
 * @package Royita
 */

get_header();
?>
<div class="royita-single-wrap container" style="padding-top:6rem;padding-bottom:4rem;">
    <div class="royita-single-inner">
        <?php while (have_posts()) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('royita-single-post'); ?>>

                <header class="royita-single-post__header" style="margin-bottom:2rem;">
                    <?php
                    $categories = get_the_category();
                    if (!empty($categories)) : ?>
                        <div class="royita-single-post__cats" style="margin-bottom:.5rem;">
                            <?php foreach ($categories as $cat) : ?>
                                <a href="<?php echo esc_url(get_category_link($cat->term_id)); ?>"
                                   class="badge badge-primary" style="margin-left:.25rem;">
                                    <?php echo esc_html($cat->name); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <h1 class="royita-single-post__title"><?php the_title(); ?></h1>

                    <div class="royita-single-post__meta" style="display:flex;gap:1rem;align-items:center;margin-top:1rem;color:#6B7280;font-size:.875rem;">
                        <span><?php echo get_the_date(); ?></span>
                        <span><?php the_author(); ?></span>
                    </div>
                </header>

                <?php if (has_post_thumbnail()) : ?>
                    <div class="royita-single-post__thumbnail" style="margin-bottom:2rem;">
                        <?php the_post_thumbnail('large', ['style' => 'width:100%;height:auto;border-radius:12px;']); ?>
                    </div>
                <?php endif; ?>

                <div class="royita-single-post__content entry-content">
                    <?php the_content(); ?>
                </div>

                <footer class="royita-single-post__footer" style="margin-top:3rem;padding-top:2rem;border-top:1px solid #E5E7EB;">

                    <?php
                    // Author Box
                    $author_id  = get_the_author_meta('ID');
                    $author_bio = get_the_author_meta('description');
                    if ($author_bio) : ?>
                        <div class="royita-author-box" style="display:flex;gap:1.5rem;background:#F9FAFB;border-radius:12px;padding:1.5rem;margin-bottom:2rem;">
                            <div class="royita-author-box__avatar">
                                <?php echo get_avatar($author_id, 80, '', '', ['style' => 'border-radius:50%;']); ?>
                            </div>
                            <div class="royita-author-box__info">
                                <h4 style="margin:0 0 .5rem;"><?php the_author(); ?></h4>
                                <p style="margin:0;color:#6B7280;font-size:.9rem;"><?php echo esc_html($author_bio); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php
                    // Related Posts
                    $cats = wp_get_post_categories(get_the_ID());
                    if (!empty($cats)) :
                        $related = new WP_Query([
                            'post_type'      => 'post',
                            'posts_per_page' => 3,
                            'post__not_in'   => [get_the_ID()],
                            'cat'            => $cats[0],
                        ]);
                        if ($related->have_posts()) : ?>
                            <div class="royita-related-posts">
                                <h3 style="margin-bottom:1.5rem;">مطالب مرتبط</h3>
                                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:1.5rem;">
                                    <?php while ($related->have_posts()) : $related->the_post(); ?>
                                        <div class="royita-related-post-card" style="border:1px solid #E5E7EB;border-radius:12px;overflow:hidden;">
                                            <?php if (has_post_thumbnail()) : ?>
                                                <a href="<?php the_permalink(); ?>">
                                                    <?php the_post_thumbnail('royita-campaign-thumb', ['style' => 'width:100%;height:auto;display:block;']); ?>
                                                </a>
                                            <?php endif; ?>
                                            <div style="padding:1rem;">
                                                <h4 style="font-size:1rem;margin:0 0 .5rem;">
                                                    <a href="<?php the_permalink(); ?>" style="color:inherit;text-decoration:none;"><?php the_title(); ?></a>
                                                </h4>
                                                <p style="font-size:.8rem;color:#6B7280;margin:0;"><?php echo get_the_date(); ?></p>
                                            </div>
                                        </div>
                                    <?php endwhile; wp_reset_postdata(); ?>
                                </div>
                            </div>
                        <?php endif;
                    endif; ?>

                </footer>
            </article>
        <?php endwhile; ?>
    </div>
</div>
<?php get_footer(); ?>
