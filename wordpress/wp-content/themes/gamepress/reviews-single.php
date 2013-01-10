<article id="post-<?php the_ID(); ?>" <?php post_class('single review'); ?>>

		<?php 
		if(wp_get_attachment_image_src(get_post_meta($post->ID, "gamepress_cover", true), 'gamecover')) { 
            $image = wp_get_attachment_image_src(get_post_meta($post->ID, "gamepress_cover", true), 'gamecover');
		?>
		<header>
			<img src="<?php echo $image[0]; ?>" class="cover" alt="<?php the_title(); ?>" />
		<?php }else { ?>		
		
		<header class="noimage">
		
		<?php } ?>
		
		<h2><?php the_title(); ?></h2>
		<h3 class="subtitle">
			<?php if(get_post_meta($post->ID, "gamepress_intro", true)) { ?><?php echo get_post_meta($post->ID, "gamepress_intro", true); ?><?php } ?>
		</h3>
		<div class="entry-meta">
			<?php the_time('F j, Y') ?> | 		
			<?php echo get_the_term_list( $post->ID, 'gamepress_review_category',  __( 'Posted in ', 'gamepress' ),', ',' |' ) ?>
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
	
