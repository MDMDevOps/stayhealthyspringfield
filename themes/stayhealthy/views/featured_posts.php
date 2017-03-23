<?php
/*
 * Template to extend Better Recent Posts Plugin
 */
?>

<?php if ( $wp_query->have_posts() ) : ?>
	<div class="row  flexwrap recent_posts">
		<?php while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
			<div class="column sm-6 md-3 flexcol">
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'recent-posts-widget' ); ?> itemscope itemtype="http://schema.org/BlogPosting">
					<header class="excerpt-header">
						<figure class="excerpt-thumbnail">
							<a href="<?php the_permalink( ); ?>" rel="bookmark"><?php the_post_thumbnail( 'featured-post-thumbnail' ); ?></a>
							<figcaption>
								<?php //do_action( 'show_single_category' );?>
							</figcaption>
						</figure>
					</header>
					<div class="excerpt-content" itemprop="text articleBody">
						<h5 class="excerpt-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h5>
					</div>
					<footer class="excerpt-footer">
						<a class="button button--block" href="<?php the_permalink(); ?>"><?php _e( 'Read More', 'mpress-child' ); ?></a>
					</footer>
				</article>
			</div>
			<?php ++$count; ?>
		<?php endwhile; ?>
	</div>
<?php endif; ?>