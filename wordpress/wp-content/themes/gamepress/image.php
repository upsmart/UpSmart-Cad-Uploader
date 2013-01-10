<?php get_header(); ?>

	<!-- CONTENT -->
	<div id="content">
		<section id="main-content" role="main" class="full-height">
			
			<?php while ( have_posts() ) : the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class('single'); ?>>
                    <header>
                        <h2><a href="<?php echo get_permalink($post->post_parent); ?>" rev="attachment"><?php echo get_the_title($post->post_parent); ?> </a></h2>
                        <div class="entry-meta">
                            <?php the_time('F j, Y') ?> | 
                            <?php echo __( 'Posted in ', 'gamepress' ).' '.get_the_category_list( __( ', ', 'gamepress' ) ); ?> | 
                            <?php comments_popup_link(__('No comments','gamepress'), __('1 comment','gamepress'), __('Comments: %','gamepress')); ?>
                        </div>
                    </header>
                    <div class="entry-content">
                        <div style="text-align: center;"><a href="<?php echo wp_get_attachment_url($post->ID); ?>"><?php echo wp_get_attachment_image( $post->ID, 'medium' ); ?></a></div>      
                        <?php if ( !empty($post->post_excerpt) ) the_excerpt(); ?><br />
                        <div style="float:right;"><?php next_image_link('',__('Next image &raquo;','gamepress')) ?></div><?php previous_image_link('',__('&laquo; Previous image','gamepress')) ?><br />
                    </div>
                </article>
			<?php endwhile; // end of the loop. ?>
			
			<?php
				// If comments are open or we have at least one comment, load up the comment template
				if ( comments_open() || '0' != get_comments_number() )
					comments_template( '', true );
			?>
		</section>
	</div>
	<!-- END CONTENT -->
		
	<?php get_sidebar(); ?>

<?php get_footer(); ?>