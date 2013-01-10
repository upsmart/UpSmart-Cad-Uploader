<?php
/**
 * Plugin Name: Twitter stream widget
 */

add_action( 'widgets_init', 'gamepress_twitter_load_widgets' );

function gamepress_twitter_load_widgets() {
	register_widget( 'gamepress_Twitter_Widget' );
}

class gamepress_Twitter_Widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function gamepress_Twitter_Widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'gamepress_twitter_widget', 'description' => __('A widget that displays your latest tweets', 'gamepress_twitter_widget') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'gamepress_twitter_widget' );

		/* Create the widget. */
		$this->WP_Widget( 'gamepress_twitter_widget', __('GamePress: Twitter Stream', 'gamepress_twitter_widget'), $widget_ops, $control_ops );
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );

		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'] );
		$username = $instance['username'];
		$number = $instance['number'];
		$text = $instance['text'];
		
		/* Before widget (defined by themes). */
		echo $before_widget;
		echo '<div class="widget twitter-feed">';

		/* Display the widget title if one was input (before and after defined by themes). */
		if ( $title )
			echo $before_title . $title . $after_title;

		?>
		
			<div class="tweet" id="twitter_div">
				<ul id="twitter_update_list"></ul>
			</div>
			<a href="http://twitter.com/<?php echo $username ?>" id="twitter-link"><?php echo $text ?></a>
			<script type="text/javascript" src="http://twitter.com/javascripts/blogger.js"></script>
			<script type="text/javascript" src="http://twitter.com/statuses/user_timeline/<?php echo $username ?>.json?callback=twitterCallback2&amp;count=<?php echo $number ?>"></script>
			
			
		<?php

		/* After widget (defined by themes). */
		echo '</div>';
		echo $after_widget;
	}

	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['username'] = strip_tags( $new_instance['username'] );
		$instance['number'] = strip_tags( $new_instance['number'] );
		$instance['text'] = strip_tags( $new_instance['text'] );

		return $instance;
	}


	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 'title' => __('From Twitter','gamepress'), 'number' => '3','username' =>'', 'text' => __('Follow us on Twitter','gamepress'));
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:','gamepress'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:90%;" />
		</p>
		
		<!-- Twitter username -->
		<p>
			<label for="<?php echo $this->get_field_id( 'username' ); ?>"><?php _e('Twitter username:','gamepress'); ?></label>
			<input id="<?php echo $this->get_field_id( 'username' ); ?>" name="<?php echo $this->get_field_name( 'username' ); ?>" value="<?php echo $instance['username']; ?>" style="width:90%;" />
		</p>

		<!-- Number of posts -->
		<p>
			<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e('Number of tweets to show:','gamepress'); ?></label>
			<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" value="<?php echo $instance['number']; ?>" size="3" />
		</p>
		
		<!-- Follow text -->
		<p>
			<label for="<?php echo $this->get_field_id( 'text' ); ?>"><?php _e('Follow Text:','gamepress'); ?></label>
			<input id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>" value="<?php echo $instance['text']; ?>" style="width:90%;" />
		</p>


	<?php
	}
}

?>