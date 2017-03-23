<?php
/**
 * Widget form
 * This file is used to markup the widget on the admin side
 * @link    http://midwestfamilymarketing.com
 * @since   1.0.0
 * @package recent_posts_jumbotron
 */
?>
<!-- The Title field : Used for admin purposes only -->
<div class="widget-form mdm_recent_posts">
	<p>
		<label for="<?php echo $this->get_field_name( 'title' ); ?>">Admin Label</label>
		<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $instance['title'] );?>">
		<span class="description"><?php _e( 'Admin only widget title', 'mdm_recent_posts' ); ?></span>
	</p>

	<!-- The Label field : Used for the front-end widget title display -->
	<p>
		<label for="<?php echo $this->get_field_name( 'name' ); ?>">Public Title</label>
		<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'name' ); ?>" name="<?php echo $this->get_field_name( 'name' ); ?>" value="<?php echo esc_attr( $instance['name'] ); ?>">
		<span class="description"><?php _e( 'Public widget title, leave blank to not output', 'mdm_recent_posts' ); ?></span>
	</p>

	<!-- The Post Type field : Used to limit post types -->
	<p>
		<label for="<?php echo $this->get_field_name( 'posttype' ); ?>">Post Type</label>
		<select class="widefat SlectBox" multiple="multiple" name="<?php echo sprintf( '%s[]', $this->get_field_name( 'posttype' ) ); ?>" id="<?php echo $this->get_field_name( 'posttype' ); ?>">
			<?php foreach( get_post_types( array( 'public' => true ), 'objects' ) as $posttype ) : ?>
				<?php if( !in_array( $posttype->name, array( 'content_block', 'attachment' ) ) ) :
					echo sprintf( '<option value="%s" %s>%s</option>', $posttype->name, in_array( $posttype->name, (array)$instance['posttype'] ) ? 'selected' : '', $posttype->name );
				endif; ?>
			<?php endforeach; ?>
		</select>
	</p>

	<!-- The Categories field : Used to choose categories to either show, or exclude -->
	<p>
		<label for="<?php echo $this->get_field_name( 'cat' ); ?>">Categories</label>
		<select class="widefat SlectBox" multiple="multiple" name="<?php echo sprintf( '%s[]', $this->get_field_name( 'cat' ) ); ?>" id="<?php echo $this->get_field_name( 'cat' ); ?>">
			<option value="-1"<?php echo in_array( '-1', (array)$instance['cat'] ) ? ' selected' : '' ?>>Use Current Page / Post Category</option>
			<?php foreach( get_categories() as $category ) : ?>
				<?php echo sprintf( '<option value="%d" %s>%s</option>', $category->cat_ID, in_array( $category->cat_ID, (array)$instance['cat'] ) ? 'selected' : '', $category->name ); ?>
			<?php endforeach; ?>
		</select>
	</p>

	<!-- The Tags field : Used to choose tags to either show, or exclude -->
	<p>
		<label for="<?php echo $this->get_field_name( 'tag' ); ?>">Tags</label>
		<select class="widefat SlectBox" multiple="multiple" name="<?php echo sprintf( '%s[]', $this->get_field_name( 'tag' ) ); ?>" id="<?php echo $this->get_field_name( 'tag' ); ?>">
			<?php foreach( get_tags() as $tag ) : ?>
				<?php echo sprintf( '<option value="%d" %s>%s</option>', $tag->term_id, in_array( $tag->term_id, (array)$instance['tag'] ) ? 'selected' : '', $tag->name ); ?>
			<?php endforeach; ?>
		</select>
	</p>
	<!-- The Template Field : Used to choose an output template -->
	<p>
		<label for="<?php echo $this->get_field_name( 'template' ); ?>">Output Template</label>
		<select class="widefat SlectBox" name="<?php echo $this->get_field_name( 'template' ); ?>" id="<?php echo $this->get_field_name( 'template' ); ?>">
			<?php foreach( $templates as $index => $template ) : ?>
				<?php echo sprintf( '<option value="%s" %s>%s</option>', $index, $instance['template'] == $index ? 'selected' : '', $index ); ?>
			<?php endforeach; ?>
		</select>
	</p>

	<!-- The Showposts field : Used to limit the number of posts displayed -->
	<p>
		<label for="<?php echo $this->get_field_name( 'showposts' ); ?>">Number of posts to show:</label>
		<input class="tiny-text" id="<?php echo $this->get_field_name( 'showposts' ); ?>" name="<?php echo $this->get_field_name( 'showposts' ); ?>" type="number" step="1" min="1" value="<?php echo esc_attr( $instance['showposts'] ); ?>" size="3">
	</p>

	<p>
		<label for="<?php echo $this->get_field_name( 'exclude_cat' ); ?>">
		<input type="checkbox" id="<?php echo $this->get_field_id( 'exclude_cat' ); ?>" name="<?php echo $this->get_field_name( 'exclude_cat' ); ?>" value='on' <?php checked( $instance['exclude_cat'], 'on' ); ?>><em>Exclude</em> Categories</label>
	</p>

	<p>
		<label for="<?php echo $this->get_field_name( 'ignore_sticky_posts' ); ?>">
		<input type="checkbox" id="<?php echo $this->get_field_id( 'ignore_sticky_posts' ); ?>" name="<?php echo $this->get_field_name( 'ignore_sticky_posts' ); ?>" value='on' <?php checked( $instance['ignore_sticky_posts'], 'on' ); ?>><em>Ignore</em> Sticky Posts</label>
	</p>

	<p>
		<label for="<?php echo $this->get_field_name( 'exclude_tag' ); ?>">
		<input type="checkbox" id="<?php echo $this->get_field_id( 'exclude_tag' ); ?>" name="<?php echo $this->get_field_name( 'exclude_tag' ); ?>" value='on' <?php checked( $instance['exclude_tag'], 'on' ); ?>><em>Exclude</em> Tags</label>
	</p>
</div>