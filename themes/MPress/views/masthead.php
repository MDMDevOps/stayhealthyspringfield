<?php
/**
 * Template part for displaying the masthead, commonly overridden by child themes
 * @link https://codex.wordpress.org/Template_Hierarchy
 * @package mpress
 */
?>

<?php if( get_theme_mod( 'mpress_menu_type' ) === 'offcanvas' ) : get_template_part( 'views/offcanvas' ); endif; ?>

<header id="masthead" role="banner">
	<div class="wrapper">
		<div class="site-branding" itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
			<?php the_custom_logo(); ?>
		</div>
			<button class="menu-toggle" aria-expanded="false" data-toggle="<?php echo get_theme_mod( 'mpress_menu_type' ); ?>"><?php do_action( 'mpress_icon', 'menu' ) ?><span class="screen-reader-text"><?php esc_html_e( 'Menu', 'mpress' ); ?></span></button>				<nav id="site-navigation" class="navigation-menu <?php echo get_theme_mod( 'mpress_menu_type' ); ?>" role="navigation" itemscope itemtype="https://schema.org/SiteNavigationElement" aria-expanded="false" data-toggle="dropdown" data-group="dropdown">
			<?php if( has_nav_menu( 'primary-navbar' ) ) : ?>
				<?php wp_nav_menu( array( 'theme_location' => 'primary-navbar', 'container' => '', 'walker' => new \Mpress\Walker_Nav_Menu() ) ); ?>
			<?php endif; ?>
		</nav>
	</div>
</header>