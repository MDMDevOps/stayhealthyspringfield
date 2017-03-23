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
		<div class="entry-meta">
			<?php do_action( 'mpress_entry_meta' ); ?>
		</div>
	</header>
	<div class="entry-content" itemprop="text articleBody">
		<?php the_content(); ?>
	</div>
	<footer class="entry-footer">
		<?php do_action( 'mpress_entry_meta', array( 'meta_type' => array( 'edit' ) ) ); ?>
	</footer>
</article>