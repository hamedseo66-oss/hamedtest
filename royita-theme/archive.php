<?php
/**
 * Archive / Blog Listing Template
 *
 * @package Royita
 */

get_header();
?>
<div class="royita-archive-wrap container" style="padding-top:6rem;padding-bottom:4rem;">

    <header class="royita-archive-header" style="margin-bottom:3rem;text-align:center;">
        <h1 class="royita-archive-header__title">
            <?php
            if (is_category()) {
                single_cat_title();
            } elseif (is_tag()) {
                single_tag_title();
            } elseif (is_author()) {
                the_author();
            } elseif (is_date()) {
                echo get_the_date('F Y');
            } else {
                echo __('بلاگ', 'royita');
            }
            ?>
        </h1>
        <?php if (get_the_archive_description()) : ?>
            <div class="royita-archive-header__desc" style="margin-top:1rem;color:#6B7280;">
                <?php the_archive_description(); ?>
            </div>
        <?php endif; ?>
    </header>

    <?php if (have_posts()) : ?>
        <div class="royita-archive-grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:2rem;">
            <?php while (have_posts()) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('royita-post-card'); ?> style="border:1px solid #E5E7EB;border-radius:12px;overflow:hidden;background:#fff;">
                    <?php if (has_post_thumbnail()) : ?>
                        <a href="<?php the_permalink(); ?>">
                            <?php the_post_thumbnail('royita-campaign', ['style' => 'width:100%;height:auto;display:block;']); ?>
                        </a>
                    <?php endif; ?>
                    <div style="padding:1.5rem;">
                        <div style="font-size:.8rem;color:#6B7280;margin-bottom:.5rem;">
                            <?php echo get_the_date(); ?> &mdash; <?php the_author(); ?>
                        </div>
                        <h2 style="font-size:1.1rem;margin:0 0 .75rem;">
                            <a href="<?php the_permalink(); ?>" style="color:inherit;text-decoration:none;"><?php the_title(); ?></a>
                        </h2>
                        <div style="color:#6B7280;font-size:.9rem;margin-bottom:1rem;">
                            <?php the_excerpt(); ?>
                        </div>
                        <a href="<?php the_permalink(); ?>" class="btn btn-primary btn-sm">ادامه مطلب</a>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>

        <div class="royita-pagination" style="margin-top:3rem;text-align:center;">
            <?php the_posts_pagination(['mid_size' => 2]); ?>
        </div>

    <?php else : ?>
        <p style="text-align:center;color:#6B7280;"><?php _e('محتوایی یافت نشد.', 'royita'); ?></p>
    <?php endif; ?>

</div>
<?php get_footer(); ?>
