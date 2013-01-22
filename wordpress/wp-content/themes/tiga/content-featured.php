<?php
/**
 * Featured posts 
 *
 * Display featured posts based on sticky post
 * 
 * @package 	Tiga
 * @author		Satrya
 * @license		license.txt
 * @since 		1.0
 *
 */

$sticky = get_option( 'sticky_posts' );

if ( ! empty( $sticky ) ) { ?>

	<section class="featured-posts rslides_container">
		<div class="featuredposts-heading"><?php _e( 'Featured Posts', 'tiga' ); ?></div>

			<ul class="rslides clearfix">

				<?php
				$args = array( 
					'post__in' => $sticky,
					'order' => 'DESC'
				);
				$loop = new WP_Query( $args );

				if ( $loop->have_posts() ) : ?>

					<?php while (  $loop->have_posts() ) :  $loop->the_post(); ?>
						
						<li>
							<article id="post-<?php the_ID(); ?>" <?php post_class( 'featured-slides' ); ?>>
								<div class="slides-item">
								
									<?php if( has_post_thumbnail() ) { ?>

										<figure class="slides-thumbnail">
											<?php the_post_thumbnail( 'tiga-700px', array( 'class' => 'photo thumbnail', 'alt' => get_the_title() ) ); ?>
										</figure>

									<?php } ?>
									
									<div class="slides-content">
										<h2 class="entry-title">
											<a href="<?php esc_url( the_permalink() ); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'tiga' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php esc_attr( the_title() ); ?></a>
										</h2>
										
										<div class="entry-summary">
											<?php the_excerpt(); ?>
										</div>
									</div> <!-- end slides-content -->
							
								</div> <!-- end .slides-item -->
							</article> <!-- end #post-<?php the_ID(); ?> -->
						</li>
						
					<?php endwhile; wp_reset_postdata();?>

				<?php endif; ?>

			</ul> <!-- end .slides -->
			
	</section> <!-- end .featured-posts -->

<?php } ?>