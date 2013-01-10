		<div class="slider-wrapper theme-thumbnails">
			
			<div id="slider" class="thumbs nivoSlider <?php echo of_get_option('gamepress_color_scheme','red'); ?>">
		
			<?php
				
				$tmp = $wp_query;
                $variant = of_get_option('gamepress_slider_variant');
				$cat = of_get_option('gamepress_slider_category');
				$limit = 4;
                
                if( $variant == '1') :
                    $cat = of_get_option('gamepress_slider_category');
                    $wp_query = new WP_Query( array( 'cat' => $cat, 'posts_per_page' => $limit ) );
                else :
                    $wp_query = new WP_Query( array( 'posts_per_page' => $limit, 'post_type' => array( 'post', 'gamepress_reviews', 'gamepress_video' ) ) );
                endif;
                
				if(have_posts()) :
					while(have_posts()) :
						the_post();
			?>
			
				<?php 
					if(wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'nivo-slide')) { 
						$image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'nivo-slide');
						$thumb = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'nivo-thumb');
					}else {
						$image[0] = get_template_directory_uri()."/images/nivo-slide-placeholder.jpg";
						$thumb[0] = get_template_directory_uri()."/images/nivo-thumb-placeholder.jpg";
					} 
				?>
			

					<img src="<?php echo $image[0]; ?>" alt="<?php the_title(); ?>" data-thumb="<?php echo $thumb[0]; ?>" title="#htmlcaption_<?php the_ID(); ?>" />
				
				<?php endwhile; ?>
				
			<?php endif; ?>
			</div>
			
			<?php
			if(have_posts()) :
				while(have_posts()) :
					the_post();
			?>
			
			<div id="htmlcaption_<?php the_ID(); ?>" class="nivo-html-caption">
				<h2><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
				<?php gamepress_excerpt('gamepress_excerptlength_teaser', 'gamepress_morelink'); ?>
			</div>
			
			<?php endwhile; ?>

			<?php endif;
			$wp_query = $tmp;
			?>
		
		</div>