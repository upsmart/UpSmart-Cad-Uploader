
			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			
				<div class="post_date">
					<span class="post_day"><a href="<?php the_permalink(); ?>"><?php the_time('d'); ?></a></span>
					<span class="post_year"><?php the_time('M Y'); ?> </span>
				</div>
				
				<div class="postmeta"><?php do_action('themezee_display_postmeta_single'); ?></div>
			
				<h2 class="post-title"><?php the_title(); ?></h2>
				
				<div class="clear"></div>
				
				<div class="entry">
					<?php the_post_thumbnail('medium', array('class' => 'alignleft')); ?>
					<?php the_content(); ?>
					<div class="clear"></div>
					<?php wp_link_pages(); ?>
					<!-- <?php trackback_rdf(); ?> -->
				</div>
				
				<div class="postinfo"><?php do_action('themezee_display_postinfo_single'); ?></div>

			</div>