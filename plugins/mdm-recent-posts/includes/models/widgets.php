<?php

namespace Mdm\Recent_Posts\Models;

class Widgets extends \Mdm\Recent_Posts {

	/**
	 * Kickoff operation of this module
	 */
	public function burn_baby_burn() {
		add_action( 'widgets_init', array( $this, 'register_widgets' ) );
	}

	public function register_widgets() {
		// Register widget(s)
		register_widget( '\\Mdm\\Recent_Posts\\Widgets\\Widget' );
	}

}