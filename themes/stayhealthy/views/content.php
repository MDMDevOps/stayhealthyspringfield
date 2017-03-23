<?php
/**
 * Template part for displaying content
 * @link https://codex.wordpress.org/Template_Hierarchy
 * @package mpress
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope itemtype="http://schema.org/BlogPosting">
	<header class="entry-header">
		<?php do_action( 'mpress_entry_title', 'entry-title' ); ?>
		<?php if( is_single() ) : ?>
			<div class="entry-meta">
				<?php do_action( 'mpress_entry_meta', array( 'meta_type' => array( 'date', 'categories', 'post_tags', 'comments', 'edit' ) ) ); ?>
			</div>
		<?php endif; ?>
	</header>
	<div class="entry-content" itemprop="text articleBody">
		<?php the_content(); ?>
	</div>
	<footer class="entry-footer">
		<?php do_action( 'mpress_entry_meta', array( 'meta_type' => array( 'edit' ) ) ); ?>
	</footer>
</article>
<aside id="after-content-widgets"><?php get_sidebar( 'after-content' ); ?></aside>