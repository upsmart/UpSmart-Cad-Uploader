<article id="post-<?php the_ID(); ?>" <?php post_class('single'); ?>>
	<header>
		<h2><?php the_title(); ?></h2>
		<div class="entry-meta">
			<?php the_time('F j, Y') ?> | 
			<?php echo __( 'Posted in ', 'gamepress' ).' '. get_the_category_list( __( ', ', 'gamepress' ) ); ?> | 
			<?php comments_popup_link(__('No comments','gamepress'), __('1 comment','gamepress'), __('Comments: %','gamepress')); ?>
		</div>
	</header>
	<div class="entry-content">
		<?php the_content(); ?>
		<div class="clear"></div>
		<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'gamepress' ), 'after' => '</div>' ) ); ?>

		<?php if( has_tag() ) : ?>
			<hr class="divider-dotted" />
		<?php endif; ?>
		
		<div class="alignleft tags"><?php the_tags( __( 'Tagged: ', 'gamepress' ), ', ', ''); ?></div>
    	<?php edit_post_link(__('Edit this entry','gamepress'),'<p>', '</p>'); ?>
	</div>
</article>
	
