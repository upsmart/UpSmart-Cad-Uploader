<?php get_header(); ?>

	<!-- CONTENT -->
	<div id="content">

		<?php if ( have_posts() ) : ?>
		<section id="main-content" role="main" class="full-height">
						
			<header>
				<h2>
				<?php /* If this is a review archive */ if (is_tax('gamepress_review_category')) { ?>
				<?php $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) ); echo __('Reviews:','gamepress') .' '. $term->name; ?>  
				<?php /* If this is a video archive */ } elseif (is_tax('gamepress_video_category')) { ?>
				<?php $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) ); echo __('Videos:','gamepress') .' '. $term->name; ?>
				<?php } ?>

				</h2>	
			</header>

			<div class="archive-content">
			<?php while (have_posts()) : the_post(); ?>
			<?php
                
				if ( get_post_type() == 'gamepress_reviews' ) : ?>

					<?php get_template_part('reviews','archive'); ?>				

					
				<?php elseif ( get_post_type() == 'gamepress_video' ) : ?>
				
					<?php get_template_part('video','archive'); ?>
					
				<?php else:	?>
				
					<?php get_template_part('content'); ?>
				
				<?php endif; ?>

				<?php endwhile; // end of the loop. ?>
				
			<?php else : ?>	
			</div>

		<section id="main-content" role="main" class="full-height">
		
			<article id="post-0" class="post no-results not-found">
				<header>
					<h2 class="entry-title"><?php _e( 'Nothing Found', 'gamepress' ); ?></h2>
				</header><!-- .entry-header -->

				<div class="entry-content">
					<p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'gamepress' ); ?></p>
					<?php get_search_form(); ?>
				</div><!-- .entry-content -->
			</article><!-- #post-0 -->

			<?php endif; ?>	

		</section>
		<?php
			if(function_exists('wp_pagenavi')) :
				wp_pagenavi(); 
			else :
		?>
			<div class="wp-pagenavi">
				<div class="alignleft"><?php next_posts_link('&laquo; '.__('Older posts','gamepress')) ?></div> 
				<div class="alignright"><?php previous_posts_link(__('Newer posts','gamepress').' &raquo;') ?></div>
			</div>
		<?php endif; ?>
	</div>
	<!-- END CONTENT -->
		
	<?php get_sidebar(); ?>

<?php get_footer(); ?>