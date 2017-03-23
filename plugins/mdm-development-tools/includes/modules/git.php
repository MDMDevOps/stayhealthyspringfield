<?php

namespace Mdm\DevTools\Modules;

use \Mdm\DevTools\Modules\Utilities as Utilities;

class Git extends \Mdm\Devtools {

	private static $exception = false;

	/**
	 * Kickoff operation of this module
	 */
	public function burn_baby_burn() {
		self::$exception = false;
		add_filter( 'repository_data', array( $this, 'get_repository_data' ), 10, 2 );
		add_action( 'wp_ajax_git_terminus', array( $this, 'git_terminus' ) );
		add_action( 'display_git_configuration', array( $this, 'display_git_config' ) );
	}

	public function is_initialized() {
		try {
			$path = trailingslashit( trim( ABSPATH . parent::$plugin_settings['git_path'] ) );
			chdir( $path );
			$real_path = exec( "git rev-parse --show-toplevel" );
			if( trailingslashit( trim( $real_path ) ) === $path ) {
				return true;
			}
			return false;
		} catch (Exception $e) {
			return false;
		}
	}

	private function git_ready() {
		// Make sure our repo is initialized
		if( $this->is_initialized() === false ) {
			return false;
		}
		// Make sure we have all of our repo data setup
		if( empty( parent::$plugin_settings['git_user'] ) || empty( parent::$plugin_settings['git_repo'] ) || empty( parent::$plugin_settings['api_key'] ) ) {
			return false;
		}
		return true;
	}

	public function display_git_config() {
		if( !$this->git_ready() ) {
			include parent::$plugin_path . 'partials/pages/configuration_instructions.php';
		} else {
			$output  = '<ol>';
			$output .= '<li>Go to the github repository at ';
			$output .= sprintf( '<a href="https://github.com/%1$s/%2$s/settings/hooks" target="_blank">https://github.com/%1$s/%2$s/settings/hooks</a></li>', parent::$plugin_settings['git_user'], parent::$plugin_settings['git_repo'] );
			$output .= '<li>Choose <strong>Add New</strong></li>';
			$output .= sprintf( '<li>Enter Payload URL : <strong>%s</strong></li>', esc_url( home_url( '/wp-json/staging/v1/pull' ) ) );
			$output .= '</ol>';
			echo $output;
		}
	}

	public function git_terminus() {
		switch( $_POST['state'] ) {
			case 'status' :
				$response = $this->status();
				break;
			case 'commit' :
				$response = $this->commit();
				break;
			case 'push' :
				$response = $this->push();
				break;
			case 'pull' :
				$response = $this->pull();
				break;
			case 'reset' :
				$response = $this->reset();
				break;
			default :
				$response = false;
				break;
		}
		if( $response === false ) {
			$response = 'Unable to process request at this time';
		}
		echo json_encode( apply_filters( 'the_content', $response ) );
		exit();
	}

	public function register_rest_route() {
		register_rest_route( 'staging/v1', '/pull', array(
		    'methods' => 'POST',
		    'callback' => array( $this, 'webhook_callback' ),
		) );
	}

	public function webhook_callback( $request ) {
		// do type checking here as the method declaration must be compatible with parent
		if ( !$request instanceof \WP_REST_Request ) {
		    throw new \InvalidArgumentException( __METHOD__ . ' expects an instance of WP_REST_Request.' );
		}

		$request = $this->parse_request_object( $request );

		// If we're not pushing to the master branch, we can bail
		if( !isset( $request->ref ) || strpos( $request->ref, 'master' ) === false ) {
		    return new \WP_REST_Response( 'Push not on master branch', 201 );
		}
		// Peform the pull
		$response = $this->reset();
		// Check if an exception was thrown
		if( $this->exception !== false ) {
			return new \WP_REST_Response( $response, 187 );

		}
		// if we made it here without an exception being thrown, return response
		return new WP_REST_Response( $response, 200 );

	}

