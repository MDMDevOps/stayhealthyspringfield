/**
 * Scroll Toggle Module
 * Used for triggering elements based on scroll position
 * @TODO Place github URI to this file
 * @since 12/8/2016
 * @version 1.0.0
 * @package Mpress
 */

( function( $, Mpress ) {
	'use strict';

	var navItems;

	var init = function() {

		navItems = $.map( $( 'ul.menu' ), function( el ) {
			return new navMenu( el );
		});
		return {
			navItems : navItems,
		};
	};

	function navMenu( menu ) {

		var $menu, $li, $lastChild;

		var cacheDom = function() {
			$menu = $( menu );

			$li = $.map( $menu.find( 'li' ), function( li ) {
				return new NavElement( li );
			});

			var $submenus = $.map( $menu.find( 'ul.sub-menu' ), function( ul ) {
				return new SubMenu( ul );
			});

			console.log( $submenus );

		};

		var bindHandlers = function() {
			// $li.link.on( 'focus', function(  ){console.log('worked');} );
		};

		/**
		 * Helper function to check if an element is visible
		 * @param $element : jquery object
		 */
		var isVisible = function( $element ) {
			if( $element.css( 'visibility') === 'hidden' || $element.css( 'display' ) === 'none' || $element.css( 'opacity' ) === '0' ) {
				return false;
			}
			return true;
		};

		(function(){
			cacheDom();
			bindHandlers();
		})();
	}

	function SubMenu( ul ) {
		var $submenu;

		var cacheDom = function() {
			$submenu = $( ul );
		};

		var bindHandlers = function() {
			$submenu.on( 'mpress:show', show );
			$submenu.on( 'mpress:hide', hide );
		};

		var show = function( event ) {

		};

		var hide = function( event ) {

		};

		( function() {
			cacheDom();
			bindHandlers();
		})();

		return $submenu;
	}


	function NavElement( li ) {
		var $li;

		var cacheDom = function() {
			$li          = $( li );
			$li.link        = $li.children( 'a' );
			$li.toggle      = $li.children( '.dropdown-toggle' );
		};

		var bindHandlers = function() {

		};

		/*
		 * Anon init function for individual item
		 */
		( function() {
			cacheDom();
			bindHandlers();
		})();
		return $li;
	}

	/**
	 * Attach module to Mpress global
	 */
	Mpress.Navigation = init();
})( jQuery, Mpress );