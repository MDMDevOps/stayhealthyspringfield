<?php

function mpress_content_type( $post ) {
    if( is_home( $post ) && !is_front_page( $post ) ) {
        return 'blog';
    } elseif( is_404( $post ) ) {
        return '404';
    } elseif( is_singular( $post ) ) {
        $post_type = get_post_type( $post );
        if( in_array( $post_type, array( 'post', 'page', 'attachment' ) ) ) {
            return $post_type;
        } else {
            return 'custom';
        }
    } elseif( is_category( $post ) ) {
        return 'category';
    } elseif( is_tag( $post ) ) {
        return 'tag';
    } elseif( is_day( $post ) ) {
        return 'day';
    } elseif( is_month( $post ) ) {
        return 'month';
    } elseif( is_year( $post ) ) {
        return 'year';
    } elseif( is_author( $post ) ) {
        return 'author';
    } elseif( is_search( $post ) ) {
        return 'search';
    } elseif( is_archive( $post ) ) {
        return 'archive';
    }
    return false;
}