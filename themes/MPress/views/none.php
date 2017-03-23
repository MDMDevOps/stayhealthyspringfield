<?php
/**
 * Template part for displaying a message that posts cannot be found.
 * @link https://codex.wordpress.org/Template_Hierarchy
 * @package mpress
 */
?>

<article class="no-results not-found">

	<header class="entry-header">
		<h1 class="entry-title"><?php esc_html_e( 'Nothing Found', 'mpress' ); ?></h1>
	</header>

	<div class="entry-content">
		<?php if ( is_active_sidebar( '404-content-widgets' ) ) : ?>
			<?php dynamic_sidebar( '404-content-widgets' ); ?>
		<?php endif; ?>
	</div>
</article>