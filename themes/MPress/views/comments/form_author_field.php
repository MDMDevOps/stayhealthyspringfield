<div class="row">
	<div class="column md-6">
		<p class="comment-form-author">
			<label for="author" class="screen-reader-text"><?php echo __( 'Author', 'mpress' ) . $req; ?></label>
			<input type="text" name="author" id="author" value="<?php echo $commenter['comment_author']; ?>" placeholder="<?php _e( 'Author Name', 'mpress' ); ?>">
		</p>
	</div>
