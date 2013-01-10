<?php
/**
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 * By default it uses the theme name, in lowercase and without spaces, but this can be changed if needed.
 * If the identifier changes, it'll appear as if the options have been reset.
 */

function optionsframework_option_name() {

	// This gets the theme name from the stylesheet
	$themename = get_option( 'stylesheet' );
	$themename = preg_replace("/\W/", "_", strtolower($themename) );

	$optionsframework_settings = get_option( 'optionsframework' );
	$optionsframework_settings['id'] = $themename;
	update_option( 'optionsframework', $optionsframework_settings );
}

/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the 'id' fields, make sure to use all lowercase and no spaces.
 *
 * If you are making your theme translatable, you should replace 'options_framework_theme'
 * with the actual text domain for your theme.  Read more:
 * http://codex.wordpress.org/Function_Reference/load_theme_textdomain
 */

function optionsframework_options() {
	
	// Color schemes
	$color_scheme = array("red" => __('Red', 'gamepress'),"green" => __('Green', 'gamepress'),"blue" => __('Blue', 'gamepress'),"orange" =>__('Orange', 'gamepress'));
	// HomePage layout
	$hp_layout = array("1" => __('Display full content for each post', 'gamepress'),"2" => __('Display excerpt and big thumbnail', 'gamepress'), "3" => __('Display excerpt and small thumbnail', 'gamepress'));
	// HomePage include
	$hp_include = array(
		'gamepress_reviews' => __('Game Reviews', 'gamepress'),
		'gamepress_video' => __('Videos', 'gamepress')
	);
	$radio = array("0" => __('No', 'gamepress'),"1" => __('Yes', 'gamepress'));
	$slider_variant = array("0" => __('Newest posts', 'gamepress'),"1" => __('Posts from selected category', 'gamepress'));
	$slider_type = array ("1" => __('Default Nivo Slider','gamepress'),"2" => __('Nivo Slider with thumbs navigation', 'gamepress'));
	// Pull all the categories into an array
	$options_categories = array();  
    $args = array();
	$categories_obj = get_categories( );
	$reviews_obj = get_categories( array( 'taxonomy' => 'gamepress_review_category' ) );
	$video_obj = get_categories( array( 'taxonomy' => 'gamepress_video_category' ) );
    
    $options_categories_obj = array_merge((array)$categories_obj, (array)$reviews_obj, (array)$video_obj );

	foreach ($options_categories_obj as $category) {
    	$options_categories[$category->cat_ID] = $category->cat_name;
	}

	// Pull all the pages into an array
	$options_pages = array();  
	$options_pages_obj = get_pages('sort_column=post_parent,menu_order');
	$options_pages[''] = 'Select a page:';
	foreach ($options_pages_obj as $page) {
    	$options_pages[$page->ID] = $page->post_title;
	}
		
	// If using image radio buttons, define a directory path
	$imagepath =  get_stylesheet_directory_uri() . '/images/';
		
	$options = array();
		
	$options[] = array( "name" => __('Basic settings', 'gamepress'),
						"type" => "heading");					

	$options[] = array( "name" => __('Color scheme', 'gamepress'),
						"desc" => __('Select color scheme.', 'gamepress'),
						"id" => "gamepress_color_scheme",
						"std" => "one",
						"type" => "select",
						"class" => "mini", //mini, tiny, small
						"options" => $color_scheme);

    $options[] = array( "name" => __('Custom logo image', 'gamepress'),
						"desc" => __('You can upload custom image for your website logo (optional).', 'gamepress'),
						"id" => "gamepress_logo_image",
						"type" => "upload");

	$options[] = array( "name" => __('Do You want to use custom favicon?','gamepress'),
						"id" => "gamepress_favicon_radio",
						"std" => "0",
						"type" => "radio",
						"options" => $radio);					

	$options[] = array( "name" => __('Favicon URL', 'gamepress'),
						"desc" => __('If You choose to use custom favicon, input here FULL URL to the favicon.ico image.', 'gamepress'),
						"id" => "gamepress_favicon_url",
						"type" => "text",
                        "class" => "medium");

	$options[] = array( "name" => __('Search', 'gamepress'),
						"desc" => __('Do you want to include search in menu bar?', 'gamepress'),
						"id" => "gamepress_search",
						"std" => 1,
						"type" => "radio",
						"options" => $radio);	                       
                        
	$options[] = array( "name" => __('Home Page settings', 'gamepress'),
						"type" => "heading");							

	$options[] = array( "name" => __('Home Page layout', 'gamepress'),
						"id" => "gamepress_hp_layout",
						"std" => "1",
						"type" => "select",
						"options" => $hp_layout);
					
	$options[] = array( "name" => __('Select custom post types, that You want to include on the home page', 'gamepress'),
						"desc" => __('These custom post types will be also included in archives pages', 'gamepress'),
						"id" => "gamepress_hp_include",
                        "std" => array(
                                    'gamepress_reviews' => __('Game Reviews', 'gamepress'),
                                    'gamepress_video' => __('Videos', 'gamepress')
                               ),
						"type" => "multicheck",
						"options" => $hp_include);

						
	$options[] = array( "name" => __('Do You want to display image slider on the Home Page?','gamepress'),
						"id" => "gamepress_slider_radio",
						"std" => 0,
						"type" => "radio",
						"options" => $radio);

	$options[] = array( "name" => __('Slider should display:','gamepress'),
						"id" => "gamepress_slider_variant",
						"std" => 1,
						"type" => "select",
                        "class" => "hidden_control",
						"options" => $slider_variant);                        
                        
	$options[] = array( "name" => __('Select Category for Featured Posts slider', 'gamepress'),
						"desc" => __('Posts from this category will be rotating in image slider on the Home Page. IMPORTANT: Make sure all posts in this category have Featured Image set.', 'gamepress'),
						"id" => "gamepress_slider_category",
						"type" => "select",
                        "class" => "hidden",
						"options" => $options_categories);	
                        						
	$options[] = array( "name" => __('Slider type', 'gamepress'),
						"id" => "gamepress_slider_type",
						"std" => "1",
						"type" => "select",
						"class" => "mini hidden_control", //mini, tiny, small
						"options" => $slider_type);

    $options[] = array( "name" => __('Number of posts in slider', 'gamepress'),
						"id" => "gamepress_slider_limit",
                        "desc" => __('Pertains only to Default Nivo Slider. Nivo Slider with thumbs navigation shows always maximum of 4 posts.','gamepress'),
						"std" => "1",
						"type" => "text",
						"class" => "small hidden"); //mini, tiny, small);
                        
    return $options;
}