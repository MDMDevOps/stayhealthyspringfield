<?php
/*******************************************************************************
 *                         _______ ____
 *                        / ____(_) / /____  __________
 *                       / /_  / / / __/ _ \/ ___/ ___/
 *                      / __/ / / / /_/  __/ /  (__  )
 *                     /_/   /_/_/\__/\___/_/  /____/
 *
 *
 ******************************************************************************/

if( !function_exists( 'mpress_featured_image' ) ) {
    function mpress_featured_image( $size = 'post-thumbnail' ) {
        // Try to get the image ID
        $image_by_id = get_theme_mod('mpress_default_featured_image_id', null );
        if( $image_by_id !== null ) {
            echo wp_get_attachment_image( $image_by_id, $size );
            return true;
        } else {
            $image_by_src = get_theme_mod('mpress_default_featured_image', null );
            if( $image_by_src !== null ) {
                echo sprintf( '<img src="%s">', $image_by_src );
                return true;
            }
        }
        return false;
    }
}