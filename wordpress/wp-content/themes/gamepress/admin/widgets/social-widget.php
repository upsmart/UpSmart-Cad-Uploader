<?php
/**
 * Plugin Name: Social Widget
 */

add_action( 'widgets_init', 'gamepress_social_load_widgets' );

function gamepress_social_load_widgets() {
	register_widget( 'gamepress_social_widget' );
}

class gamepress_social_widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function gamepress_social_widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'gamepress_social_widget', 'description' => __('Displays icons with linked to RSS / Facebook/ Twitter / Flickr / YouTube', 'gamepress') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'gamepress_social_widget' );

		/* Create the widget. */
		$this->WP_Widget( 'gamepress_social_widget', __('GamePress: Social icons', 'gamepress'), $widget_ops, $control_ops );
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );

		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'] );
		$rss = $instance['rss'];
		$facebook = $instance['facebook'];
		$twitter = $instance['twitter'];
		$youtube = $instance['youtube'];
		$flickr = $instance['flickr'];
		
		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Display the widget title if one was input (before and after defined by themes). */
		if ( $title )
			echo $before_title . $title . $after_title;

		?>
			<div class="sidebar-social">
				<?php if($rss) { ?><a href="<?php echo $rss; ?>" title="<?php _e('Subscribe to RSS feed','gamepress') ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/icons/rss.png" alt="Subskrybuj RSS" /></a><?php } ?>
				<?php if($facebook) { ?><a href="<?php echo $facebook; ?>" title="<?php _e('Follow me on Facebook','gamepress') ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/icons/facebook.png" alt="Facebook" /></a><?php } ?>
				<?php if($twitter) { ?><a href="<?php echo $twitter; ?>" title="<?php _e('Follow me on Twitter','gamepress') ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/icons/twitter.png" alt="Twitter" /></a><?php } ?>
				<?php if($youtube) { ?><a href="<?php echo $youtube; ?>" title="<?php _e('Follow me on YouTube','gamepress') ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/icons/youtube.png" alt="YouTube" /></a><?php } ?>
				<?php if($flickr) { ?><a href="<?php echo $flickr; ?>" title="<?php _e('Follow me on Flickr','gamepress') ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/icons/flickr.png" alt="Flickr" /></a><?php } ?>
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
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['rss'] = $new_instance['rss'];
		$instance['facebook'] = $new_instance['facebook'];
		$instance['twitter'] = $new_instance['twitter'];
		$instance['youtube'] = $new_instance['youtube'];
		$instance['flickr'] = $new_instance['flickr'];

		return $instance;
	}


	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 'title' => __('Follow me','gamepress_social_widget'), 'rss' => '', 'facebook' => '', 'twitter' => '', 'youtube' => '', 'flickr' => '');
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title','gamepress') ?>:</label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:90%;" />
		</p>

		<!-- RSS URL -->
		<p>
			<label for="<?php echo $this->get_field_id( 'rss' ); ?>"><?php _e('URL address of your RSS feed','gamepress') ?>:</label>
			<input id="<?php echo $this->get_field_id( 'rss' ); ?>" name="<?php echo $this->get_field_name( 'rss' ); ?>" value="<?php echo $instance['rss']; ?>" style="width:90%;" />
			<small><?php _e('Enter full feed URL. If you don\'t want to display this, leave empty.','gamepress') ?></small>
		</p>
		
		<!-- Facebook URL -->
		<p>
			<label for="<?php echo $this->get_field_id( 'facebook' ); ?>"><?php _e('URL address of your Facebook profile or page','gamepress') ?>:</label>
			<input id="<?php echo $this->get_field_id( 'facebook' ); ?>" name="<?php echo $this->get_field_name( 'facebook' ); ?>" value="<?php echo $instance['facebook']; ?>" style="width:90%;" />
			<small><?php _e('Enter full URL of your Facebook profile or page. If you don\'t want to display this, leave empty.','gamepress') ?></small>
		</p>
		<!-- Twitter URL -->
		<p>
			<label for="<?php echo $this->get_field_id( 'twitter' ); ?>"><?php _e('URL address of your Twitter profile page','gamepress') ?>:</label>
			<input id="<?php echo $this->get_field_id( 'twitter' ); ?>" name="<?php echo $this->get_field_name( 'twitter' ); ?>" value="<?php echo $instance['twitter']; ?>" style="width:90%;" />
			<small><?php _e('Enter full URL of your Twitter profile page. If you don\'t want to display this, leave empty.','gamepress') ?></small>
		</p>
		<!-- YouTube URL -->
		<p>
			<label for="<?php echo $this->get_field_id( 'youtube' ); ?>"><?php _e('URL address of your YouTube profile page','gamepress') ?>:</label>
			<input id="<?php echo $this->get_field_id( 'youtube' ); ?>" name="<?php echo $this->get_field_name( 'youtube' ); ?>" value="<?php echo $instance['youtube']; ?>" style="width:90%;" />
			<small><?php _e('Enter full URL of your YouTube profile page. If you don\'t want to display this, leave empty.','gamepress') ?></small>
		</p>		<!-- Flickr URL -->
		<p>
			<label for="<?php echo $this->get_field_id( 'flickr' ); ?>">Adres profliu na Flickr:</label>
			<input id="<?php echo $this->get_field_id( 'flickr' ); ?>" name="<?php echo $this->get_field_name( 'flickr' ); ?>" value="<?php echo $instance['flickr']; ?>" style="width:90%;" />
			<small><?php _e('Enter full URL to your Flickr profile. If you don\'t want to display this, leave empty.','gamepress') ?></small>
		</p>

	<?php
	}
}

?>