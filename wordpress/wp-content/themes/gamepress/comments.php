
	<div class="divider-solid"></div>
	<?php if ( post_password_required() ) : ?>
		<p class="nopassword"><?php _e( 'This post is password protected. Enter the password to view any comments.', 'gamepress' ); ?></p>


	<?php
			/* Stop the rest of comments.php from being processed,
			 * but don't kill the script entirely -- we still have
			 * to fully load the template.
			 */
			return;
		endif;
	?>

	<?php // You can start editing here -- including this comment! ?>
	<?php if ( have_comments() ) : ?>
	<div class="comments-header">
		<h3>
			<?php comments_number(__('No comments','gamepress'), __('1 comment','gamepress'), __('% Comments','gamepress')); ?>
		</h3>
	</div>
	<div class="divider-solid"></div>
	<div id="comments">

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-above">
			<h1 class="assistive-text section-heading"><?php _e( 'Comment navigation', 'gamepress' ); ?></h1>
			<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'gamepress' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'gamepress' ) ); ?></div>
		</nav>
		<?php endif; // check for comment navigation ?>

		<ol class="commentlist">
			<?php wp_list_comments( array( 'callback' => 'gamepress_comment', 'avatar_size' => '60' ) ); ?>
		</ol>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-below">
			<h1 class="assistive-text section-heading"><?php _e( 'Comment navigation', 'gamepress' ); ?></h1>
			<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'gamepress' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'gamepress' ) ); ?></div>
		</nav>
		<?php endif; // check for comment navigation ?>
	</div><!-- #comments -->
	<?php endif; // have_comments() ?>
	<div class="comment-form">
	<?php
		
		// If comments are closed and there are no comments, let's leave a little note, shall we?
		if ( ! comments_open() && '0' != get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
		<p class="nocomments"><?php _e( 'Comments are closed.', 'gamepress' ); ?></p>
	<?php endif; ?>

	<?php comment_form(
	array(
		'title_reply' => __( 'Leave a comment','gamepress'),
		'title_reply_to' => __( 'Leave a reply to %s', 'gamepress'),
        'cancel_reply_link' => __( 'Cancel reply', 'gamepress'),
		'comment_field' => '<div><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></div>',
		'comment_notes_after' => ''
	)); 
	?>
	</div>

