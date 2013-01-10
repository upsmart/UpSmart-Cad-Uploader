<?php if (post_password_required()) { ?>
    <p class="nocomments"><?php _e('This post is password protected. Enter the password to view any comments.', 'responsive'); ?></p>
    
	<?php return; } ?>

<ul id="comments">
<?php if (have_comments()) : ?>
<li>
	<h2 class="title">Comments</h2>
    
	<ol class="commentlist">
		<?php wp_list_comments('avatar_size=250&type=comment'); ?>
	</ol>
</li>

    <?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
     <li class="stars"><span class="left"><?php previous_comments_link(); ?></span><span class="right"><?php next_comments_link(); ?></span></li>
    <?php endif; ?>
    
<?php endif; ?>

<?php if (!empty($comments_by_type['pings'])) : ?>
<li>
	<h2 class="title">Pings &amp; Trackbacks</h2>
    
	<ol class="commentlist">
		<?php wp_list_comments('type=pings&max_depth=<em>'); ?>
	</ol>
</li>
<?php endif; ?>
 
<?php if (comments_open()) : ?>
<li>
    <?php $fields = array(
			'author' => '<p class="comment-form-author">' . '<label for="author">' . __('Name','responsive') . '</label> ' . ( $req ? '<span class="required">*</span>' : '' ) .
			'<input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . '" size="30" /></p>',
			'email' => '<p class="comment-form-email"><label for="email">' . __('E-mail','responsive') . '</label> ' . ( $req ? '<span class="required">*</span>' : '' ) .
			'<input id="email" name="email" type="text" value="' . esc_attr($commenter['comment_author_email']) . '" size="30" /></p>',
			'url' => '<p class="comment-form-url"><label for="url">' . __('Website','responsive') . '</label>' .
			'<input id="url" name="url" type="text" value="' . esc_attr($commenter['comment_author_url']) . '" size="30" /></p>', );

    $defaults = array('fields' => apply_filters('comment_form_default_fields', $fields));

    comment_form($defaults); ?>

</li>
<?php endif; ?>

<?php if ( comments_open() || get_comment_pages_count() > 0 || !empty($comments_by_type['pings']) ) : ?>
	<li class="stars"></li>
<?php endif; ?>
</ul>