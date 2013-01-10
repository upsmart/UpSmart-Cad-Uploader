    
			<article id="post-<?php the_ID(); ?>" <?php post_class('list-small-thumb'); ?>>

				<?php if (  (function_exists('has_post_thumbnail')) && (has_post_thumbnail())  ) : ?>	
					<div class="img-wrapper">
						<a href="<?php the_permalink(); ?>" class="img-bevel" rel="bookmark" title="<?php the_title(); ?>" class="img-bevel video">
							<?php the_post_thumbnail('article-thumb'); ?>
						</a>
					</div>
				<?php 

				else : ?>

				<div class="noimage">
				
				<?php endif; ?>

				<div class="entry-date">
					<span class="day"><?php the_time('d'); ?></span><span class="month"><?php the_time('M'); ?></span>
				</div>
				
				<div class="entry-header">
					<h2><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
					<div class="entry-meta">						
							<?php 
								if ( get_post_type() == 'gamepress_reviews') :
									echo get_the_term_list( $post->ID, 'gamepress_review_category',  __( 'Posted in ', 'gamepress' ),', ' );							
								elseif ( get_post_type() == 'gamepress_video' ) :
									echo get_the_term_list( $post->ID, 'gamepress_video_category',  __( 'Posted in ', 'gamepress' ),', ' );							
								else :
									echo __( 'Posted in ', 'gamepress' ).' '. get_the_category_list( __( ', ', 'gamepress' ) ) . ''; 					
								endif; 
							?>							
							| <?php comments_popup_link( __( 'Leave a comment', 'gamepress' ), __( '1 Comment', 'gamepress' ), __( '% Comments', 'gamepress' ),'',__( 'Comments are closed', 'gamepress' ) ); ?>
                        <?php if(get_the_title() == '') { ?>
                            | <a href="<?php the_permalink(); ?>" class="thepermalink" title="<?php _e( 'Permalink', 'gamepress' ); ?>"><?php _e( 'Permalink', 'gamepress' ); ?></a>                        <?php } else { ?>
                        <?php } ?>					</div>
				</div>
				<div class="entry-content">
						<?php gamepress_excerpt('gamepress_excerptlength_teaser', 'gamepress_excerptmore'); ?>	
				</div>

				<?php if ( !has_post_thumbnail() ) : ?>	
				</div>
				<?php endif; ?>
			</article>				