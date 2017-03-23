jQuery( function( $ ) {
	'use strict';
	// Get the outer wrapper we use for most things
	var $plugin_element_wrapper = $( '.mdm_ads_manager' );
	/*
	 * Initialize sumo select for admin multiselect elements
	 */
	( function() {
		var $select = $plugin_element_wrapper.find( '.multi-select-box' ).SumoSelect();
	})();
	/**
	 * Image upload script for admin pages
	 */
	( function() {
		var mediaFrame, $metabox, $source;
		var createFrame = function() {
			// Create frame
			wp.media.frames.file_frame = wp.media({
				title: "Insert Image",
				multiple: false,
				editing:   false,
				button: { text: "Choose Image" },
				displaySettings: false,
				library: {
				   type: 'image'
				}
			});
			// Return frame
			return wp.media.frames.file_frame;
		};

		var chooseImage = function( event ) {
			event.preventDefault();
			// Set Elements
			$metabox = $( event.target ).closest( '.inside' );
			$source  = $metabox.find( '[data-input=source]' );
			// Create media frame
			if( typeof mediaFrame === 'undefined' ) {
				mediaFrame = createFrame();
			}
			// Bind frame's select event
			mediaFrame.on( 'select', insertImage );
			// Open frame
			mediaFrame.open();
		};

		var insertImage = function( event ) {
			// Get selection
			var selection = mediaFrame.state().get( 'selection' ).first().toJSON();
			// Add URL
			$source.attr( 'value', selection.url );
			// Remove handler
			mediaFrame.off( 'select', insertImage );
		};

		( function() {
			$( document ).on( 'click', '.mdm_ads_manager [data-action="choose"]', chooseImage );
		})();
	})();
});