<?php
/**
 * Template Name: Full Width Page
 * Description: A Page Template for Full Width Content
 * @see     https://codex.wordpress.org/Template_Hierarchy
 * @package mpress
 */
?>

<?php get_header(); ?>

<div id="primary" class="content-area full-width">

    <main id="main" class="site-main" role="main">

        <?php while ( have_posts() ) : the_post(); ?>
            <?php get_template_part( 'views/content', get_post_type() ); ?>
            <?php if ( comments_open() || get_comments_number() ) : comments_template(); endif; ?>
        <?php endwhile;?>

    </main>

    <aside id="sidebar" class="full-width">
        <?php get_sidebar(); ?>
    </aside>

</div>

<?php get_footer(); ?>