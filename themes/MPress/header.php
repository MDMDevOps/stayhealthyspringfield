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
<!--[if (gt IE 9)|!(IE)]><!--> <html <?php language_attributes(); ?> class="no-js"> <!--<![endif]-->
<head>
	<!-- define character set -->
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<!-- XFN Metadata Profile -->
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<!-- mobile specific metadeta -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- specify IE rendering version -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<!-- Wordpress pingback url -->
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<!-- Humans.txt - Where we give credits and thanks -->
	<link type="text/plain" rel="author" href="<?php echo site_url( '/humans.txt' ); ?>" />
	<!-- Wordpress generated head area -->
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?> itemscope itemtype="http://schema.org/WebPage">

	<?php do_action( 'wp_after_body' ); ?>

	<a class="skip-link screen-reader-text jumpscroll" href="#content"><?php esc_html_e( 'Skip to content', 'mpress' ); ?></a>

	<div id="page-wrapper">

		<?php get_template_part( 'views/masthead', apply_filters( 'masthead_type', '' ) ); ?>

		<div id="page" class="hfeed site">
