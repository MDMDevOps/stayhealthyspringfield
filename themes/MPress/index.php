<?php
/**
 * The main template file.
 * This is the most generic template file in a WordPress theme
 * It is used to display a page when nothing more specific matches a query.
 * @link https://codex.wordpress.org/Template_Hierarchy
 * @package mpress
 */
?>

<?php get_header(); ?>

<div id="primary" class="content-area row">
	<main id="main" class="site-main column sm-8" role="main">
	<?php
		/**
		 * Begin standard wordpress loop, first checking if we have posts to show
		 */
		if( have_posts() ) :
			while( have_posts() ) : the_post();
				/**
				 * Check if we are displaying a single post/page/attachment or an archive
				 * This part could be reused for single.php, page.php
				 */
				if( is_singular() ) :
					get_template_part( 'views/content', get_post_type() );
					the_post_navigation();
					if ( comments_open() || get_comments_number() ) : comments_template(); endif;
				else :
					/**
					 * Archive type + pagination for archive view
					 * This part could be used for archive.php, search.php, etc
					 */
					get_template_part( sprintf( 'views/%s', get_theme_mod( 'mpress_archive_type', 'content' ) ), get_post_type() );
				endif;
			endwhile;
			the_posts_pagination(
				array(
					'mid_size' => 2,
					'prev_text' => __( '<span class="icon fa fa-angle-left"></span><span class="screen-reader-text">Previous Page</span>', 'textdomain' ),
					'next_text' => __( '<span class="icon fa fa-angle-right"></span><span class="screen-reader-text">Next Page</span>', 'textdomain' ),
				)
			);
		/**
		 * Fallback for if we have no posts to show
		 */
		else:
			get_template_part( 'views/none' );
		endif;
	?>
	</main>
	<aside id="sidebar" class="column sm-4">
		<?php get_sidebar(); ?>
	</aside>
</div>
<?php get_footer(); ?>
