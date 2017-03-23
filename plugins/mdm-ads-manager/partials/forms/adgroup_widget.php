<p>
	<label for="<?php echo $this->get_field_name( 'title' ); ?>">Title:</label>
	<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>">
</p>
<p>
	<label for="<?php echo $this->get_field_name( 'adgroup' ); ?>">Ad Group:</label>
	<select class="widefat" id="<?php echo $this->get_field_id( 'adgroup' ); ?>" name="<?php echo $this->get_field_name( 'adgroup' ); ?>" >
		<option value=""></option>
		<?php foreach( $terms as $term ) : ?>
			<option value="<?php echo $term->term_id; ?>" <?php selected( $instance['adgroup'], $term->term_id, true ); ?>><?php echo $term->name; ?></option>
		<?php endforeach; ?>
	</select>
</p>
<p>
	<label for="<?php echo $this->get_field_name( 'showposts' ); ?>">Max number of ads to show:</label>
	<input type="number" class="widefat" id="<?php echo $this->get_field_id( 'showposts' ); ?>" name="<?php echo $this->get_field_name( 'showposts' ); ?>" value="<?php echo esc_attr( $instance['showposts'] ); ?>">
</p>
<p>
	<label for="<?php echo $this->get_field_name( 'hidetitle' ); ?>"><input type="checkbox" value="on" class="widefat" id="<?php echo $this->get_field_id( 'hidetitle' ); ?>" name="<?php echo $this->get_field_name( 'hidetitle' ); ?>" <?php checked( $instance['hidetitle'], 'on', true ); ?>>&nbsp;Hide Title?</label>
</p>