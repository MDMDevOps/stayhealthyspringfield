<?php $post_data = $metabox['args']['meta']; ?>
<?php wp_enqueue_media(); ?>
<div class="row-container add-margin-bottom">
	<div class="col-sm-15">
		<label for="adunit_image">Ad Unit Image</label>
	</div>
	<div class="col-sm-45">
		<div class="input-group">
			<div class="input-group-cell widefat">
				<input type="text" id="adunit_image" name="adunit_image" class="input-group-field widefat" value="<?php echo esc_url_raw( $post_data['adunit_image'] ); ?>" data-input="source">
			</div>
			<div class="input-group-button">
				<button class="button button-primary" id="mdmam_upload" data-action="choose">Select Image</button>
			</div>
		</div>
		<p class="description">You can specify in image URL, or upload a banner image into the Wordpress media gallery.</p>
	</div>
</div>
<div class="row-container add-margin-bottom">
	<div class="col-sm-15">
		<label for="adunit_link">Ad Unit Link</label>
	</div>
	<div class="col-sm-45">
		<input type="text" class="widefat" id="adunit_link" name="adunit_link" value="<?php echo esc_url_raw( $post_data['adunit_link'] ); ?>">
		<p class="description">Specify where the ad unit should link to</p>
	</div>
</div>
<div class="row-container add-margin-bottom">
	<div class="col-sm-15">
		<label for="adunit_target">Ad Unit Target</label>
	</div>
	<div class="col-sm-45">
		<select name="adunit_target" id="adunit_target" class="widefat">
			<option value="_blank" <?php selected( $post_data['adunit_target'], '_blank', true ); ?>>_blank - Open the URL in a new window or tab</option>
			<option value="_self" <?php selected( $post_data['adunit_target'], '_self', true ); ?>>_self - Open the URL the same window or tab</option>
			<option value="_top" <?php selected( $post_data['adunit_target'], '_top', true ); ?>>_top - Open the URL in a new full body window</option>
		</select>
		<p class="description">Specify how the link should open</p>
	</div>
</div>

