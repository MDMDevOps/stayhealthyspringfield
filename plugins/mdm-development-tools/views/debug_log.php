<?php
// Set up some variables
$debug_statuses = \Mdm\DevTools\Modules\Utilities::get_debug_status();
?>

<div class="wrap <?php echo parent::$plugin_name; ?>">
	<div id="debugstatus" class="mdmpanel">
	    <style scoped>
	    	#debugstatus table {
				border: 1px solid rgba(158, 156, 156, 0.22);
				border-collapse: collapse;
				min-width: 100%;
				margin-bottom: 1em;
	    	}
	    	#debugstatus td, th {
	    		border: 1px solid rgba(193, 191, 191, 0.19);
	    		padding: .4em 1em;
	    		text-align: left;
	    	}
	    	#systemreport-phpinfo h2 {
	    		font-size: 2rem;
	    		border-bottom: 1px solid rgba(128, 128, 128, 0.18);
	    		margin-bottom: .4rem;
	    	}
	    	#debugstatus td.h {
	    		font-weight: 600;
	    		background: rgb(241, 241, 241);
	    	}
	    	#debugstatus th {
	    		font-size: 1.1em;
	    	}
	    	#debugstatus .gist table {
	    		border: none;
	    		margin: none;
	    	}
	    	#debugstatus .gist table th, #debugstatus .gist table td {
	    		border: none;
	    		margin: none;
	    	}
	    	#debugstatus code.gistembed {
	    		background: transparent;
	    		margin: 0;
	    		padding: 0;
	    	}
	    </style>
	    <h2>Debug Status</h2>
	    <hr>
	    <p>WP_DEBUG is a PHP constant (a permanent global variable) that can be used to trigger the "debug" mode throughout WordPress. It is assumed to be false by default and is usually set to true in the wp-config.php file on development copies of WordPress.</p>
		<table>
			<thead>
				<tr>
					<th>Constant</th>
					<th>Status</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach( $debug_statuses as $constant => $status ) : ?>
					<tr>
						<td class="name"><?php echo $constant; ?></td>
						<td class="status <?php echo strtolower( $status ); ?>"><?php echo $status; ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<p>To enable a stock debug configuration, paste the following into your <code>wp-config.php</code> file:</p>
		<code class="gistembed" data-gist-id="a1b3d0410465df2aece8fa04f4c19ee3"></code>
		<p>For more information about enabling debug mode, <a href="https://codex.wordpress.org/WP_DEBUG" target="_blank">see the codex.</a></p>
	</div>



	<div id="debuglog" class="mdmpanel">
		<h2>Debug Log</h2>
		<hr>
		<p>WP_DEBUG_LOG is a companion to WP_DEBUG that causes all errors to also be saved to a debug.log log file inside the /wp-content/ directory. This is useful if you want to review all notices later or need to view notices generated off-screen (e.g. during an AJAX request or wp-cron run).</p>
		<p>Note that this allows you to write to /wp-content/debug.log using PHP's built in error_log() function, which can be useful for instance when debugging AJAX events.</p>
	    <style scoped>
	    	#debuglog .wp-debug-log {
				max-height: 400px;
				overflow: auto;
				border: 1px solid rgba(193, 191, 191, 0.19);
				padding: .4em 1em;
	    	}
	    	#debuglog ul#log {
	    		list-style-type: none;
	    		list-style-position: inside;
	    	}
	    	#debuglog ul#log li {
	    		margin-bottom: 1.618em;
	    		#c40303;
	    	}
	    	#debuglog ul#log li .error {
	    		color: #c40303;
	    		font-weight: bold;
	    	}
	    </style>

		<?php
		if( !defined( 'WP_DEBUG_LOG' ) || WP_DEBUG_LOG !== true ) {
			echo 'Debug log is not enabled. Enable WP_DEBUG && WP_DEBUG_LOG to use this feature';
		}
		else if( !file_exists( ABSPATH . 'wp-content/debug.log' ) ) {
			echo 'Log file not found.';
		}
		else {
			echo '<div class="wp-debug-log">';
			$errors = file( ABSPATH . 'wp-content/debug.log', FILE_IGNORE_NEW_LINES );
			echo '<ul id="log">';

			foreach( $errors as $index => $error ) {
				echo '<li><span class="error">Error #' . $index . '</span> ' . $error . '</li>';
			}
			echo '</ul></div>';
		}
		?>
	</div>
</div>