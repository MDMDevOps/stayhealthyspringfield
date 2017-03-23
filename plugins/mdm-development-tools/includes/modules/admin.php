<?php

namespace Mdm\DevTools\Modules;

use \Mdm\DevTools\Modules\Utilities as Utilities;

class Admin extends \Mdm\Devtools {

	public function register_assets() {
		// Styles
		wp_register_style( 'fontAwesome', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css', array(), '4.7.0', 'all' );
		wp_register_style( sprintf( '%s_admin',  parent::$plugin_name ), Utilities::url( 'styles/dist/admin.min.css' ), array( 'fontAwesome' ),  parent::$plugin_version, 'all' );
		// Scripts
		wp_register_script( 'gistembed', '//cdnjs.cloudflare.com/ajax/libs/gist-embed/2.4/gist-embed.min.js', array( 'jquery' ), parent::$plugin_version, true );
		wp_register_script( sprintf( '%s_admin', parent::$plugin_name ), Utilities::url( 'scripts/dist/admin.min.js' ), array( 'jquery' ), parent::$plugin_version, true );
		wp_localize_script( sprintf( '%s_admin', parent::$plugin_name ), parent::$plugin_name, array( 'wpajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	}

	public function enqueue_assets() {
		wp_enqueue_style( sprintf( '%s_admin',  parent::$plugin_name ) );
		wp_enqueue_script( sprintf( '%s_admin',  parent::$plugin_name ) );
		wp_enqueue_script( 'gistembed' );
	}

	/**
	 * Enqueue and localize admin site javascript
	 * @since 1.0.0
	 * @see https://developer.wordpress.org/reference/functions/wp_enqueue_script/
	 * @see https://developer.wordpress.org/reference/functions/wp_localize_script/
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( sprintf( '%s_admin', parent::$plugin_name ), parent::$plugin_url . 'scripts/dist/staging.admin.min.js', array( 'jquery' ), parent::$plugin_version, true );
		wp_localize_script( sprintf( '%s_admin', parent::$plugin_name ), 'wpgitajax', array( 'wpajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	}

	public function add_admin_pages() {
		add_menu_page( __( 'Development Tools', parent::$plugin_name ), __( 'Dev Tools', parent::$plugin_name ), 'manage_options', parent::$plugin_name, array( $this, 'display_admin_page' ), 'dashicons-admin-generic', 0 );
	}

	public function display_admin_page() {
		// $current = $this->get_current_section();
		$tabs = $this->get_admin_page_tabs();
		// Get current tab
		$current = isset( $_GET['tab'] ) ? $_GET['tab'] : key( $tabs );
		echo '<div class="wrap">';
			// Begin output
			echo '<h2 class="nav-tab-wrapper">';
			foreach( $tabs as $tab => $tab_args ) {
				// Set the class of the tab
				$class = ( $current === $tab ) ? 'nav-tab nav-tab-active' : 'nav-tab';
				// Append tab markup
				echo sprintf( '<a class="%s" href="?page=%s&tab=%s">%s</a>', $class, parent::$plugin_name, $tab, $tab_args['tab'] );
			}
			echo '</h2>';
			// Display page content
			include parent::$plugin_path . $tabs[$current]['content'];
		echo '</div>';
	}

	private function display_tabs() {
		// $current = $this->get_current_section();
		$tabs = $this->get_admin_page_tabs();
		// Get current tab
		$current = isset( $_GET['tab'] ) ? $_GET['tab'] : key( $tabs );
		// Begin output
		$output = '<h2 class="nav-tab-wrapper">';
		foreach( $tabs as $tab => $tab_args ) {
			// Set the class of the tab
			$class = ( $current === $tab ) ? 'nav-tab nav-tab-active' : 'nav-tab';
			// Append tab markup
			$output .= sprintf( '<a class="%s" href="?page=%s&tab=%s">%s</a>', $class, parent::$plugin_name, $tab, $tab_args['tab'] );
		}
		$output .= '</h2>';
		return $output;
	}

	private function get_admin_page_tabs() {
		$tabs = array(
			'git' => array(
				'title'   => __( 'git', parent::$plugin_name ),
				'tab'     => __( 'GIT', parent::$plugin_name ),
				'content'     => 'views/git_status.php',
			),
			'documentation' => array(
				'title'   => __( 'documentation', parent::$plugin_name ),
				'tab'     => __( 'Documentation', parent::$plugin_name ),
				'content'     => 'views/documentation.php',
				'key'     => sprintf( '%s_%s', parent::$plugin_name, 'log' ),
			),
			'sysreport' => array(
				'title'   => __( 'sysreport', parent::$plugin_name ),
				'tab'     => __( 'System Report', parent::$plugin_name ),
				'content'     => 'views/system_report.php',
				'key'     => sprintf( '%s_%s', parent::$plugin_name, 'sysreport' ),
			),
			'log' => array(
				'title'   => __( 'Debug Log', parent::$plugin_name ),
				'tab'     => __( 'Debug Log', parent::$plugin_name ),
				'content'     => 'views/debug_log.php',
			),
		);
		return apply_filters( sprintf( '%s_admin_page_tabs', parent::$plugin_name ), $tabs );
	}

	public function output_php_info() {
		$phpinfo = $this->get_php_info();
		preg_match("/<body[^>]*>(.*?)<\/body>/is", $phpinfo, $matches);
		echo '<div id="systemreport-phpinfo">', $matches[1], '</div>';
	}

	public function get_php_info() {
		ob_start();
		phpinfo();
		return ob_get_clean();
	}
}