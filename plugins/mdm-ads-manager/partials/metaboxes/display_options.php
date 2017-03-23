<?php
$options   = $metabox['args']['options'];
$post_data = $metabox['args']['meta'];
?>

<div class="row-container">
	<div class="col-xl-30">
		<h2>Show On</h2>
		<p>
			<hr>
		</p>
		<div class="row-container">
			<div class="col-sm-20">
				<label for="">Pages</label>
			</div>
			<div class="col-sm-40">
				<select name="show_on_pages[]" id="show_on[pages][]" class="widefat multi-select-box" multiple="multiple">
					<option value="-1" <?php echo ( in_array( '-1', $post_data['show_on_pages'] ) ) ? 'selected' : ''; ?>>Any</option>
					<?php foreach( $options['pages'] as $page ) : ?>
						<?php $ancestor_count = Mdm\AdsManager\Utilities::get_ancestors_count( $page->ID, 'page', 'post_type' ); ?>
						<option class="<?php echo ( !empty( $ancestor_count ) ) ? 'child-' . $ancestor_count : 'top'; ?>" value="<?php echo $page->ID; ?>" <?php echo ( in_array( $page->ID, $post_data['show_on_pages'] ) ) ? 'selected' : ''; ?>><?php echo $page->post_title; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>

		<div class="row-container">
			<div class="col-sm-20">
				<label for="">Categories</label>
			</div>
			<div class="col-sm-40">
				<select name="show_on_cats[]" id="show_on[cats][]" class="widefat multi-select-box" multiple="multiple">
					<option value="-1" <?php echo ( in_array( '-1', $post_data['show_on_cats'] ) ) ? 'selected' : ''; ?>>Any</option>
					<?php foreach( $options['categories'] as $category ) : ?>
						<?php $ancestor_count = Mdm\AdsManager\Utilities::get_ancestors_count( $category->term_id, 'category', 'taxonomy' ); ?>
						<option class="<?php echo ( !empty( $ancestor_count ) ) ? 'child-' . $ancestor_count : 'top'; ?>" value="<?php echo $category->term_id; ?>" <?php echo ( in_array( $category->term_id, $post_data['show_on_cats'] ) ) ? 'selected' : ''; ?>><?php echo $category->name; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>

		<div class="row-container">
			<div class="col-sm-20">
				<label for="">Tags</label>
			</div>
			<div class="col-sm-40">
				<select name="show_on_tags[]" id="show_on[tags][]" class="widefat multi-select-box" multiple="multiple">
					<option value="-1" <?php echo ( in_array( '-1', $post_data['show_on_tags'] ) ) ? 'selected' : ''; ?>>Any</option>
					<?php foreach( $options['post_tags'] as $tag ) : ?>
						<option value="<?php echo $tag->term_id; ?>" <?php echo ( in_array( $tag->term_id, $post_data['show_on_tags'] ) ) ? 'selected' : ''; ?>><?php echo $tag->name; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
	</div>


	<div class="col-xl-30">
		<h2>Hide On</h2>
		<p>
			<hr>
		</p>
		<div class="row-container">
			<div class="col-sm-20">
				<label for="">Pages</label>
			</div>
			<div class="col-sm-40">
				<select name="hide_on_pages[]" id="hide_on[pages][]" class="widefat multi-select-box" multiple="multiple">
					<option value="-1" <?php echo ( in_array( '-1', $post_data['hide_on_pages'] ) ) ? 'selected' : ''; ?>>Any</option>
					<?php foreach( $options['pages'] as $page ) : ?>
						<?php $ancestor_count = Mdm\AdsManager\Utilities::get_ancestors_count( $page->ID, 'page', 'post_type' ); ?>
						<option class="<?php echo ( !empty( $ancestor_count ) ) ? 'child-' . $ancestor_count : 'top'; ?>" value="<?php echo $page->ID; ?>" <?php echo ( in_array( $page->ID, $post_data['hide_on_pages'] ) ) ? 'selected' : ''; ?>><?php echo $page->post_title; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>

		<div class="row-container">
			<div class="col-sm-20">
				<label for="">Categories</label>
			</div>
			<div class="col-sm-40">
				<select name="hide_on_cats[]" id="hide_on[cats][]" class="widefat multi-select-box" multiple="multiple">
					<option value="-1" <?php echo ( in_array( '-1', $post_data['hide_on_cats'] ) ) ? 'selected' : ''; ?>>Any</option>
					<?php foreach( $options['categories'] as $category ) : ?>
						<?php $ancestor_count = Mdm\AdsManager\Utilities::get_ancestors_count( $category->term_id, 'category', 'taxonomy' ); ?>
						<option class="<?php echo ( !empty( $ancestor_count ) ) ? 'child-' . $ancestor_count : 'top'; ?>" value="<?php echo $category->term_id; ?>" <?php echo ( in_array( $category->term_id, $post_data['hide_on_cats'] ) ) ? 'selected' : ''; ?>><?php echo $category->name; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>

		<div class="row-container">
			<div class="col-sm-20">
				<label for="">Tags</label>
			</div>
			<div class="col-sm-40">
				<select name="hide_on_tags[]" id="hide_on[tags][]" class="widefat multi-select-box" multiple="multiple">
					<option value="-1" <?php echo ( in_array( '-1', $post_data['hide_on_tags'] ) ) ? 'selected' : ''; ?>>Any</option>
					<?php foreach( $options['post_tags'] as $tag ) : ?>
						<option value="<?php echo $tag->term_id; ?>" <?php echo ( in_array( $tag->term_id, $post_data['hide_on_tags'] ) ) ? 'selected' : ''; ?>><?php echo $tag->name; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
	</div>

</div>



