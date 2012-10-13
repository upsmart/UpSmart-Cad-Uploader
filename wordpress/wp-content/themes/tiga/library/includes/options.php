<?php
/**
 * Theme options
 *
 * Theme options functions for Tiga theme
 *
 * @package 	Tiga
 * @author		Satrya
 * @license		license.txt
 * @since 		Tiga 0.0.1
 *
 */

function optionsframework_option_name() {

	$themename = get_option( 'stylesheet' );
	$themename = preg_replace("/\W/", "_", strtolower($themename) );

	$optionsframework_settings = get_option( 'optionsframework' );
	$optionsframework_settings['id'] = $themename;
	update_option( 'optionsframework', $optionsframework_settings );
}

/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 *  
 */

function optionsframework_options() {
	
	$tiga_background = array(
		'color' => '',
		'image' => '',
		'repeat' => 'repeat',
		'position' => 'top center',
		'attachment'=>'scroll' 
	);
	
	$tiga_numbers = array(
		'2' => __('Two', 'tiga'), 
		'3' => __('Three', 'tiga'), 
		'4' => __('Four', 'tiga'), 
		'5' => __('Five', 'tiga'), 
		'6' => __('Six', 'tiga'), 
		'7' => __('Seven', 'tiga'), 
		'8' => __('Eight', 'tiga'), 
		'9' => __('Nine', 'tiga'), 
		'10' => __('Ten', 'tiga') 
	);
	
	$tiga_select = array(
		'enable' => __('Enable', 'tiga'), 
		'disable' => __('Disable', 'tiga') 
	);

	$tiga_social = array(
		'tiga_post' => __('Single post', 'tiga'),
		'tiga_page' => __('Page', 'tiga'),
		'tiga_both' => __('Both', 'tiga'),
		'tiga_none' => __('None', 'tiga')
	);
	
	$imagepath =  get_template_directory_uri() . '/library/admin/images/layouts/';
	$patternpath =  get_template_directory_uri() . '/library/img/bg/';
		
	$options = array();

	$options[] = array( 
		'name' => __('Announcement', 'tiga'),
		'type' => 'heading'
	);

	$options[] = array( 
		"name" => 'Announcement',
		"desc" => "<p>In shortly I will release Tiga version 1.0, a lot of changes will add to this version. Here's the list:</p>
			<ul class='ul-disc'>
				<li>No more sticky post slider, the slider will changed with custom post type.</li>
				<li>Social settings, will moved to the Social widget it self.</li>
				<li>Home page layout, will moved to page-templates.</li>
				<li>Remove the custom background, but changed with the WordPress custom background function.</li>
				<li>No more meta verification settings, I will change it with custom header script.</li>
				<li>No more ads setting, in version 1.0 you have ability to add widget below post title and below post content. So, you can display the ads from the widget.</li>
				<li>You don't need the buddypress child theme anymore, in version 1.0 I will included all buddypress files inside the theme.</li>
			</ul>
			<p style='color: #f00;'>If you see the update notification, please backup your site before updating!</p>
			",
		"type" => "info"
	);

	$options[] = array( 
		"name" => 'New Features',
		"desc" => "<p>What's new in version 1.0:</p>
			<ul class='ul-disc'>
				<li>FB Like box widget.</li>
				<li>Ads widget.</li>
				<li>Twitter manager widget.</li>
				<li>Landing page template.</li>
				<li>etc.</li>
			</ul>
			",
		"type" => "info"
	);

	$options[] = array( 
		"name" => 'Support',
		"desc" => "<p>Please check our new forum <a href='http://satrya.me/' target='_blank'>http://satrya.me/</a>, you can access the theme documentation and post your question/problem there.</p>
			",
		"type" => "info"
	);
		
	$options[] = array( 
		'name' => __('General', 'tiga'),
		'type' => 'heading'
	);
							
	$options[] = array( 
		'name' => __('Custom Logo', 'tiga'),
		'desc' => __('Upload a logo for your website, or specify the image address of your online logo. (http://example.com/logo.png)', 'tiga'),
		'id' => 'tiga_custom_logo',
		'type' => 'upload'
	);
								
	$options[] = array( 
		'name' => __('Custom Favicon', 'tiga'),
		'desc' => __('Upload a favicon for your website, or specify the image address of your online favicon. (http://example.com/favicon.png)', 'tiga'),
		'id' => 'tiga_custom_favicon',
		'type' => 'upload'
	);
							
	$options[] = array( 
		'name' => __('Custom CSS', 'tiga'),
		'desc' => __('Quickly add some CSS to your theme by adding it to this block.', 'tiga'),
		'id' => 'tiga_custom_css',
		'std' => '',
		'type' => 'textarea'
	); 
						
	$options[] = array( 
		'name' => __('Analytic Code', 'tiga'),
		'desc' => __('Paste your Google Analytics (or other) tracking code here. It will be inserted before the closing body tag of your theme.', 'tiga'),
		'id' => 'tiga_analytic_code',
		'type' => 'textarea'
	); 		 
						
	$options[] = array( 
		'name' => __('Iframe Blocker', 'tiga'),
		'desc' => __('Iframe blocker is for block iframe to your site such as google image.', 'tiga'),
		'id' => 'tiga_iframe_blocker',
		'std' => 'disable',
		'type' => 'select',
		'options' => $tiga_select
	);
						
	/* ============================== End General Settings ================================= */					
	
	$options[] = array( 
		'name' => __('Theme', 'tiga'),
		'type' => 'heading'
	);
						
	$options[] = array( 
		'name' => __('Global Layouts', 'tiga'),
		'desc' => __('Left content, right content or one column', 'tiga'),
		'id' => 'tiga_layouts',
		'std' => 'lcontent',
		'type' => 'images',
		'options' => array(
			'lcontent' => $imagepath . '2cr.png',
			'rcontent' => $imagepath . '2cl.png',
			'onecolumn' => $imagepath . '1col.png',
		)
	);

	$options[] = array( 
		'name' => __('Home Page Layouts', 'tiga'),
		'desc' => __('Two columns or one column', 'tiga'),
		'id' => 'tiga_home_layouts',
		'std' => 'two-cols',
		'type' => 'images',
		'options' => array(
			'two-cols' => $imagepath . 'l-mag.png',
			'one-col' => $imagepath . 'l-standard.png'
		)
	);
	
	$options[] = array( 
		'name' => __('Background Pattern', 'tiga'),
		'desc' => __('Available background pattern to customize your site', 'tiga'),
		'id' => 'tiga_pattern',
		'std' => 'pattern-0',
		'type' => 'images',
		'options' => array(
			'pattern-0' => $patternpath . '0.png',
			'pattern-1' => $patternpath . '1.png',
			'pattern-2' => $patternpath . '2.png',
			'pattern-3' => $patternpath . '3.png',
			'pattern-4' => $patternpath . '4.png',
			'pattern-5' => $patternpath . '5.jpg',
			'pattern-6' => $patternpath . '6.jpg',
			'pattern-7' => $patternpath . '7.png',
			'pattern-8' => $patternpath . '8.png',
			'pattern-9' => $patternpath . '9.png',
			'pattern-10' => $patternpath . '10.png'
		)
	);
	
	$options[] = array(
		'name' =>  __('Custom background', 'tiga'),
		'desc' => __('Customize your background', 'tiga'),
		'id' => 'tiga_custom_bg',
		'std' => $tiga_background,
		'type' => 'background' 
	);
	
	$options[] = array( 
		'name' => __('Show featured posts', 'tiga'),
		'desc' => __('Check this option to show featured posts on home page. Featured posts is based on sticky post. To show the featured posts, you only need create a post then check the sticky post option.', 'tiga'),
		'id' => 'tiga_show_featured',
		'type' => 'checkbox'
	);
						
	$options[] = array(
		'name' => __('Select a number of featured posts', 'tiga'),
		'desc' => __('How many featured posts you want to show ?', 'tiga'),
		'id' => 'tiga_featured',
		'class' => 'hidden',
		'type' => 'select',
		'std' => '2',
		'options' => $tiga_numbers
	);
						
	$options[] = array( 
		'name' => __('Use footer widgets', 'tiga'),
		'desc' => __('Check this option if you want to use the footer widgets', 'tiga'),
		'id' => 'tiga_footer_widgets',
		'type' => 'checkbox'
	);
						
	$options[] = array( 
		'name' => __('Display social share button', 'tiga'),
		'desc' => __('Display social share on single post and page', 'tiga'),
		'id' => "tiga_social_share",
		'std' => 'tiga_post', // These items get checked by default
		'type' => "radio",
		'options' => $tiga_social
	);
						
	$options[] = array( 
		'name' => __('Display author box', 'tiga'),
		'desc' => __('Check this option if you want display the author box on single posts', 'tiga'),
		'id' => 'tiga_author_box',
		'type' => 'checkbox'
	);
	
	/* ============================== End Theme Settings ================================= */	
	
	$options[] = array( 
		'name' => __('Typography', 'tiga'),
		'type' => 'heading'
	);

	$options[] = array( 
		'name' => __('Disable custom typography', 'tiga'),
		'desc' => __('Disable custom typography and use theme defaults.', 'tiga'),
		'id' => 'tiga_disable_typography',
		'std' => true,
		'type' => 'checkbox' 
	);

	$options[] = array( 
		'name' => __('Content typography', 'tiga'),
		'desc' => __('This font is used for content text.', 'tiga'),
		'id' => 'tiga_content_font',
		'std' => array('size' => '13px','face' => '"BitterRegular", Georgia, Palatino, "Palatino Linotype", Times, "Times New Roman", serif', 'color' => '#333333'),
		'type' => 'typography',
		'options' => array(
			'sizes' => array( '12','13','14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24' ),
			'faces' => array(
				'"BitterRegular", Georgia, Palatino, "Palatino Linotype", Times, "Times New Roman", serif' => 'Bitter Regular',
				'Arial, "Helvetica Neue", Helvetica, sans-serif' => 'Arial',
				'Georgia, Palatino, "Palatino Linotype", Times, "Times New Roman", serif' => 'Georgia',
				'Tahoma, Geneva, Verdana, sans-serif' => 'Tahoma',
				'"Helvetica Neue", Arial, Helvetica, sans-serif' => 'Helvetica',
				'Verdana, Geneva, sans-serif' => 'Verdana',
				'Times, "Times New Roman", Georgia, serif' => 'Times New Roman',
				'"Trebuchet MS", "Lucida Sans Unicode", "Lucida Grande", "Lucida Sans", Arial, sans-serif' => 'Trebuchet MS',
				'Cambria, Georgia, serif' => 'Cambria',
				'Calibri, Candara, Segoe, "Segoe UI", Optima, Arial, sans-serif' => 'Calibri'
			),
			'styles' => array( 'normal' => 'Normal', 'bold' => 'Bold' )
		)
	);

	$options[] = array( 
		'name' => __('Content heading typography', 'tiga'),
		'desc' => __('Select the headline font (h1,h2,h3 etc)', 'tiga'),
		'id' => 'tiga_heading_font',
		'std' => array('size' => '13px','face' => '"FrancoisOneRegular", Arial, "Helvetica Neue", Helvetica, sans-serif', 'color' => '#333333'),
		'type' => 'typography',
		'options' => array(
			'sizes' => false,
			'faces' => array(
				'"FrancoisOneRegular", Arial, "Helvetica Neue", Helvetica, sans-serif' => 'Francois Regular',
				'Arial, "Helvetica Neue", Helvetica, sans-serif' => 'Arial',
				'Georgia, Palatino, "Palatino Linotype", Times, "Times New Roman", serif' => 'Georgia',
				'Tahoma, Geneva, Verdana, sans-serif' => 'Tahoma',
				'"Helvetica Neue", Arial, Helvetica, sans-serif' => 'Helvetica',
				'Verdana, Geneva, sans-serif' => 'Verdana',
				'Times, "Times New Roman", Georgia, serif' => 'Times New Roman',
				'"Trebuchet MS", "Lucida Sans Unicode", "Lucida Grande", "Lucida Sans", Arial, sans-serif' => 'Trebuchet MS',
				'Cambria, Georgia, serif' => 'Cambria',
				'Calibri, Candara, Segoe, "Segoe UI", Optima, Arial, sans-serif' => 'Calibri'
			),
			'styles' => array( 'normal' => 'Normal',  'bold' => 'Bold' )
		)
	);

	/* ============================== End Typography Settings ================================= */

	$options[] = array( 
		'name' => __('Social', 'tiga'),
		'type' => 'heading'
	);
	
	$options[] = array( 
		'name' => __('Social settings', 'tiga'),
		'desc' => __('If you want to display the social button, first you should fill the form below, put only the <strong>username</strong>. After that, go to Appearance > Widgets then drag the widget &raquo; tiga social buttons.', 'tiga'),
		'type' => 'info'
	);
		
	$options[] = array( 
		'name' => __('Email', 'tiga'),
		'desc' => __('Your email', 'tiga'),
		'id' => 'tiga_email',
		'type' => 'text'
	);
		
	$options[] = array( 
		'name' => __('Twitter Username', 'tiga'),
		'desc' => __('Your twitter username', 'tiga'),
		'id' => 'tiga_twitter_username',
		'type' => 'text'
	);
						
	$options[] = array( 
		'name' => __('Facebook Username', 'tiga'),
		'desc' => __('Your facebook username', 'tiga'),
		'id' => 'tiga_fb_username',
		'type' => 'text'
	);
						
	$options[] = array( 
		'name' => __('Google Plus Username', 'tiga'),
		'desc' => __('https://plus.google.com/u/<strong>109253446701726260861</strong>', 'tiga'),
		'id' => 'tiga_gplus_username',
		'type' => 'text'
	);
						
	$options[] = array( 
		'name' => __('Youtube Username', 'tiga'),
		'desc' => __('Your youtube username', 'tiga'),
		'id' => 'tiga_ytube_username',
		'type' => 'text'
	);
						
	$options[] = array( 
		'name' => __('Flickr Username', 'tiga'),
		'desc' => __('Your flickr username', 'tiga'),
		'id' => 'tiga_flickr_username',
		'type' => 'text'
	);
						
	$options[] = array( 
		'name' => __('Linkedin Username', 'tiga'),
		'desc' => __('http://linkedin.com/in/<strong>username</strong>', 'tiga'),
		'id' => 'tiga_linkedin_username',
		'type' => 'text'
	);
						
	$options[] = array( 
		'name' => __('Pinterest Username', 'tiga'),
		'desc' => __('Your pinterest username', 'tiga'),
		'id' => 'tiga_pinterest_username',
		'type' => 'text'
	);
						
	$options[] = array( 
		'name' => __('Dribbble Username', 'tiga'),
		'desc' => __('Your dribbble username', 'tiga'),
		'id' => 'tiga_dribbble_username',
		'type' => 'text'
	);
						
	$options[] = array( 
		'name' => __('Github Username', 'tiga'),
		'desc' => __('Your github username', 'tiga'),
		'id' => 'tiga_github_username',
		'type' => 'text'
	);
						
	$options[] = array( 
		'name' => __('LastFM Username', 'tiga'),
		'desc' => __('Your lastfm username', 'tiga'),
		'id' => 'tiga_lastfm_username',
		'type' => 'text'
	);

	$options[] = array( 
		'name' => __('Vimeo Username', 'tiga'),
		'desc' => __('Your vimeo username', 'tiga'),
		'id' => 'tiga_vimeo_username',
		'type' => 'text'
	);
	
	$options[] = array( 
		'name' => __('Tumblr Username', 'tiga'),
		'desc' => __('Your tumblr username', 'tiga'),
		'id' => 'tiga_tumblr_username',
		'type' => 'text'
	);
	
	$options[] = array( 
		'name' => __('Instagram Username', 'tiga'),
		'desc' => __('Your instagram username', 'tiga'),
		'id' => 'tiga_instagram_username',
		'type' => 'text'
	);
	
	/* ============================== End Social Settings ================================= */
	
	$options[] = array( 
		'name' => __('Meta Verification', 'tiga'),
		'type' => 'heading'
	);
						
	$options[] = array( 
		'name' => __('Webmaster Tools Setting', 'tiga'),
		'desc' => __('You can use the boxes below to verify with the different Webmaster Tools. Only enter the meta values/content. <br />ex: <i><meta name="google-site-verification" content="<b>2141241512</b>" /></i>', 'tiga'),
		'type' => 'info'
	);
						
	$options[] = array( 
		'name' => __('Google Webmaster Tools', 'tiga'),
		'desc' => __('<a href="http://www.google.com/webmasters/">Google webmaster tools &raquo;</a>', 'tiga'),
		'id' => 'tiga_meta_google',
		'std' => '',
		'type' => 'text'
	);
						
	$options[] = array( 
		'name' => __('Bing Webmaster', 'tiga'),
		'desc' => __('<a href="http://www.bing.com/webmaster/">Bing webmaster &raquo;</a>', 'tiga'),
		'id' => 'tiga_meta_bing',
		'std' => '',
		'type' => 'text'
	);
						
	$options[] = array( 
		'name' => __('Alexa', 'tiga'),
		'desc' => __('<a href="http://www.alexa.com/">Alexa &raquo;</a>', 'tiga'),
		'id' => 'tiga_meta_alexa',
		'std' => '',
		'type' => 'text'
	);
						
	/* ============================== End Meta Verivication Settings ================================= */	
	
	$options[] = array( 
		'name' => __('Ads Setting', 'tiga'),
		'type' => 'heading'
	);
						
	$options[] = array( 
		'name' => __('Ads 1', 'tiga'),
		'desc' => __('Ads after post title on single post', 'tiga'),
		'id' => 'tiga_ads_after_title',
		'std' => '',
		'type' => 'textarea'
	);
						
	$options[] = array( 
		'name' => __('Ads 2', 'tiga'),
		'desc' => __('Ads after post content on single post', 'tiga'),
		'id' => 'tiga_ads_after_content',
		'std' => '',
		'type' => 'textarea'
	); 
	
	/* ============================== End Ads Settings ================================= */	

	return $options;
}

/* 
 * Custom script for theme options
 *
 * @since Tiga 0.0.1
 */

add_action('optionsframework_custom_scripts', 'tiga_custom_scripts');
function tiga_custom_scripts() { ?>
	<script type='text/javascript'>
	jQuery(document).ready(function($) {

		$('#tiga_show_featured').click(function() {
			$('#section-tiga_featured').fadeToggle(400);
		});
		
		if ($('#tiga_show_featured:checked').val() !== undefined) {
			$('#section-tiga_featured').show();
		}
		
	});
	</script>
<?php
}