	public function parse_request_object( $request ) {
		$params = $request->get_params();
		$params = json_decode( $params['payload'] );
		return $params;
	}

	private function status() {
		// Make sure our repo is ready
		if( $this->git_ready() === false ) {
			return false;
		}
		try {
			chdir( trailingslashit( trim( ABSPATH . parent::$plugin_settings['git_path'] ) ) );
			$response = shell_exec( 'git status' );
			return $response;
		} catch ( Exception $e ) {
			// Eventually I'll do some other stuff here to handle exceptions
			$this->exception === $e;
			$response = $e->getMessage();
			return $response;
		}
	}

	private function commit() {
		// Make sure our repo is ready
		if( $this->git_ready() === false ) {
			return false;
		}
		try {
			chdir( trailingslashit( trim( ABSPATH . parent::$plugin_settings['git_path'] ) ) );
			$response  = shell_exec( 'git add --all' );
			$response .= shell_exec( sprintf( 'git commit -m "Automated Commit From %s"', $_SERVER['HTTP_HOST'] ) );
			return $response;
		} catch ( Exception $e ) {
			// Eventually I'll do some other stuff here to handle exceptions
			$this->exception === $e;
			$response = $e->getMessage();
			return $response;
		}
	}

	private function push() {
		// Make sure our repo is ready
		if( $this->git_ready() === false ) {
			return false;
		}
		try {
			chdir( trailingslashit( trim( ABSPATH . parent::$plugin_settings['git_path'] ) ) );
			$response = shell_exec( 'git push origin master' );
			return $response;
		} catch ( Exception $e ) {
			// Eventually I'll do some other stuff here to handle exceptions
			$this->exception === $e;
			$response = $e->getMessage();
			return $response;
		}
	}

	private function pull() {
		// Make sure our repo is ready
		if( $this->git_ready() === false ) {
			return false;
		}
		try {
			chdir( trailingslashit( trim( ABSPATH . parent::$plugin_settings['git_path'] ) ) );
			$response = shell_exec( 'git pull origin master' );
			return $response;
		} catch ( Exception $e ) {
			// Eventually I'll do some other stuff here to handle exceptions
			$this->exception === $e;
			$response = $e->getMessage();
			return $response;
		}
	}
	private function reset() {
		// Make sure our repo is ready
		if( $this->git_ready() === false ) {
			return false;
		}
		try {
			do_action( 'before_git_reset' );
			chdir( trailingslashit( trim( ABSPATH . parent::$plugin_settings['git_path'] ) ) );
			$response  = shell_exec( 'git fetch --all' );
			$response .= shell_exec( 'git reset --hard origin/master' );
			do_action( 'after_git_reset' );
			return $response;
		} catch ( Exception $e ) {
			// Eventually I'll do some other stuff here to handle exceptions
			$this->exception === $e;
			$response = false;
			return $response;
		}
	}


	public function get_repository_data( $query, $query_args ) {
		// Make sure our repo is ready
		if( $this->git_ready() === false ) {
			return false;
		}
		// Set up defaults to avoid index errors
		$default_query_args = array(
			'per_page' => 1000,
		);
		// Merge passed arguments with defaults
		$query_args = wp_parse_args( $query_args, $default_query_args );
		// Get data from github API
		try {
			// 1: Build request URI
			$request_uri = sprintf( 'https://api.github.com/repos/%s/%s/%s', parent::$plugin_settings['git_user'], parent::$plugin_settings['git_repo'], $query );
			// 2: Append variables
			$request_uri = add_query_arg( 'access_token', parent::$plugin_settings['api_key'], rtrim( $request_uri, '/' ) );
			// 3. Append additional queries
			foreach( $query_args as $query_key => $query_arg ) {
				$request_uri = add_query_arg( $query_key, $query_arg, rtrim( $request_uri, '/' ) );
			}
			// 3: Get json response from github and parse it
			return json_decode( wp_remote_retrieve_body( wp_remote_get( $request_uri ) ), true );
		} catch ( Exception $e ) {
			return false;
		}
	}
}