<?php
/**
 * Plugin Name: Openmeetings Widget
 * Description: Openmeetings
 * Version: 0.1
 * Author: Shuki Vaknin
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * This plugin was written by Shuki Vaknin. You can send suggestions or bug reports to shukivaknin@gmail.com.
 * Contributions to the plugin are welcomed.
 */

/**
 * Add function to widgets_init that'll load our widget.
 * @since 0.1
 */
add_action( 'widgets_init', 'openmeetings_load_widgets' );

/**
 * Register our widget.
 * 'Openmeetings_Widget' is the widget class used below.
 *
 * @since 0.1
 */

include ("widget.php");


	function openmeetings_load_widgets() {
	register_widget( 'Openmeetings_Widget' );
}

/**
 * Openmeetings Widget class.
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.
 *
 * @since 0.1
 */
class Openmeetings_Widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function Openmeetings_Widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'example', 'description' => __('Openmeetings widget', 'example') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'example-widget' );

		/* Create the widget. */
		$this->WP_Widget( 'example-widget', __('Openmeetings Widget', 'example'), $widget_ops, $control_ops );
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );

		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'] );

		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Display the widget title if one was input (before and after defined by themes). */
		if ( $title )
			echo $before_title . $title . $after_title;
			Openmeetingswidget( $instance );

		/* After widget (defined by themes). */
		echo $after_widget;
	}

	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['hostname'] = strip_tags( $new_instance['hostname'] );
		$instance['admin'] = strip_tags( $new_instance['admin'] );
		$instance['password'] = strip_tags( $new_instance['password'] );
		$instance['roomid'] = strip_tags( $new_instance['roomid'] );
		$instance['widget_text'] = strip_tags( $new_instance['widget_text'] );
		$instance['widget_imgurl'] = strip_tags( $new_instance['widget_imgurl'] );
		$instance['title'] = strip_tags( $new_instance['title'] );

		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 
		'hostname' => __('domain.com:5080/openmeetings', 'hostname'),
		'admin' => __('admin', 'admin'),
		'password' => __('password', 'example'),
		'roomid' => __('', 'example'),
		'widget_text' => __('Chat now!', 'example'),
		'widget_imgurl' => __('URL', 'example'),
		'title' => __('Openmeetings', 'example'));
		
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget hostname: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'hostname' ); ?>"><?php _e('Server URL:', 'hostname'); ?></label>
			<input id="<?php echo $this->get_field_id( 'hostname' ); ?>" name="<?php echo $this->get_field_name( 'hostname' ); ?>" value="<?php echo $instance['hostname']; ?>" style="width:100%;" />
		</p>

		<!-- Your admin: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'admin' ); ?>"><?php _e('Your admin username:', 'admin'); ?></label>
			<input id="<?php echo $this->get_field_id( 'admin' ); ?>" name="<?php echo $this->get_field_name( 'admin' ); ?>" value="<?php echo $instance['admin']; ?>" style="width:100%;" />
		</p>
		<!-- Widget password: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'password' ); ?>"><?php _e('Your admin password:', 'password'); ?></label>
			<input type="password" id="<?php echo $this->get_field_id( 'password' ); ?>" name="<?php echo $this->get_field_name( 'password' ); ?>" value="<?php echo $instance['password']; ?>" style="width:100%;" />
		</p>

		<!-- Your roomid: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'roomid' ); ?>"><?php _e('Your Room ID:', ''); ?></label>
			<input id="<?php echo $this->get_field_id( 'roomid' ); ?>" name="<?php echo $this->get_field_name( 'roomid' ); ?>" value="<?php echo $instance['roomid']; ?>" style="width:100%;" />
		</p>
		<!-- Widget widget_text: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'widget_text' ); ?>"><?php _e('widget_text:', 'Chat now!'); ?></label>
			<input id="<?php echo $this->get_field_id( 'widget_text' ); ?>" name="<?php echo $this->get_field_name( 'widget_text' ); ?>" value="<?php echo $instance['widget_text']; ?>" style="width:100%;" />
		</p>
		<!-- Widget widget_imgurl: Image URL Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'widget_imgurl' ); ?>"><?php _e('widget_imgurl:', 'Chat now!'); ?></label>
			<input id="<?php echo $this->get_field_id( 'widget_imgurl' ); ?>" name="<?php echo $this->get_field_name( 'widget_imgurl' ); ?>" value="<?php echo $instance['widget_imgurl']; ?>" style="width:100%;" />
		</p>
		<!-- Widget title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'Openmeetings'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p><?php
		
		}

}
?>