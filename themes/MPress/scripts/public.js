// Protect the global namespace
if( $ ) { $.noConflict(); }

jQuery( function( $ ) {

	// Close All the menu's on window resize
	$( window ).on( 'resize', function( event ) {
		if( event.isTrigger !== undefined ) {
			return;
		}
		// Mpress.togglebuttons.collapse();
		// Mpress.navigation.closeAll();
	});

}); // end document ready