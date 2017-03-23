<?php
/**
 * The template for displaying the footer.
 * Contains the closing of the #content div and all content after.
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 * @package mpress
 */
?>

<div class="scrollwrapper clearfix">
	<a id="scrolltop" class="jumpscroll scrolltoggle" data-trigger="body" data-trigger-offset="-200" href="#"><?php do_action( 'mpress_icon', 'scrolltop' ); ?><span class="screen-reader-text">Scroll to Top</span></a>
</div>


<footer id="colophon" class="site-footer" role="contentinfo">

	<div class="wrapper">
		<div class="row">
			<?php if ( is_active_sidebar( 'footer-left-column' ) ) : ?>
				<div class="widget-area secondary column md-4" role="complementary">
					<?php dynamic_sidebar( 'footer-left-column' ); ?>
				</div>
			<?php endif; ?>
			<?php if ( is_active_sidebar( 'footer-center-column' ) ) : ?>
				<div class="widget-area secondary column md-4" role="complementary">
					<?php dynamic_sidebar( 'footer-center-column' ); ?>
				</div>
			<?php endif; ?>
			<?php if ( is_active_sidebar( 'footer-right-column' ) ) : ?>
				<div class="widget-area secondary column md-4" role="complementary">
					<?php dynamic_sidebar( 'footer-right-column' ); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>

	<div class="site-info">
		<div class="wrapper">
			<?php do_action( 'copyright_message' ); ?>
		</div>
	</div>



</footer>

</div><!-- #page -->

</div> <!-- #page-wrapper -->

<?php wp_footer(); ?>

</body>
</html>