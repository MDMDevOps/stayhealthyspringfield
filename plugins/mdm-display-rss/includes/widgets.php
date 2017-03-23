<?php

namespace Mdm\RssDisplay;

class Widgets extends \Mdm\RssDisplay {

	public function register() {
		register_widget( '\\Mdm\\RssDisplay\\Widgets\\Widget' );
	}

}