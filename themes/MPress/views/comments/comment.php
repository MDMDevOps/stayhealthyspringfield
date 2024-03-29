<<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( $args['has_children'] ? 'parent' : '', $comment ); ?>>
		 <article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
			<header class="comment-header">
				<figure class="comment-author gravatar">
					<?php if ( 0 != $args['avatar_size'] ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
				</figure>
				<div class="vcard">
					<span class="author">
						<?php printf( __( '%s <span class="says">says:</span>' ), sprintf( '<b class="fn">%s</b>', get_comment_author_link( $comment ) ) ); ?>
					</span>
					<span class="meta">
						<a href="<?php echo esc_url( get_comment_link( $comment, $args ) ); ?>">
							<time datetime="<?php comment_time( 'c' ); ?>">
								<?php
									/* translators: 1: comment date, 2: comment time */
									printf( __( '%1$s at %2$s' ), get_comment_date( '', $comment ), get_comment_time() );
								?>
							</time>
						</a>
					</span>
					<span class="edit">
						<?php edit_comment_link( __( 'Edit' ), '<span class="edit-link">', '</span>' ); ?>
					</span>
				</div>



			   <?php if ( '0' == $comment->comment_approved ) : ?>
			   <p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.' ); ?></p>
			   <?php endif; ?>
			</header><!-- .comment-meta -->

			<div class="comment-content">
			   <?php comment_text(); ?>
			</div><!-- .comment-content -->

			<?php
			comment_reply_link( array_merge( $args, array(
			   'add_below' => 'div-comment',
			   'depth'     => $depth,
			   'max_depth' => $args['max_depth'],
			   'before'    => '<div class="reply">',
			   'after'     => '</div>'
			) ) );
			?>
		 </article><!-- .comment-body -->