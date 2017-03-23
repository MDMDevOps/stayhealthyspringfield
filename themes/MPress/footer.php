<?php
/**
 * The template for displaying the footer.
 * Contains the closing of the #content div and all content after.
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 * @package mpress
 */
?>



<footer id="colophon" class="site-footer" role="contentinfo">

	<?php get_template_part( 'views/colophon', apply_filters( 'colophon_type', '' ) ); ?>

</footer>

</div><!-- #page -->

</div> <!-- #page-wrapper -->

<?php wp_footer(); ?>

</body>
</html>
