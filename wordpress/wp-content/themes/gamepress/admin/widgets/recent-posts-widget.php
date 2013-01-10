<?php
/**
 * Plugin Name: Recent Posts
 */

add_action( 'widgets_init', 'gamepress_recent_posts_load_widgets' );

function gamepress_recent_posts_load_widgets() {
	register_widget( 'gamepress_recent_posts_widget' );
}

class gamepress_recent_posts_widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function gamepress_recent_posts_widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'gamepress_tabbed_widget', 'description' => __('Displays a list of recent posts, reviews and videos in a tabbed widget', 'gamepress') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'gamepress_tabbed_widget' );

		/* Create the widget. */
		$this->WP_Widget( 'gamepress_tabbed_widget', __('GamePress: Recent news, reviews & videos', 'gamepress'), $widget_ops, $control_ops );
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );

		/* Our variables from the widget settings. */
		$number = $instance['number'];

		?>
		<div class="widget reviews_widget">
			<div class="tabs-wrapper">
			
			<ul class="tabs-nav tabs">
				<li><a href="#"><?php _e('Reviews','gamepress'); ?></a></li>
				<li><a href="#"><?php _e('News','gamepress'); ?></a></li>
				<li><a href="#"><?php _e('Videos','gamepress'); ?></a></li>
			</ul>
		
			<ul id="tabs-1" class="reviews pane">
			<?php
			
			/** Get all reviews **/
			
			$query_r = array('posts_per_page' => $number, 'nopaging' => 0, 'post_status' => 'publish', 'post_type' => 'gamepress_reviews');
			$reviews = new WP_Query($query_r);
			if ($reviews->have_posts()) :			
				
			?>
				<?php  while ($reviews->have_posts()) : $reviews->the_post(); ?>
				
				<li>
					<?php if ((function_exists('has_post_thumbnail')) && (has_post_thumbnail())  ) : ?>
					<div class="entry-thumb">
					<a href="<?php echo get_permalink() ?>" class="img-bevel" rel="bookmark" title="<?php the_title(); ?>">
						<?php the_post_thumbnail(); ?>
					</a>
					</div>
					<?php endif; ?>

					<div class="entry-wrapper">
						<h6 class="entry-title"><a href="<?php echo get_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h6>
						<div class="entry-meta">
							<?php the_time('F j, Y') ?> |
							<?php comments_popup_link(__('No comments','gamepress'), __('1 comment','gamepress'), __('Comments: %','gamepress')); ?>
						</div>
						
						<?php if(get_post_meta(get_the_ID(), "gamepress_score", true)) : ?>
						<div class="rating-bar">
							<?php echo gamepress_rating(get_post_meta(get_the_ID(), "gamepress_score", true)); ?>
						</div>
						<?php endif; ?>
					</div>
				</li>
				
				<?php endwhile; ?>			
			<?php 
			else: _e('No game reviews yet.','gamepress');
			endif; 
            wp_reset_query();
			?>
				
			</ul>

			<ul id="tabs-2" class="news pane">
				
			<?php
			
			$recent_posts = new WP_Query(array('showposts' => $number,'post_status' => 'publish'));
			
			?>
			
				<?php while($recent_posts->have_posts()): $recent_posts->the_post(); ?>

				<li>
					<?php if ((function_exists('has_post_thumbnail')) && (has_post_thumbnail())  ) : ?>
					<div class="entry-thumb">
					<a href="<?php echo get_permalink() ?>" class="img-bevel" rel="bookmark" title="<?php the_title(); ?>">
						<?php the_post_thumbnail(); ?>
					</a>
					</div>
					<?php endif; ?>
					<div class="entry-wrapper">
						<h6 class="entry-title"><a href="<?php echo get_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h6>
						<div class="entry-meta">
							<?php the_time('F j, Y') ?> |
							<?php comments_popup_link(__('No comments','gamepress'), __('1 comment','gamepress'), __('Comments: %','gamepress')); ?>
						</div>
					</div>
				</li>

				<?php endwhile; ?>
			</ul>
			<?php wp_reset_query(); ?>
			
			<ul id="tabs-3" class="video pane">
			<?php
			
			/** Get all videos **/
			
			$query_v = array('showposts' => $number, 'nopaging' => 0, 'post_status' => 'publish', 'post_type' => 'gamepress_video');
			$videos = new WP_Query($query_v);
			if ($videos->have_posts()) :
				
			?>
				<?php  while ($videos->have_posts()) : $videos->the_post(); ?>
				
				<li>
					<?php if ((function_exists('has_post_thumbnail')) && (has_post_thumbnail())  ) : ?>
					<div class="entry-thumb">
					<a href="<?php echo get_permalink() ?>" class="img-bevel" rel="bookmark" title="<?php the_title(); ?>">
						<?php the_post_thumbnail(); ?>
					</a>
					</div>
					<?php endif; ?>

					<div class="entry-wrapper">
						<h6 class="entry-title"><a href="<?php echo get_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h6>
						<div class="entry-meta">
							<?php the_time('F j, Y') ?> |
							<?php comments_popup_link(__('No comments','gamepress'), __('1 comment','gamepress'), __('Comments: %','gamepress')); ?>
						</div>
					</div>
				</li>
				
				<?php endwhile; ?>
			
			<?php 
			else: _e('No videos yet.','gamepress');
			endif; 
            wp_reset_query();
			?>	
			</ul>
		</div>
		<?php

		/* After widget (defined by themes). */
		echo $after_widget;
	}

	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['number'] = strip_tags( $new_instance['number'] );

		return $instance;
	}


	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array('number' => 3);
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		
		<!-- Number of posts -->
		<p>
			<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e('Number of posts to show','gamepress') ?>:</label>
			<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" value="<?php echo $instance['number']; ?>" size="3" />
		</p>


	<?php
	}
}

?>