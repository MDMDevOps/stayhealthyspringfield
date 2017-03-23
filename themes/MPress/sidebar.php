<?php
/**
 * The sidebar containing the main widget area.
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 * @package mpress
 */
?>

<?php if ( is_active_sidebar( 'primary-sidebar' ) ) : ?>
    <div class="widget-area secondary sidebar" role="complementary">
        <?php dynamic_sidebar( 'primary-sidebar' ); ?>
    </div>
<?php endif; ?>
