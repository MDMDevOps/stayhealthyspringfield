var Mpress = ( function ( $ ) {
	'use strict';
	var App = {};

	/**
	 * Navigation Module
	 * Used to add touch navigation to menu items, as well as dropdown button support
	 */
	App.Navigation = ( function () {
		// Create new menu item, from each menu items
		var menuItems = $.map( $( '.menu li' ), function( element ) {
			return new menuItem( element );
		});
		// Define how to close all menu items at once
		function closeAll() {
			$.each( menuItems, function( index ) {
				menuItems[index].close();
			});
		}
		return {
			closeAll : closeAll,
		};
		// Define behavior for single menu item
		function menuItem( li ) {
			// Cache DOM elements
			var $li     = $( li );
			var $link   = $li.children( 'a' );
			var $parent = $li.parents( 'ul.sub-menu' );
			var $sub    = $li.children( 'ul' );
			var $last   = $parent.find( 'a' ).last();
			var $toggle = $li.children( '.dropdown-toggle' );
			var $toptog = $parent.find( '.dropdown-toggle' );
			// Bind event handlers
			$toggle.on( 'click', toggleSubMenu );
			$link.on( 'focus', showSubMenu );
			$link.on( 'mousedown', preventFocus );
			$link.on( 'click touchstart', showSubMenu );
			$link.on( 'blur',  blurMenu );
			// $( 'body' ).on( 'click touchstart', blurMenu );
			// Determine if a submenu is visible or not
			function isVisible( $el ) {
				// If has visibility : hidden or display : none, or opacity : 0 -> return false ( not visible )
				if( $el.css( 'visibility') === 'hidden' || $el.css( 'display' ) === 'none' || $el.css( 'opacity' ) === '0' ) {
					return false;
				}
				return true;
			}
			function preventFocus( event ) {
				if( event.isTrigger === undefined ) {
					event.preventDefault();
					return true;
				}
			}
			// Define how toggle buttons handle click events
			function toggleSubMenu( event ) {
				event.preventDefault();
				if( $sub.hasClass( 'focused' ) ) {
					$sub.removeClass( 'focused' ).attr( 'aria-hidden', 'true' );
					return true;
				}
				$sub.addClass( 'focused' ).attr( 'aria-hidden', 'false' );
				return false;
			}
			// Define how to handle clicks (for touch support)
			function showSubMenu( event ) {

				// If this has no sub item, there's nothing to do
				if( $sub === undefined ) {
					return true;
				}

				// If we have toggle buttons, we can let the click pass through
				if( $toggle !== undefined && $toggle.length !== 0 && isVisible( $toggle ) && event.type !== 'focus' ) {
					return true;
				}

				// If this sub menu is already visible, there's nothing to do
				if( isVisible( $sub ) ) {
					return true;
				}
				// If we made it here, we can capture the click and open the sub menu instead
				if( event.type !== 'focus' ) {
					$link.focus();
				}
				event.preventDefault();
				$sub.addClass( 'focused' ).attr( 'aria-hidden', 'false' );
				return false;
			}
			// Define behavior on blur
			function blurMenu( event ) {
				// If there is no parent or sub, we can just bail
				if( $parent === undefined && $sub === undefined ) {
					return false;
				}
				setTimeout( function() {
					// Get element that is gaining focus
					var target = $( ':focus' );
					// If target is within scope of this nav item, lets bail
					if( $parent.find( target ).length || $sub.find( target ).length || target.hasClass( 'dropdown-toggle' ) ) {
						return false;
					}
					// If we made it here, let's close the parent & sub
					$parent.removeClass( 'focused' ).attr( 'aria-hidden', 'true' );
					$sub.removeClass( 'focused' ).attr( 'aria-hidden', 'true' );
				}, 1 );
				return true;
			}
			// Simple force close
			function close() {
				// If there is no parent or sub, we can just bail
				if( $parent === undefined && $sub === undefined ) {
					return false;
				}
				$link.blur();
				// If we made it here, let's close the parent & sub
				$parent.removeClass( 'focused' ).attr( 'aria-hidden', 'true' );
				$sub.removeClass( 'focused' ).attr( 'aria-hidden', 'true' );
			}
			// Define methods we expose
			return {
				close : close,
			};
		}
	})();

	/**
	 * Insert Breaks into WordPress Gallaries
	 */
	// App.Gallaries = ( function() {
	//     $( document ).on( 'ready', wp_gallary );
	//     function wp_gallary() {
	//         var gallaries = $( '.gallery' );
	//         var column_list = [ 'gallery-columns-1', 'gallery-columns-2', 'gallery-columns-3', 'gallery-columns-4', 'gallery-columns-5', 'gallery-columns-6', 'gallery-columns-7', 'gallery-columns-8', 'gallery-columns-9' ];
	//         for( var i = 0; i < gallaries.length; i++ ) {
	//             for( var j = 0; j < column_list.length; j++ ) {
	//                 if( gallaries[i].classList.contains( column_list[j] ) ) {
	//                     insert_columns( $( gallaries[i] ), column_list[j] );
	//                 }
	//             }
	//         }
	//     }
	//     function insert_columns( $gallery, column_class ) {
	//         var columns = parseInt( column_class.replace( /^\D+/g, '') );
	//         var figures = $gallery.find( 'figure' );
	//         var width   = 0;
	//         // Get total width of columns
	//         for( var i = 0; i <= columns; i++ ) {
	//             width += $( figures[i] ).width();
	//         }
	//         // Insert max-width
	//         $gallery.css( { 'maxWidth' : width } );
	//     }
	//  });
	return App;
})( jQuery );