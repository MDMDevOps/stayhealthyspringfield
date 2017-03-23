<?php
/**
 * Template part for displaying post excerpts
 * @link https://codex.wordpress.org/Template_Hierarchy
 * @package mpress
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'excerpt' ); ?> itemscope itemtype="http://schema.org/BlogPosting">
	<header class="excerpt-header">
		<?php do_action( 'mpress_entry_title', 'excerpt-title' ); ?>
		<div class="excerpt-meta">
			<?php do_action( 'mpress_entry_meta' ); ?>
		</div>
	</header>
	<div class="excerpt-content" itemprop="text articleBody">
		<?php the_excerpt(); ?>
	</div>
	<footer class="excerpt-footer">
		<?php do_action( 'mpress_entry_meta', array( 'meta_type' => array( 'edit' ) ) ); ?>
	</footer>
</article>