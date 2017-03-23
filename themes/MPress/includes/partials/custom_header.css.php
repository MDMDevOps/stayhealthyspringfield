<?php
/**
 * CSS File to be output for custom header styles
 */
?>

<style type="text/css" id="custom_header_styles">
    <?php if ( $header_text_display === false ) : ?>
        .site-title,
        .site-description {
            position: absolute;
            clip: rect( 1px, 1px, 1px, 1px );
        }
    <?php endif; ?>
    <?php if( $header_text_color !== false  )  : ?>
    	.site-title a,
    	.site-description {
    	    color: #<?php echo esc_attr( $header_text_color ); ?>;
    	}
    <?php endif; ?>
</style>