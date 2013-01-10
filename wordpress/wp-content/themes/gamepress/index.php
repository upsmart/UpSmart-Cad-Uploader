<?php get_header(); ?>

	<!-- CONTENT -->
	<div id="content">
	
		<?php
			if ( $paged < 2  && of_get_option('gamepress_slider_radio', '1')) : 
			
				if (of_get_option('gamepress_slider_type') == '1'):

					get_template_part( 'includes/nivo_default' ); 

				elseif (of_get_option('gamepress_slider_type') == '2'):

					get_template_part( 'includes/nivo_thumbnails' ); 				

				endif;

			endif;
		?>

		<?php if ( have_posts() ) : ?>
		
		<section id="main-content" role="main">

			<?php if ( $paged < 2  && (of_get_option('gamepress_slider_radio') == '2' || of_get_option('gamepress_slider_radio') == '1')) : ?>
		
			<h2 class="section-header"><?php _e('Latest news', 'gamepress'); ?></h2>
			
			<?php endif; ?>
			
				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>

					<?php
						
						if (of_get_option('gamepress_hp_layout') == '2'):
							
							get_template_part('content','bigthumb');
												
						elseif (of_get_option('gamepress_hp_layout') == '3') :

							get_template_part('content', 'smallthumb');
							
						else :

							get_template_part('content');
						
						endif;
					?>

				<?php endwhile; ?>

		<?php else : ?>
		
		<section id="main-content" role="main" class="full-height">
		
				<article id="post-0" class="post no-results not-found">
					<header class="entry-header">
						<h1 class="entry-title"><?php _e( 'Nothing Found', 'gamepress' ); ?></h1>
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