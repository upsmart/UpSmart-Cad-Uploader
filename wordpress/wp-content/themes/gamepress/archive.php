<?php get_header(); ?>

	<!-- CONTENT -->
	<div id="content">

		<?php if ( have_posts() ) : ?>
		<section id="main-content" role="main">
						
			<header>
				<h2>
				
				<?php
					if ( is_day() ) :
						printf( __( 'Daily Archives: %s', 'gamepress' ), get_the_date());
					elseif ( is_month() ) :
						printf( __( 'Monthly Archives: %s', 'gamepress' ), get_the_date( 'F Y' ));
					elseif ( is_year() ) :
						printf( __( 'Yearly Archives: %s', 'gamepress' ), get_the_date( 'Y' ));
					elseif ( is_category() ) :
						printf( __( 'Category: %s', 'gamepress' ), single_cat_title('',false));
					elseif ( is_tag() ) :
						printf( __( 'Tagged: %s', 'gamepress' ), single_tag_title('',false));					
					else :
						_e( 'Archives', 'gamepress' );
					endif;
				?>
				</h2>
                <?php 

                if(is_category()):
                    if(category_description( $post->ID)):
                        echo '<p><b>'.category_description( $post->ID).'</b></p>';
                    endif;
                endif;                    
                ?>
			</header>
			
			<?php while ( have_posts() ) : the_post(); ?>
			
				<?php 
						
						if (of_get_option('gamepress_hp_layout') == '2'):
						
							get_template_part('content', 'bigthumb');
						
						elseif (of_get_option('gamepress_hp_layout') == '3') :

                            get_template_part('content', 'smallthumb');						

						else :
						
							get_template_part('content');
						
						endif;
				
				?>
			
			<?php endwhile; // end of the loop. ?>
			
		<?php else : ?>	
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