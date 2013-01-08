<?php
/**
 * @package Login_in_widget
 * @version 1.0.1
 */
/*
  Plugin Name: Login in Widget
  Plugin URI: http://www.mimtel.it
  Description: Displays a login in a widget
  Version: 1.0
  Author: Luca Preziati
  Author URI: http://www.mintel.it
  License: GPL2
 */

/*  Copyright 2010  Luca Preziati(luca.preziati@gmail.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

/**
 * page_in_widget_Widget Class
 */
class login_in_widget_Widget extends WP_Widget {
  static $domain =  'login_in_widget';
	/** constructor */
	function login_in_widget_Widget() {
          
    parent::WP_Widget(false, 'Login in widget', array('description' => 'Displays the login and register url or access to profile and logout url in a widget'));
	      
    load_plugin_textdomain(self::$domain, '/wp-content/plugins/login-in-widget/lang' );

  }

	/** @see WP_Widget::widget */
	function widget($args, $instance) {
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);
		$page_id = (int) $instance['page_id'];
		// WPML compatibility
		if ( function_exists('icl_object_id') ) {
		     $page_id = icl_object_id($page_id, "page");
		}
		$more = (int) $instance['more'];

		echo $before_widget;

		if(!$page_id){
			echo 'Page in widget::No Page id set.';
			echo $after_widget;
			return;
		}

		if ($title) {
			echo $before_title . $title . $after_title;
		}

		$page = get_page($page_id, OBJECT, 'display');
		$content = apply_filters('the_content', $this->get_the_content($page, $more));

		echo $content;

		echo $after_widget;
	}

	/** @see WP_Widget::update */
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		return $instance;
	}

	/** @see WP_Widget::form */
	function form($instance) {
    ?>
    <p>No configuration aviable currenty</p>
    <?php
	}


  

  function createLink($link,$title,$description,$class = null){
            if($class == null || $class =='')
              return $output.='<a href="'.$link.'" title="'.$title.'">'.$description.'</a>';
            else
              return $output.='<a href="'.$link.'" title="'.$title.'" class="'.$class.'">'.$description.'</a>';
   }
	/* Local version of get_the_content function,
	 * adapted to suit the widget
	 */
	function get_the_content($post, $more = 1) {
   // add_action('wp_enqueue_scripts', 'login_in_widget_styles');
  
   
    $output.='<ul class="ulTopHeader"><li class="liTopHeader">';
    if ( !is_user_logged_in() ){
     $output.=$this->createLink(wp_login_url( get_permalink()),__("TitleLogin",self::$domain),__("Login",self::$domain));
	   $output.='</li><li class="liTopHeader">';
		 $output.= wp_register('', '',false);
    } else{
     $currentUser = wp_get_current_user();
	   $name = __("Welcome",self::$domain)." ".$currentUser->user_login; 
     $output.= $this->createLink(admin_url( 'profile.php' ),__("TitleProfileModify",self::$domain),$name);
     $output.='</li><li class="liTopHeader">';
		 $output.= $this->createLink(wp_logout_url( get_permalink() ),__("TitleLogout",self::$domain),__("Logout",self::$domain));
		}
		$output.='</li></ul>';
	
  return $output;
  }
}

function login_in_widget_styles(){ 
  // Register the style like this for a theme:  
  // (First the unique name for the style (custom-style) then the src, 
  // then dependencies and ver no. and media type)
    
    wp_register_style( login_in_widget_Widget::$domain, plugins_url() .'/login-in-widget/css/login-in-widget.css');
    // enqueing:
    wp_enqueue_style( login_in_widget_Widget::$domain );
  }


// class page_in_widget_Widget
// register page_in_widget
add_action('widgets_init', create_function('', 'return register_widget("login_in_widget_Widget");'));
add_action('wp_enqueue_scripts', 'login_in_widget_styles');


