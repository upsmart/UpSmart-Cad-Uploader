<?php get_header(); ?>

		<!-- CONTENT -->
		<div id="content">
			<section id="main-content" role="main" class="full-height">

			<?php if ( have_posts() ) : ?>

				<header>
					<h2>
					<?php
						if ( is_post_type_archive() ) :
							printf( __( 'Videos', 'gamepress' ));
						else :
							_e( 'Archives', 'gamepress' );
						endif;
					?>
					</h2>
				</header>

			<?php rewind_posts(); ?>
			
			<div class="archive-content">	
			
			<?php while ( have_posts() ) : the_post(); ?>
				
				<?php get_template_part('video','archive'); ?>

                
			<?php endwhile; // end of the loop. ?>
			
			</div>
			
			<?php else : ?>	
			
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