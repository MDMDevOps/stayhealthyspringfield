<?php $post_data = $metabox['args']['meta']; ?>
<div class="row-container">
	<div class="col-sm-15">
		<label for="adunit_code">Banner Code</label>
	</div>
	<div class="col-sm-45">
		<textarea name="adunit_code" id="adunit_code" cols="30" rows="10" class="widefat"><?php echo $post_data['adunit_code'] ?></textarea>
		<p class="description">You can use ad code in this box. This is useful for dynamic ads, such as adsense, that provide a javascript snippet in place of a banner image. Shortcodes can also be used to utilize content from other plugins</p>
	</div>
</div>