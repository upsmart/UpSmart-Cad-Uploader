
					<section class="video-item">

					<div class="img-wrapper">
						<a href="<?php the_permalink(); ?>" class="img-bevel video">
						<?php 
							if (  (function_exists('has_post_thumbnail')) && (has_post_thumbnail())  ) { 
								the_post_thumbnail('video-thumb'); 
							} else { ?>
								<img src="<?php echo get_template_directory_uri(); ?>/images/video-thumb-placeholder.jpg" alt="" />
						<?php } ?>
						</a>
						<div class="entry-header">
							<h5><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
						</div>						
					</div>
					<div class="entry-meta">
						<?php the_time('M d, Y'); ?> |
						<?php echo get_the_term_list( $post->ID, 'gamepress_video_category',  __( 'Posted in ', 'gamepress' ),', ') ?>
					</div>
					</section>