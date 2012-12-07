<?php

// Exit if accessed directly
if ( !defined('ABSPATH') ) exit;

/**
 * Custom twitter widget
 * 
 * @package     Tiga
 * @author      Satrya
 * @license     license.txt
 * @since       1.0
 *
 */
class tiga_twitter extends WP_Widget {

    /**
     * Widget setup
     */
    function tiga_twitter() {
    
        $widget_ops = array( 
            'classname' => 'tiga_twitter', 
            'description' => __( 'A custom widget to display your recent tweets.', 'tiga' ) 
        );

        $control_ops = array( 
            'width' => 300, 
            'height' => 350, 
            'id_base' => 'tiga_twitter' 
        );

        $this->WP_Widget( 'tiga_twitter', __( '&raquo; Tiga Twitter', 'tiga' ), $widget_ops, $control_ops );
    }

    /**
     * Display widget
     */
    function widget( $args, $instance ) {
        extract( $args, EXTR_SKIP );
 
        $title = $title = apply_filters( 'widget_title', empty($instance['title']) ? __('Recent Tweets', 'tiga') : $instance['title'], $instance, $this->id_base);
        $tw_username = esc_attr(  $instance['tw_username'] );
        $tw_count = $instance['tw_count'];
        $tw_image = $instance['tw_image'];
        $tw_screen_name = $instance['tw_screen_name'];
        $tw_full_name = $instance['tw_full_name'];
        $tw_action_button = $instance['tw_action_button'];
        $tw_follow_button = $instance['tw_follow_button'];

        echo $before_widget;
 
        if (!empty($title))
            echo $before_title . $title . $after_title;
           ?>

            <script type="text/javascript">
                $j = jQuery.noConflict();
                $j(document).ready(function(){

                    $j('#twitter-widget').jTweetsAnywhere({
                        username: '<?php echo $tw_username; ?>',
                        count: <?php echo $tw_count; ?>,
                        showTweetFeed: {
                            showProfileImages: <?php echo !empty( $tw_image ) ? 'true' : 'false'; ?>,
                            showUserScreenNames: <?php echo !empty( $tw_screen_name ) ? 'true' : 'false'; ?>,
                            showUserFullNames: <?php echo !empty( $tw_full_name ) ? 'true' : 'false'; ?>,
                            showActionReply: <?php echo !empty( $tw_action_button ) ? 'true' : 'false'; ?>,
                            showActionRetweet: <?php echo !empty( $tw_action_button ) ? 'true' : 'false'; ?>,
                            showActionFavorite: <?php echo !empty( $tw_action_button ) ? 'true' : 'false'; ?>
                        }
                    });
                    
                });
            </script>

            <div id="twitter-widget"> </div>
            <?php
            if( ! empty( $tw_follow_button ) ) { ?>
                <span class="follow-me">
                    <a href="https://twitter.com/<?php echo $tw_username; ?>" class="twitter-follow-button" data-show-count="false">Follow @<?php echo $tw_username; ?></a>
                    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
                </span>
            <?php
            }

        echo $after_widget;
    }

    /**
     * Update widget
     */
    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = esc_attr( $new_instance['title'] );
        $instance['tw_username'] = esc_attr( $new_instance['tw_username'] );
        $instance['tw_count'] = (int) ( $new_instance['tw_count'] );
        $instance['tw_image'] = isset($new_instance['tw_image']);
        $instance['tw_screen_name'] = isset($new_instance['tw_screen_name']);
        $instance['tw_full_name'] = isset($new_instance['tw_full_name']);
        $instance['tw_action_button'] = isset($new_instance['tw_action_button']);
        $instance['tw_follow_button'] = isset($new_instance['tw_follow_button']);

        return $instance;
    }

    /**
     * Widget setting
     */
    function form( $instance ) {

        /* Set up some default widget settings. */
        $defaults = array(
            'title'         => '',
            'tw_username'   => '',
            'tw_count'      => '',
            'tw_image'      => true,
            'tw_screen_name' => true,
            'tw_full_name'  => true,
            'tw_action_button' => true,
            'tw_follow_button' => true
        );
        $instance = wp_parse_args( (array) $instance, $defaults );
        $title = esc_attr($instance['title']);
        $tw_username = esc_attr( $instance['tw_username'] );
        $tw_count = (int) ( $instance['tw_count'] );
        $tw_image = $instance['tw_image'];
        $tw_screen_name = $instance['tw_screen_name'];
        $tw_full_name = $instance['tw_full_name'];
        $tw_action_button = $instance['tw_action_button'];
        $tw_follow_button = $instance['tw_follow_button'];
    ?>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'tiga' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'tw_username' ) ); ?>"><?php _e( 'Twitter Username:', 'tiga' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'tw_username' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'tw_username' ) ); ?>" type="text" value="<?php echo $tw_username; ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'tw_count' ); ?>"><?php _e( 'Number of Tweets to Retrieve:', 'tiga' ); ?></label>
            <input class="widefat" style="width: 40px;" id="<?php echo $this->get_field_id( 'tw_count' ); ?>" name="<?php echo $this->get_field_name( 'tw_count' ); ?>" min="1" max="10" type="number" value="<?php echo !empty( $tw_count ) ? $tw_count : 5; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'tw_image' ); ?>"><?php _e( 'Show profile picture', 'tiga' ); ?></label>
            <input id="<?php echo $this->get_field_id( 'tw_image' ); ?>" name="<?php echo $this->get_field_name( 'tw_image' ); ?>" type="checkbox" <?php checked( isset( $tw_image ) ? $tw_image : 1 ); ?> />&nbsp;
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'tw_screen_name' ); ?>"><?php _e( 'Show user name', 'tiga' ); ?></label>
            <input id="<?php echo $this->get_field_id( 'tw_screen_name' ); ?>" name="<?php echo $this->get_field_name( 'tw_screen_name' ); ?>" type="checkbox" <?php checked( isset( $tw_screen_name ) ? $tw_screen_name : 1 ); ?> />&nbsp;
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'tw_full_name' ); ?>"><?php _e( 'Show full name', 'tiga' ); ?></label>
            <input id="<?php echo $this->get_field_id( 'tw_full_name' ); ?>" name="<?php echo $this->get_field_name( 'tw_full_name' ); ?>" type="checkbox" <?php checked( isset( $tw_full_name ) ? $tw_full_name : 1 ); ?> />&nbsp;
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'tw_action_button' ); ?>"><?php _e( 'Show action button (reply, retweet and favorites)', 'tiga' ); ?></label>
            <input id="<?php echo $this->get_field_id( 'tw_action_button' ); ?>" name="<?php echo $this->get_field_name( 'tw_action_button' ); ?>" type="checkbox" <?php checked( isset( $tw_action_button ) ? $tw_action_button : 1 ); ?> />&nbsp;
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'tw_follow_button' ); ?>"><?php _e( 'Show follow button', 'tiga' ); ?></label>
            <input id="<?php echo $this->get_field_id( 'tw_follow_button' ); ?>" name="<?php echo $this->get_field_name( 'tw_follow_button' ); ?>" type="checkbox" <?php checked( isset( $tw_follow_button ) ? $tw_follow_button : 1 ); ?> />&nbsp;
        </p>
    <?php
    }

}

?>