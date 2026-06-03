<?php
/**
 * Royita Front Page Template
 *
 * @package Royita
 */

get_header();
?>

<div class="front-page">
    <?php
    get_template_part('template-parts/home/hero');
    get_template_part('template-parts/home/how-it-works');
    get_template_part('template-parts/home/active-campaigns');
    get_template_part('template-parts/home/top-creators');
    get_template_part('template-parts/home/stats');
    get_template_part('template-parts/home/testimonials');
    get_template_part('template-parts/home/faq');
    get_template_part('template-parts/home/final-cta');
    ?>
</div>

<?php
get_footer();
