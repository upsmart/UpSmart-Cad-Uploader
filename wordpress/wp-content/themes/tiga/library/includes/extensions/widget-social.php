<?php
/**
 * Custom social buttons widget
 * 
 * @package     Tiga
 * @author      Satrya
 * @license     license.txt
 * @since       Tiga 0.0.1
 *
 */
class tiga_social extends WP_Widget {

	/**
	 * Widget setup
	 */
	function tiga_social() {

		$widget_ops = array( 
			'classname' => 'tiga_social_widget', 
			'description' => __('Social buttons widget.', 'tiga') 
		);

		$control_ops = array( 
			'width' => 300, 
			'height' => 350, 
			'id_base' => 'tiga_social_widget' 
		);

		$this->WP_Widget( 'tiga_social_widget', __('&raquo; Tiga social buttons', 'tiga'), $widget_ops, $control_ops );
	}

	/**
	 * Display widget
	 */
	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
 
		$title = apply_filters('widget_title', $instance['title'] );
		
		echo $before_widget;
 
		if (!empty($title))
			echo $before_title . $title . $after_title;
		?>
		
		<ul class="social-buttons clearfix">
			<?php if(of_get_option('tiga_email')) { ?>
				<li class="has-tip tip-top" data-width="35" title="Email"><a href="mailto:<?php echo esc_attr( of_get_option('tiga_email') ); ?>" target="_blank"><img src="<?php echo get_template_directory_uri(). '/library/img/icons/mail.png'; ?>"></a></li>
			<?php } if(of_get_option('tiga_twitter_username')) { ?>
				<li class="has-tip tip-top" data-width="45" title="Twitter"><a href="http://twitter.com/<?php echo esc_attr( of_get_option('tiga_twitter_username') ); ?>" target="_blank"><img src="<?php echo get_template_directory_uri(). '/library/img/icons/twitter.png'; ?>"></a></li>
			<?php } if(of_get_option('tiga_fb_username')) { ?>
				<li class="has-tip tip-top" data-width="55" title="Facebook"><a href="http://www.facebook.com/<?php echo esc_attr( of_get_option('tiga_fb_username') ); ?>" target="_blank"><img src="<?php echo get_template_directory_uri(). '/library/img/icons/facebook.png'; ?>"></a></li>
			<?php } if(of_get_option('tiga_gplus_username')) { ?>
				<li class="has-tip tip-top" data-width="70" title="Google Plus"><a href="https://plus.google.com/u/<?php echo esc_attr( of_get_option('tiga_gplus_username') ); ?>/" target="_blank"><img src="<?php echo get_template_directory_uri(). '/library/img/icons/google+.png'; ?>"></a></li>
			<?php } if(of_get_option('tiga_ytube_username')) { ?>
				<li class="has-tip tip-top" data-width="50" title="Youtube"><a href="http://www.youtube.com/user/<?php echo esc_attr( of_get_option('tiga_ytube_username') ); ?>" target="_blank"><img src="<?php echo get_template_directory_uri(). '/library/img/icons/youtube.png'; ?>"></a></li>
			<?php } if(of_get_option('tiga_flickr_username')) { ?>
				<li class="has-tip tip-top" data-width="35" title="Flickr"><a href="http://www.flickr.com/photos/<?php echo esc_attr( of_get_option('tiga_flickr_username') ); ?>" target="_blank"><img src="<?php echo get_template_directory_uri(). '/library/img/icons/flickr.png'; ?>"></a></li>
			<?php } if(of_get_option('tiga_linkedin_username')) { ?>
				<li class="has-tip tip-top" data-width="50" title="Linkedin"><a href="http://linkedin.com/in/<?php echo esc_attr( of_get_option('tiga_linkedin_username') ); ?>" target="_blank"><img src="<?php echo get_template_directory_uri(). '/library/img/icons/linkedin.png'; ?>"></a></li>
			<?php } if(of_get_option('tiga_pinterest_username')) { ?>
				<li class="has-tip tip-top" data-width="55" title="Pinterest"><a href="http://pinterest.com/<?php echo esc_attr( of_get_option('tiga_pinterest_username') ); ?>" target="_blank"><img src="<?php echo get_template_directory_uri(). '/library/img/icons/pinterest.png'; ?>"></a></li>
			<?php } if(of_get_option('tiga_dribbble_username')) { ?>
				<li class="has-tip tip-top" data-width="50" title="Dribbble"><a href="http://dribbble.com/<?php echo esc_attr( of_get_option('tiga_dribbble_username') ); ?>" target="_blank"><img src="<?php echo get_template_directory_uri(). '/library/img/icons/dribbble.png'; ?>"></a></li>
			<?php } if(of_get_option('tiga_github_username')) { ?>
				<li class="has-tip tip-top" data-width="40" title="Github"><a href="https://github.com/<?php echo esc_attr( of_get_option('tiga_github_username') ); ?>" target="_blank"><img src="<?php echo get_template_directory_uri(). '/library/img/icons/github.png'; ?>"></a></li>
			<?php } if(of_get_option('tiga_lastfm_username')) { ?>
				<li class="has-tip tip-top" data-width="50" title="Last FM"><a href="http://www.last.fm/user/<?php echo esc_attr( of_get_option('tiga_lastfm_username') ); ?>" target="_blank"><img src="<?php echo get_template_directory_uri(). '/library/img/icons/last_fm.png'; ?>"></a></li>
			<?php } if(of_get_option('tiga_vimeo_username')) { ?>
				<li class="has-tip tip-top" data-width="40" title="Vimeo"><a href="http://vimeo.com/<?php echo esc_attr( of_get_option('tiga_vimeo_username') ); ?>" target="_blank"><img src="<?php echo get_template_directory_uri(). '/library/img/icons/vimeo.png'; ?>"></a></li>
			<?php } if(of_get_option('tiga_tumblr_username')) { ?>
				<li class="has-tip tip-top" data-width="45" title="Tumblr"><a href="http://<?php echo esc_attr( of_get_option('tiga_tumblr_username') ); ?>.tumblr.com" target="_blank"><img src="<?php echo get_template_directory_uri(). '/library/img/icons/tumblr.png'; ?>"></a></li>
			<?php } if(of_get_option('tiga_instagram_username')) { ?>
				<li class="has-tip tip-top" data-width="60" title="Instagram"><a href="http://statigr.am/<?php echo esc_attr( of_get_option('tiga_instagram_username') ); ?>" target="_blank"><img src="<?php echo get_template_directory_uri(). '/library/img/icons/instagram.png'; ?>"></a></li>
			<?php } ?>
		</ul>
		
		<?php
		echo $after_widget;
	}
 	
	/**
	 * Update widget
	 */
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = esc_attr( $new_instance['title'] );

		return $instance;
	}

	/**
	 * Widget setting
	 */
	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
		$title = $instance['title'];
	?>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'tiga' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>

	<?php
	}
 
}
?>