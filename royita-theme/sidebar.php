<?php
/**
 * Sidebar Template
 *
 * @package Royita
 */

if (is_active_sidebar('main-sidebar')) : ?>
    <aside class="royita-sidebar" role="complementary">
        <?php dynamic_sidebar('main-sidebar'); ?>
    </aside>
<?php endif;
