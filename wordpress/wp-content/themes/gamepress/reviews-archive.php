						<?php 
							if(wp_get_attachment_image_src(get_post_meta($post->ID, "gamepress_cover", true), 'gamecover')) { 
								$image = wp_get_attachment_image_src(get_post_meta($post->ID, "gamepress_cover", true), 'gamecover-thumb');
						?>
						<section class="review-item">

							<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>" class="img-bevel review-thumb">
								<img src="<?php echo $image[0]; ?>" class="cover" alt="<?php the_title(); ?>" />
							</a>
						<?php } else { ?>
						
						<section class="review-item noimage">
						
						<?php } ?>
						
						<div class="review-content">
							<div class="rating-bar">
								<?php echo gamepress_rating(get_post_meta($post->ID, "gamepress_score", true)); ?>
							</div>

							<h3><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" ><?php the_title(); ?></a></h3>
							<div class="entry-meta">
							<?php the_time('M d, Y'); ?> | 
							<?php echo get_the_term_list( $post->ID, 'gamepress_review_category',  __( 'Posted in ', 'gamepress' ),', ',' |' ) ?>
							<?php if ( comments_open() || ( '0' != get_comments_number() && ! comments_open() ) ) : ?>
								<?php comments_popup_link( __( 'Leave a comment', 'gamepress' ), __( '1 Comment', 'gamepress' ), __( '% Comments', 'gamepress' ) ); ?>
							<?php endif; ?>							
							</div>

							<?php gamepress_excerpt('gamepress_excerptlength_teaser', 'gamepress_excerptmore'); ?>
						</div>
					</section>