<?php if( get_theme_mod( 'mpress_mobile_menu', null ) == 'menu-off-canvas' ) : ?>
    <style type="text/css">
    body.custom-background { <?php echo trim( $body_style ); ?> }
    body.custom-background #off-canvas-page-wrapper { <?php echo trim( $style ); ?> }
    </style>
<?php else : ?>
    <style type="text/css">
    body.custom-background { <?php echo trim( $style ); ?> }
    </style>
<?php endif; ?>