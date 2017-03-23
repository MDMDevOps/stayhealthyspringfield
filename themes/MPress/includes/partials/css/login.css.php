<?php
/**
 * CSS File to be output for login screen
 */
?>

<style type="text/css" id="custom_header_styles">
	h1 a {
		background-image: url( <?php echo esc_url_raw( $logo[0] ); ?> ) !important;
		height: <?php printf( '%dpx', $logo[2] ); ?> !important;
		width: <?php printf( '%dpx', $logo[1] ); ?> !important;
		-webkit-background-size: cover !important;
		background-size: cover !important;
		transform: translateX(-25%);
	}
</style>