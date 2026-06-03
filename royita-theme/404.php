<?php
/**
 * 404 Error Page
 *
 * @package Royita
 */

get_header();
?>
<div class="royita-404-wrap container" style="padding-top:8rem;padding-bottom:8rem;text-align:center;">
    <div style="font-size:8rem;font-weight:800;color:#1A6DC7;line-height:1;margin-bottom:1rem;">۴۰۴</div>
    <h1 style="font-size:2rem;margin-bottom:1rem;">صفحه مورد نظر یافت نشد</h1>
    <p style="color:#6B7280;font-size:1.1rem;margin-bottom:2rem;">
        متأسفانه صفحه‌ای که دنبال آن می‌گردید وجود ندارد یا جابجا شده است.
    </p>

    <div style="max-width:480px;margin:0 auto 2rem;">
        <?php get_search_form(); ?>
    </div>

    <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary" style="display:inline-block;padding:.75rem 2rem;background:#1A6DC7;color:#fff;border-radius:8px;text-decoration:none;font-weight:600;">
        بازگشت به خانه
    </a>
</div>
<?php get_footer(); ?>
