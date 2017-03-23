// Protect the global namespace
if( $ ) { $.noConflict(); }
// Document ready function
jQuery( function( $ ) {
	'use strict';
	// Put any custom scripts here
	var $scrolltop = $( '#scrolltop' );
	// Create inview for colophone
	var inview = new Waypoint.Inview({
		element: $('#colophon')[0],
		enter: function(direction) {
			$scrolltop.addClass( 'bottom' );
		},
		entered: function(direction) {
		},
		exit: function(direction) {

		},
		exited: function(direction) {
			$scrolltop.removeClass( 'bottom' );
		}
	});

});
