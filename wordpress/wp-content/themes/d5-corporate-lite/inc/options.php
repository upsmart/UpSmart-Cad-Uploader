<?php
/**
 * D5 CORPORATE Options Page
 * @ Copyright: D5 Creation, All Rights, www.d5creation.com
 */

function optionsframework_option_name() {

	// This gets the theme name from the stylesheet
	$themename = 'd5corporatelite';
	$optionsframework_settings = get_option( 'optionsframework' );
	$optionsframework_settings['id'] = $themename;
	update_option( 'optionsframework', $optionsframework_settings );
}

/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the 'id' fields, make sure to use all lowercase and no spaces.
 *
 * If you are making your theme translatable, you should replace 'd5corporatelite'
 * with the actual text domain for your theme.  Read more:
 * http://codex.wordpress.org/Function_Reference/load_theme_textdomain
 */

function optionsframework_options() {
	
	$options[] = array(
		'name' => 'D5 CORPORATE LITE OPTIONS', 
		'type' => 'heading');
		
	$options[] = array(
		'desc' => '<span class="donation">If you like this FREEE Theme You can consider for a small Donation to us. Your Donation will be spent for the Disadvantaged Children and Students. You can visit our <a href="http://d5creation.com/donate/" target="_blank"><strong>DONATION PAGE</strong></a> and Take your decision.</span><br /><br /><span class="donation">Need Moor Features and Options? Try <a href="http://d5creation.com/2012/07/16/d5corporate/" target="_blank"><strong>D5 CORPORATE</strong></a>.</span>',
		'type' => 'info');
	
	$options[] = array(
		'name' => 'Banner Image', 
		'desc' => 'Upload an image for the Front Page Banner.1050px X 400px image is recommended.', 
		'id' => 'banner-image',
		'std' => get_template_directory_uri() . '/images/slide-image/slide-image1.jpg',
		'type' => 'upload');
		
	$options[] = array(
		'name' => 'Front Page Heading', 
		'desc' => 'Input your heading text here. Plese limit it within 100 Letters.', 
		'id' => 'heading_text',
		'std' => 'World class and industry standard IT services are our passion. We build your ideas True',
		'type' => 'text');
	
// Quotation	
	$options[] = array(
		'desc' => '<span class="featured-area-title">Bottom Quotation</span>', 
		'type' => 'info');
	
	$options[] = array(
		'name' => 'Quote Text', 
		'desc' => 'Input your Quotation here. Plese limit it within 150 Letters.', 
		'id' => 'bottom-quotation',
		'std' => 'All the developers of D5 Creation have come from the disadvantaged part or group of the society. All have established themselves after a long and hard struggle in their life ----- D5 Creation Team',
		'type' => 'textarea');
		
		
	$options[] = array(
		'name' => 'Display the Latest Posts',
		'desc' => 'Check This if you want to display the latest Posts in Front Page.',
		'id' => 'lpost',
		'std' => '',
		'type' => 'checkbox' );	
		

// Front Page Fearured Boxs
	$fbsin=array("1","2","3","4");
	foreach ($fbsin as $fbsinumber) {
	
	$options[] = array(
		'desc' => '<span class="featured-area-title">' . 'Featured Box: 0' . $fbsinumber . '</span>',
		'type' => 'info');
	
	$options[] = array(
		'name' => 'Featured Image', 
		'desc' => 'Upload an image for the Featured Box. 215px X 100px image is recommended.', 
		'id' => 'featured-image' . $fbsinumber,
		'std' => get_template_directory_uri() . '/images/featured-image' . $fbsinumber . '.jpg',
		'type' => 'upload');	
	
		
	$options[] = array(
		'name' => 'Featured Title', 
		'desc' => 'Input your Featured Title here. Plese limit it within 30 Letters.', 
		'id' => 'featured-title' . $fbsinumber,
		'std' => 'd5corporate Theme for Small Business',
		'type' => 'text');

	
	$options[] = array(
		'name' => 'Featured Description', 
		'desc' => 'Input the description of Featured Areas. Please limit the words within 30 so that the layout should be clean and attractive.', 
		'id' => 'featured-description' . $fbsinumber,
		'std' => 'The Customizable Background and other options of d5corporate will give the WordPress Driven Site an attractive look.  d5corporate is super elegant and Professional Responsive Theme which will create the business widely expressed.',
		'type' => 'textarea');

	$options[] = array(
		'name' => 'Featured Link', 
		'desc' => 'Input your Featured Items URL here.', 
		'id' => 'featured-link' . $fbsinumber,
		'std' => '#',
		'type' => 'text');

	}
	
	return $options;
}

/*
 * This is an example of how to add custom scripts to the options panel.
 * This example shows/hides an option when a checkbox is clicked.
 */

add_action('optionsframework_custom_scripts', 'optionsframework_custom_scripts');

function optionsframework_custom_scripts() { ?>

<?php
}