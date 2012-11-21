<?php
/*
Plugin Name: Default Author Category
Plugin URI: http://roesapps.com/2011/04/default-author-category-wordpress-plugin/
Description: Allows each user profile to set a default category for whenever they create a post overriding the site wide default category.  Each profile will have a place to set that user's default category and then every time they create a new post, it will default to this category rather than the default category on the site level.
Author: Courtney Roes
Author URI: http://RoesApps.com
Version: 1.0

        Copyright (c) 2011 Courtney Roes (http://RoesApps.com)
        Default Author Category is released under the GNU General Public License (GPL)
        http://www.gnu.org/licenses/gpl-2.0.txt
*/



/*  

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.


This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/



/**

* Guess the wp-content and plugin urls/paths

*/

// Pre-2.6 compatibility

if ( ! defined( 'WP_CONTENT_URL' ) )
      define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content' );

if ( ! defined( 'WP_CONTENT_DIR' ) )
      define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );

if ( ! defined( 'WP_PLUGIN_URL' ) )
      define( 'WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins' );

if ( ! defined( 'WP_PLUGIN_DIR' ) )
      define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );





if (!class_exists('DAC_HCR')) {

    class DAC_HCR {

        //This is where the class variables go, don't forget to use @var to tell what they're for

        /**

        * @var string The options string name for this plugin

        */

        var $optionsName = 'DAC_HCR_options';

        

        /**

        * @var string $localizationDomain Domain used for localization

        */

        var $localizationDomain = "DAC_HCR";

        

        /**

        * @var string $pluginurl The path to this plugin

        */ 

        var $thispluginurl = '';

        /**

        * @var string $pluginurlpath The path to this plugin

        */

        var $thispluginpath = '';

            

        /**

        * @var array $options Stores the options for this plugin

        */

        var $options = array();

        

        //Class Functions

        /**

        * PHP 4 Compatible Constructor

        */

        function DAC_HCR(){$this->__construct();}

        

        /**

        * PHP 5 Constructor

        */        

        function __construct(){

            //Language Setup

            $locale = get_locale();

            $mo = dirname(__FILE__) . "/languages/" . $this->localizationDomain . "-".$locale.".mo";

            load_textdomain($this->localizationDomain, $mo);



            //"Constants" setup

            $this->thispluginurl = PLUGIN_URL . '/' . dirname(plugin_basename(__FILE__)).'/';

            $this->thispluginpath = PLUGIN_PATH . '/' . dirname(plugin_basename(__FILE__)).'/';


            

            //Actions        

			add_action( 'show_user_profile', array(&$this, 'DAC_HCR_profile_fields' ));
			add_action( 'edit_user_profile', array(&$this, 'DAC_HCR_profile_fields' ));
			
			add_action( 'personal_options_update', array(&$this,'dac_HCR_extra_profile_fields' ));
			add_action( 'edit_user_profile_update', array(&$this,'dac_HCR_extra_profile_fields' ));
			
			add_action('save_post', array(&$this,'dac_HCR_set_default_cat'), 10, 2);
			
			add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array(&$this, 'filter_plugin_actions'), 10, 2 );


        }

		/*
		
		* ============================
		
		* Add Donation link
		
		* ============================
		*/
		
		function filter_plugin_actions($links, $file) {

           //If your plugin is under a different top-level menu than Settiongs (IE - you changed the function above to something other than add_options_page)

           //Then you're going to want to change options-general.php below to the name of your top-level page

           $settings_link = '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=BL6YNPL7546WE" target="_blank">' . __('Donate') . '</a>';

           array_unshift( $links, $settings_link ); // before other links



           return $links;

        }
		
		/*
		
		* ============================
		
		* Functions for Default Author Category
		
		* =============================
		
		*/
		
		function DAC_HCR_profile_fields( $user ) { ?>

	<h3>Default category for when this user posts</h3>

	<table class="form-table">

		<tr>
			<th><label for="DAC_Cat">Default Category</label></th>

			<td>
            <?php 

						$args = array(
				'show_option_all'    => '',
				'show_option_none'   => '',
				'orderby'            => 'ID', 
				'order'              => 'ASC',
				'show_last_update'   => 0,
				'show_count'         => 0,
				'hide_empty'         => 0, 
				'child_of'           => 0,
				'exclude'            => '',
				'echo'               => 1,
				'selected'           => get_the_author_meta('dac_cat',$user->ID),
				'hierarchical'       => 0, 
				'name'               => 'dac_cat',
				'id'                 => 'dac_cat',
				'class'              => 'postform',
				'depth'              => 0,
				'tab_index'          => 0,
				'taxonomy'           => 'category',
				'hide_if_empty'      => false );
			
			wp_dropdown_categories($args); ?>
            
		</tr>

	</table>
<?php }


		
		function dac_HCR_extra_profile_fields( $user_id ) {
		
			if ( !current_user_can( 'edit_user', $user_id ) )
				return false;
		
			update_usermeta( $user_id, 'dac_cat', $_POST['dac_cat'] );
		}
		
		
		function dac_HCR_set_default_cat( $post_id ) {
			
			//Check if other categories have been selected
			$categories = get_the_category($post_id);
			if (empty($categories)) {
							//Get Default Author Category
				$postinfo = get_post( $post_id );
				$authorid = $postinfo->post_author;
				$dac = get_the_author_meta('dac_cat',$authorid);
				
				//Set default categories
				wp_set_post_categories( $post_id, array($dac) );
			}
			
		}      

        

  } //End Class

} //End if class exists statement



//instantiate the class

if (class_exists('DAC_HCR')) {

    $DAC_HCR_var = new DAC_HCR();

}

?>
