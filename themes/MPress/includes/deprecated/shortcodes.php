<?php

if( !function_exists( 'mpress_menu_shortcode' ) ) {
    function mpress_menu_shortcode( $atts = array() ) {
        // If no menu location, or menu is specified, bail
        if( !$atts['theme_location'] && !$atts['menu'] ) {
            return false;
        }
        // Parse attributes
        $atts = shortcode_atts( array(
            'theme_location'  => '',
            'menu'            => '',
            'container'       => 'div',
            'container_class' => '',
            'container_id'    => '',
            'menu_class'      => 'menu',
            'menu_id'         => '',
            'echo'            => false,
            'fallback_cb'     => 'wp_page_menu',
            'before'          => '',
            'after'           => '',
            'link_before'     => '',
            'link_after'      => '',
            'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
            'depth'           => 0,
            'walker'          => ''
            ), $atts, 'mpress_embed_menu' );

        return wp_nav_menu( $args );
    }
    add_shortcode( 'mpress_embed_menu', 'mpress_menu_shortcode' );
}

if( !function_exists( 'mpress_code_shortcode' ) && !shortcode_exists( 'code' ) ) {
    function mpress_code_shortcode( $atts = array(), $content = null ) {
        // Parse attributes
        $atts = shortcode_atts( array(
            'language'  => 'html',
            ), $atts, 'code' );

        $output  = sprintf( '<pre class="language-%1$s"><code class="language-%1$s">', $atts['language'] );
        $output .= $content;
        $output .= '</code></pre>';
        return $output;
    }
    add_shortcode( 'code', 'mpress_code_shortcode' );
}

/**
 * Shorcode for executing custom logo template tag / action
 * @since 1.1.1
 */
if( !function_exists( 'mpress_custom_logo_shortcode' ) ) {
    function mpress_custom_logo_shortcode( $atts = array() ) {
        ob_start();
        do_action( 'mpress_custom_logo' );
        return ob_get_clean();
    }
    add_shortcode( 'mpress_custom_logo', 'mpress_custom_logo_shortcode' );
}

/**
 * Shortcode for including a defined sidebar
 * @since 1.1.1
 */
if( !function_exists( 'mpress_sidebar_shortcode' ) ) {
    function mpress_sidebar_shortcode( $atts = array() ) {
        // Parse attributes
        $atts = shortcode_atts( array( 'name' => null ), $atts, 'mpress_embed_sidebar' );
        // If no sidebar was specified, we can bail
        if( $atts['name'] === null ) {
            return;
        }
        ob_start();
        if ( is_active_sidebar( $atts['name'] ) ) :
            dynamic_sidebar( $atts['name'] );
        endif;
        return ob_get_clean();
    }
    add_shortcode( 'mpress_embed_sidebar', 'mpress_sidebar_shortcode' );
}

/**
 * Shortcode for including a icon fonts in content
 * @since 1.1.1
 */
if( !function_exists( 'mpress_icon_font_shortcode' ) ) {
    function mpress_icon_font_shortcode( $atts = array() ) {
        $atts = shortcode_atts( array( 'icon' => null ), $atts, 'mpress_icon_font_shortcode' );
        // If no icon was specified, we can bail
        if( $atts['icon'] === null ) {
            return false;
        }
        return get_mpress_icon( $atts['icon'] );
    }
    add_shortcode( 'mpress_icon', 'mpress_icon_font_shortcode' );
}