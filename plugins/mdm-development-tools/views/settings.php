<div class="wrap <?php echo parent::$plugin_name; ?>">
	<div class="mdmpanel">
		<form method="post" action="options.php">
		    <?php wp_nonce_field( 'update-options' ); ?>
		    <?php settings_fields( $current ); ?>
		    <?php do_settings_sections( $current ); ?>
		    <?php submit_button(); ?>
		</form>
	</div>
</div>


