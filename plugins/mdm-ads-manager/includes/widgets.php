<?php

namespace Mdm\AdsManager;

class Widgets extends \Mdm\AdsManager {

	public function register() {
		register_widget( '\\Mdm\\AdsManager\\Widgets\\AdGroups' );
	}

}