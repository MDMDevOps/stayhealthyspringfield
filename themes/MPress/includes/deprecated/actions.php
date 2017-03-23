<?php
/*******************************************************************************
 *                         ___        __  _
 *                        /   | _____/ /_(_)___  ____  _____
 *                       / /| |/ ___/ __/ / __ \/ __ \/ ___/
 *                      / ___ / /__/ /_/ / /_/ / / / (__  )
 *                     /_/  |_\___/\__/_/\____/_/ /_/____/
 *
 ******************************************************************************/


if( !function_exists( 'mpress_author_data' ) ) {
    function mpress_author_data( $args = array() ) {
        if( empty( $args ) ) {
            return false;
        }
        $curauth = (get_query_var('author_name')) ? get_user_by('slug', get_query_var('author_name')) : get_userdata(get_query_var('author'));
        //$author = ( isset( $_GET['author_name'] ) ) ? get_user_by( 'slug', $author_name ) : get_userdata( intval( $author ) );
        var_dump( $curauth );
    }
    add_action( 'mpress_author_data', 'mpress_author_data', 1 );
}