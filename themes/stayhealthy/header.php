<?php
/**
 * The header for our theme.
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 * @package mpress
 */
?>

<!DOCTYPE html>
<!--[if lt IE 7 ]>  <html lang="en" class="no-js ie6">  <![endif]-->
<!--[if IE 7 ]>     <html lang="en" class="no-js ie7">  <![endif]-->
<!--[if IE 8 ]>     <html lang="en" class="no-js ie8">  <![endif]-->
<!--[if IE 9 ]>     <html lang="en" class="no-js ie9">  <![endif]-->
<!--[if IE 10 ]>    <html lang="en" class="no-js ie10"> <![endif]-->
<!--[if (gt IE 10)|!(IE)]><!--> <html <?php language_attributes(); ?> class="no-js"> <!--<![endif]-->
<head>
	<!-- define character set -->
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<!-- XFN Metadata Profile -->
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<!-- mobile specific metadeta -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- specify IE rendering version -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
	<!-- Wordpress pingback url -->
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<!-- Humans.txt - Where we give credits and thanks -->
	<link type="text/plain" rel="author" href="<?php echo site_url( '/humans.txt' ); ?>" />
	<!-- Wordpress generated head area -->
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?> itemscope itemtype="http://schema.org/WebPage">

	<a class="skip-link screen-reader-text jumpscroll" href="#content"><?php esc_html_e( 'Skip to content', 'mpress' ); ?></a>

	<div id="page-wrapper">

		<div class="appbar">
			<button class="menu-toggle" aria-expanded="false" data-toggle="<?php echo get_theme_mod( 'mpress_menu_type' ); ?>"><?php do_action( 'mpress_icon', 'menu' ) ?><span class="screen-reader-text"><?php esc_html_e( 'Menu', 'mpress' ); ?></span></button>
		</div>

		<?php if( get_theme_mod( 'mpress_menu_type' ) === 'offcanvas' ) : get_template_part( 'views/offcanvas' ); endif; ?>

		<header id="masthead" role="banner" >
			<div class="navbar">
				<div class="wrapper">
					<div class="site-branding" itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
						<?php the_custom_logo(); ?>
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="anchor-text">
							<h1 class="site-title"><?php echo get_bloginfo( 'name' ); ?></h1>
							<h2 class="site-description"><?php echo get_bloginfo( 'description' ); ?></h2>
						</a>
					</div><!-- .site-branding -->
					<nav id="site-navigation" class="navigation-menu <?php echo get_theme_mod( 'mpress_menu_type' ); ?>" role="navigation" itemscope itemtype="https://schema.org/SiteNavigationElement" aria-expanded="false" data-toggle="dropdown" data-group="dropdown">
						<?php if( has_nav_menu( 'primary-navbar' ) ) : ?>
							<?php wp_nav_menu( array( 'theme_location' => 'primary-navbar', 'container' => '', 'walker' => new \Mpress\Walker_Nav_Menu() ) ); ?>
						<?php endif; ?>
					</nav>
				</div>
			</div>
			<?php if( is_front_page() ) : ?>
				<div id="hero" style="background-image: url( <?php header_image(); ?> );">
					<?php if ( is_active_sidebar( 'homepage-masthead-widget-area' ) ) : ?>
						<div class="widget-area">
							<div class="wrapper">
								 <?php dynamic_sidebar( 'homepage-masthead-widget-area' ); ?>
							</div>
						</div>
					<?php endif; ?>
				</div>
			<?php else : ?>
				<div id="hero">
					<?php if ( is_active_sidebar( 'masthead-widget-area' ) ) : ?>
						<div class="widget-area">
							<div class="wrapper">
								 <?php dynamic_sidebar( 'masthead-widget-area' ); ?>
							</div>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>

		</header>

		<div id="page" class="hfeed site">

		<?php if( is_active_sidebar( 'widget-bar' ) && !is_front_page() ) : ?>
			<div class="widget-area secondary widget-bar" role="complementary">
				<div class="wrapper">
					 <?php dynamic_sidebar( 'widget-bar' ); ?>
				</div>
			</div>
		<?php endif; ?>

