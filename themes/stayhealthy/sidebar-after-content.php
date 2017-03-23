<?php
/**
 * Widget area that appears after the content area
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 * @package mpress
 */
?>
<?php if ( is_active_sidebar( 'after-content-widget-area' ) ) : ?>
    <div class="widget-area secondary sidebar" role="complementary">
        <?php dynamic_sidebar( 'after-content-widget-area' ); ?>
    </div>
<?php endif; ?>