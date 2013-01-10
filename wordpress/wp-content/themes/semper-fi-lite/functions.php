<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

?>
<?php

// Global Content Width, Kind of a Joke with this theme, lol
	if (!isset($content_width))
		$content_width = 1100;
			
// Ladies, Gentalmen, boys and girls let's start our engines
add_action('after_setup_theme', 'semperfi_setup');

if (!function_exists('semperfi_setup')):

    function semperfi_setup() {

        global $content_width; 
			
        // Add Callback for Custom TinyMCE editor stylesheets. (editor-style.css)
        add_editor_style();

        // This feature enables post and comment RSS feed links to head
        add_theme_support('automatic-feed-links');

        // This feature enables post-thumbnail support for a theme
		add_theme_support( 'post-thumbnails', array( 'post', 'page' ) );
			add_image_size( 'page-thumbnail', 600, 355, true );

        // This feature enables custom-menus support for a theme
        register_nav_menus(array(
			'bar' => __('The Menu Bar', 'semperfi' ) ) );

        // WordPress 3.4+
		if ( function_exists('get_custom_header')) {
        	add_theme_support('custom-background'); }
		
	}

endif;

// Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link
function semperfi_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args; }

add_filter( 'wp_page_menu_args', 'semperfi_page_menu_args' );

/**
 * Filter 'get_comments_number'
 * 
 * Filter 'get_comments_number' to display correct 
 * number of comments (count only comments, not 
 * trackbacks/pingbacks)
 *
 * Courtesy of Chip Bennett
 */
function semperfi_comment_count( $count ) {  
	if ( ! is_admin() ) {
		global $id;
		$comments_by_type = &separate_comments(get_comments('status=approve&post_id=' . $id));
		return count($comments_by_type['comment']); }
	else {
		return $count; } }

add_filter('get_comments_number', 'semperfi_comment_count', 0);

/**
 * wp_list_comments() Pings Callback
 * 
 * wp_list_comments() Callback function for 
 * Pings (Trackbacks/Pingbacks)
 */
function semperfi_comment_list_pings( $comment ) {
	$GLOBALS['comment'] = $comment; ?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>"><?php echo comment_author_link(); ?></li>
<?php }

// Sets the post excerpt length to 250 characters
function semperfi_excerpt_length($length) {
    return 250; }

add_filter('excerpt_length', 'semperfi_excerpt_length');

// This function adds in code specifically for IE6 to IE9
function semperfi_ie_css() {
	echo "\n" . '<!-- IE 6 to 9 CSS Hacking -->' . "\n";
	echo '<!--[if lte IE 9]><style type="text/css">#header h1 i{bottom:.6em;}</style><![endif]-->' . "\n";
	echo '<!--[if lte IE 8]><style type="text/css">#center{width:1000px;}</style><![endif]-->' . "\n";
	echo '<!--[if lte IE 7]><style type="text/css">#header{padding:0 auto;}#header ul {padding-left:1.5em;}#header ul li{float:left;}</style><![endif]-->' . "\n";
	echo '<!--[if lte IE 6]><style type="text/css">#header.small h1{display: none;}#center{width:800px;}#banner{background:none;}.overlay{display:none;}.browsing li {width:47%;margin:1%;}#footer{background-color:#111);}</style><![endif]-->' . "\n";
	echo "\n";
}

add_action('wp_head', 'semperfi_ie_css');

// This function removes inline styles set by WordPress gallery
function semperfi_remove_gallery_css($css) {
    return preg_replace("#<style type='text/css'>(.*?)</style>#s", '', $css); }

add_filter('gallery_style', 'semperfi_remove_gallery_css');

// This function removes default styles set by WordPress recent comments widget
function semperfi_remove_recent_comments_style() {
	global $wp_widget_factory;
	remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) ); }

add_action( 'widgets_init', 'semperfi_remove_recent_comments_style' );

// A comment reply
function semperfi_enqueue_comment_reply() {
    if ( is_singular() && comments_open() && get_option('thread_comments')) 
            wp_enqueue_script('comment-reply'); }

add_action( 'wp_enqueue_scripts', 'semperfi_enqueue_comment_reply' );

//	A safe way of adding javascripts to a WordPress generated page
if (!is_admin())
	add_action('wp_enqueue_scripts', 'semperfi_js');

if (!function_exists('semperfi_js')) {
	function semperfi_js() {
			// JS at the bottom for fast page loading
			wp_enqueue_script('semperfi-jquery-easing', get_template_directory_uri() . '/js/jquery.easing.js', array('jquery'), '1.3', true);
            wp_enqueue_script('semperfi-menu-scrolling', get_template_directory_uri() . '/js/jquery.menu.scrolling.js', array('jquery'), '1', true);
			wp_enqueue_script('semperfi-scripts', get_template_directory_uri() . '/js/jquery.fittext.js', array('jquery'), '1.0', true);
			wp_enqueue_script('semperfi-fittext', get_template_directory_uri() . '/js/jquery.fittext.sizing.js', array('jquery'), '1', true);  } }

// Redirect to the theme options Page after theme is activated
if ( is_admin() && isset($_GET['activated'] ) && $pagenow == "themes.php" )
	wp_redirect( 'themes.php?page=theme_options' ); 

// WordPress Widgets start right here.
function semperfi_widgets_init() {

	register_sidebars(3, array(
		'name'=>'footer widget%d',
		'id' => 'widget',
		'description' => 'Widgets in this area will be shown below the the content of every page.',
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '<h2>',
		'after_title' => '</h2>', )); }
	
add_action('widgets_init', 'semperfi_widgets_init');

// Checks if the Widgets are active
function semperfi_is_sidebar_active($index) {
	global $wp_registered_sidebars;
	$widgetcolums = wp_get_sidebars_widgets();
	if ($widgetcolums[$index]) {
		return true; }
		return false; }
		
// Load up links in admin bar so theme is edit
function semperfi_theme_options_add_page() {
	add_theme_page('Theme Customizer', 'Theme Customizer', 'edit_theme_options', 'customize.php' );
    add_theme_page('Theme Info', 'Theme Info', 'edit_theme_options', 'theme_options', 'semperfi_theme_options_do_page');}
	
// Add link to theme options in Admin bar
function semperfi_admin_link() {
	global $wp_admin_bar;
	$wp_admin_bar->add_menu( array( 'id' => 'Semper_Fi_Customizer', 'title' => 'Theme Customizer', 'href' => admin_url( 'customize.php' ) ));
	$wp_admin_bar->add_menu( array( 'id' => 'Semper_Fi_Information', 'title' => 'Theme Information', 'href' => admin_url( 'themes.php?page=theme_options' ) )); }

add_action( 'admin_bar_menu', 'SemperFi_admin_link', 113 );

// Sets up the Customize.php for Semper Fi (More to come)
function semperfi_customize($wp_customize) {

	// Before we begin let's create a textarea input
	class Semperfi_Customize_Textarea_Control extends WP_Customize_Control {
    
		public $type = 'textarea';
	 
		public function render_content() { ?>
			<label>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<textarea rows="1" style="width:100%;" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
			</label> <?php } }

	// Add the option to use the CSS3 property Background-size
	$wp_customize->add_setting( 'backgroundsize_setting', array(
		'default'           => 'auto',
		'control'           => 'select',));

	$wp_customize->add_control( 'backgroundsize_control', array(
		'label'				=> 'Background Size',
		'section'			=> 'background_image',
		'settings'			=> 'backgroundsize_setting',
		'type'				=> 'radio',
		'choices'			=> array(
			'auto'			=> 'Auto (Default)',
			'contain'		=> 'Contain',
			'cover'			=> 'Cover',), ));
			
	// Change up the type of paper in the background
	$wp_customize->add_setting( 'backgroundpaper_setting', array(
		'default'           => 'clean',
		'control'           => 'select',));

	$wp_customize->add_control( 'backgroundpaper_control', array(
		'label'				=> 'Background Paper Image',
		'section'			=> 'background_image',
		'settings'			=> 'backgroundpaper_setting',
		'type'				=> 'radio',
		'choices'			=> array(
			'clean'			=> 'Clean but Used (Default)',
			'peppered'		=> 'Peppered',
			'vintage'		=> 'Vintage',
			'canvas'		=> 'Heavy Canvas',), ));
	
	// Choose the Different Images for the Banner
	$wp_customize->add_section( 'bannerimage_section', array(
        'title'			=> 'Banner Image',
		'priority'		=> 2, ));

	$wp_customize->add_setting('bannerimage_setting', array(
		'default'		=> 'blue',
		'capability'	=> 'edit_theme_options',
		'type'			=> 'option', ));

	$wp_customize->add_control('themename_color_scheme', array(
		'label'			=> 'Choose one of the choices below...',
		'section'		=> 'bannerimage_section',
		'settings'		=> 'bannerimage_setting',
		'type'			=> 'radio',
		'choices'		=> array(
			'blue.png'	=> 'Blue',
			'purple.png'=> 'Purple',
			'marble.png'=> 'Marble',
			'green.png'	=> 'Green', ), ));
		
	// Change Site Title Color
	$wp_customize->add_setting( 'titlecolor_setting', array(
		'default'		=> '#e0dbce', ));

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'titlecolor_control', array(
		'label'			=> 'Site Title Color - #e0dbce',
		'section'		=> 'title_tagline',
		'settings'		=> 'titlecolor_setting', )));

	// Change Tagline Color
	$wp_customize->add_setting( 'taglinecolor_setting', array(
		'default'		=> '#3e5a21', ));

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'taglinecolor_control', array(
		'label'			=> 'Site Title Color - #3e5a21',
		'section'		=> 'title_tagline',
		'settings'		=> 'taglinecolor_setting', ))); }		
	
add_action('customize_register', 'semperfi_customize');

// Preview CSS3 Property Background-size in Customizer
function semperfi_customizer_preview() {
	wp_enqueue_script('semperfi-customizer', get_template_directory_uri() . '/js/customizer.js', array('jquery'), '1.3', true);}

add_action( 'customize_controls_print_footer_scripts', 'semperfi_customizer_preview', 10 );	
		
// Inject the Customizer Choices into the Theme
function semperfi_inline_css() {

		echo '<!-- Custom CSS Styles -->' . "\n";
        echo '<style type="text/css" media="screen">' . "\n";
		if ( get_theme_mod('backgroundsize_setting') != 'auto' ) echo '	body.custom-background {background-size:' . get_theme_mod('backgroundsize_setting') . ';}' . "\n";
		echo '	#margin {background-image:url(' . get_template_directory_uri() . '/images/' . get_theme_mod('backgroundpaper_setting') . '.png);}' . "\n";
		echo '	#header h1 a {color:' . get_theme_mod('titlecolor_setting') . ';}' . "\n";
		echo '	#header h1 i {color:' . get_theme_mod('taglinecolor_setting') . ';}' . "\n";
		echo '	#header {background: bottom url(' . get_template_directory_uri() . '/images/' . get_option('bannerimage_setting') .  ');' . "\n";
		echo '</style>' . "\n";
		echo '<!-- End Custom CSS -->' . "\n";
		echo "\n";}

add_action('wp_head', 'semperfi_inline_css'); 

// Add some CSS so I can Style the Theme Options Page
function semperfi_admin_enqueue_scripts( $hook_suffix ) {
	wp_enqueue_style('semperfi-theme-options', get_template_directory_uri() . '/theme-options.css', false, '1.0');}

add_action('admin_print_styles-appearance_page_theme_options', 'semperfi_admin_enqueue_scripts');
	
// Create the Theme Information page (Theme Options)
function semperfi_theme_options_do_page() { ?>
    
    <div class="cover">
    
    <div id="header">
    <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/customize.png">
    </div>
    
    <div id="banner"></div>
    
    <div id="center">
	<div id="floatfix">

	<div class="reading">
    <div class="spacing"></div>
    <h3 class="title"><span>Please Read</br>This Page!</span>Thanks for using Semper Fi Lite!</h3>
	<span class="content"><p>Thank you for downloading and installing the WordPress Theme "Semper Fi Lite." I hope that you enjoy it and that I can continue to create these beautiful themes for years to come. But, if you could take a moment and become acutely aware that I have created this Theme and other themes free of charge, and while I'm not looking to get Rich, I really like creating these themes for you guys. Which is why I offer additional customization of "Semper Fi Lite" when you support me and install the standard version, "Semper Fi." If you're interested in supporting my work, or need some of the addition features in "Semper Fi" head on over to <a href="http://schwarttzy.com/shop/semper-fi/">this page</a>.</p>
    <p>Incase you happen to have any issues, questions, comments, or a requests for features with "Semper Fi Lite," you can contact me through E-Mail with the form on <a href="http://schwarttzy.com/contact-me/">this page</a>.</p>
    <p>Thank you again,</br><a href="http://schwarttzy.com/about-2/">Eric J. Schwarz</a></p>
	</span>
    <h3 class="title">Customizing Semper Fi</h3>
    <span class="content">
    <p><span class='embed-youtube' style='text-align:center; display: block;'><iframe class='youtube-player' type='text/html' width='1100' height='649' src='http://www.youtube.com/embed/IU__-ipUxxc?version=3&#038;rel=1&#038;fs=1&#038;showsearch=0&#038;showinfo=1&#038;iv_load_policy=1&#038;wmode=transparent' frameborder='0'></iframe></span></p>
	</span>
    <h3 class="title">Features</h3>
    <span class="content">
    <table>
        <tbody>
        <tr>
        <th class="justify">Semper Fi Theme Features</th>
        <th>Lite</th>
        <th>Standard</th>
        </tr>
        <tr>
        <td class="justify">100% Responsive WordPress Theme</td>
        <td>&#9733;</td>
        <td>&#9733;</td>
        </tr>
        <tr>
        <td class="justify">Clean and Beautiful Stylized HTML, CSS, JavaScript</td>
        <td>&#9733;</td>
        <td>&#9733;</td>
        </tr>
        <tr>
        <td class="justify">Featured Images for Posts &amp; Pages</td>
        <td>&#9733;</td>
        <td>&#9733;</td>
        </tr>
        <tr>
        <td class="justify">Change the site Title and Slogan Colors</td>
        <td>&#9733;</td>
        <td>&#9733;</td>
        </tr>
        <tr>
        <td class="justify">Upload Your Own Background Image</td>
        <td>&#9733;</td>
        <td>&#9733;</td>
        </tr>
        <tr>
        <td class="justify">Choose from 4 different Background Paper Images</td>
        <td>&#9733;</td>
        <td>&#9733;</td>
        </tr>
        <tr>
        <td class="justify">Upload your own Background for the Paper Image</td>
        <td></td>
        <td>&#9733;</td>
        </tr>
        <tr>
        <td class="justify">Multiple Menu Banner Images to Choose</td>
        <td>&#9733;</td>
        <td>&#9733;</td>
        </tr>
        <tr>
        <td class="justify">Upload Your Own Custom Banner Image</td>
        <td></td>
        <td>&#9733;</td>
        </tr>
        <tr>
        <td class="justify">Upload Your Own Logo (To be Implemented in Future Update)</td>
        <td></td>
        <td>&#9733;</td>
        </tr>
		<tr>
        <td class="justify">Change the Link Colors in the Menu</td>
        <td></td>
        <td>&#9733;</td>
        </tr>
        <tr>
        <td class="justify">Choose you own Hyper Link Color</td>
        <td></td>
        <td>&#9733;</td>
        </tr>
        <tr>
        <td class="justify">Favicon to be Easily Implemented on Your Website</td>
        <td></td>
        <td>&#9733;</td>
        </tr>
        <tr>
        <td class="justify">The Ability to use Custom Fonts from Typekit</td>
        <td></td>
        <td>&#9733;</td>
        </tr>
        <tr>
        <td class="justify">Remove my Mark from the Footer</td>
        <td></td>
        <td>&#9733;</td>
        </tr>
		<tr>
        <td class="justify">Personal Support on Technical Issues You May Run Into</td>
        <td></td>
        <td>&#9733;</td>
        </tr>
        </tbody>
	</table>
    <p>Don't see a feature that you want, maybe theres plugin that doesn't work right, <a href="http://schwarttzy.com/contact-me/">send me an Email about it</a>.</p>
	</span>
    <h3 class="title">Semper Fi Lite Version Information</h3>
    <span class="content">
    <table>
        <tbody>
        <tr>
        <th>Version</th>
        <th class="justify"></th>
        </tr>
        <tr>
        <th>.9</th>
        <td class="justify">Just a mix up in the code that would cause some errors in version .8</td>
        </tr>
        <tr>
        <th>.8</th>
        <td class="justify">Decided to rewrite the theme over again, completely from scratch, after having an amazing thought. The responsive website movement is because the vast number of different size resolutions of screen that we view the content. This amazing thought came to me that I could base everything off of em, which is the average width of one letter on your screen. Unlike using pixels to decide how the website displays where you have no idea what the end users font size is, using em give you a relatively good idea. Doing it this way I have optimized the readability of website based on how the user want to view the website. I also cleaned some code up to lighten the load on browsers and bring a more unified experience whether you're using Chrome, Firefox, or Internet Explorer.</td>
        </tr>
        <tr>
        <th>.7</th>
        <td class="justify">Small update to add in a new feature and rewrite some of the code. The new feature allows for someone to choose from 4 different choices for the white background that looks like paper. This feature is located under the "Background" tab on the "<a href="<?php echo home_url(); ?>/wp-admin/customize.php">Theme Customizer</a> Page." As for the rewrite of the code, I decided to have the Style.CSS file leave partially unfinished and have the default choice, or the choices currently chosen, finish the file Style.CSS with the missing code in the Header of the page. By choosing to leave the code out, browsers such as in firefox, chrome, IE, etc. will only see the CSS code once and won't have to write over and duplicate entries. Basically it keeps things lighter and cleaner.</td>
        </tr>
        <th>.6</th>
        <td class="justify">Quick update to add some more features to the theme along with a better "Theme Information" page. It is now possible to choose one of three different banners on the <a href="<?php echo home_url(); ?>/wp-admin/customize.php">Theme Customizer</a> page. I plan to add even more choices in the future for the banner, and on a side note you can upload you own with Semper Fi. Added in the ablity to easily change the color on Site Title and also the Site Slogan on the <a href="<?php echo home_url(); ?>/wp-admin/customize.php">Theme Customizer</a> page too. I have removed the file "theme-options.php" from the theme and the that was in there has been move to the file "functions.php" which is in the same folder. Finally the javascript file "background-size-preview.js" which handled the background changing on the <a href="<?php echo home_url(); ?>/wp-admin/customize.php">Theme Customizer</a> has been rename to "customizer.js" becuase it makes more sense since adding a bunch of code.</td>
        </tr>
        <th>.5</th>
        <td class="justify">The initial release.</td>
        </tr>
        </tbody>
	</table>
	</span>
    <h3 class="title">Semper Fi Version Information</h3>
    <span class="content">
    <table>
        <tbody>
        <tr>
        <th>Version</th>
        <th class="justify"></th>
        </tr>
        <tr>
        <th>4</th>
        <td class="justify">Semper Fi, the standard version received a similar update to "Lite" in .7 including the same new features and small rewrite of some code. Theres no real difference between the two except for the additional features.</td>
        </tr>
        <tr>
        <th>3</th>
        <td class="justify">Semper Fi, the standard version received a similar update to "Lite" in .7 including the same new features and small rewrite of some code. It too now includes 4 different choices for the white background that looks like paper, but unlike "Lite" the standard version of Semper Fi allows you upload your own image also. Semper Fi also had the same rewrite of code so that the code basically leaves Style.CSS uncomplete and has the final bit of code added in the header based on either the default choices, or the ones currently chosen in <a href="<?php echo home_url(); ?>/wp-admin/customize.php">Theme Customizer</a>. All in all the point is to reduce the footprint of code, and the possibility of downloading unnecessary content.</td>
        </tr>
        <th>2</th>
        <td class="justify">This is the first update after the initial release of the “Lite” version of “Semper Fi” and includes a bunch of changes for the better. Both “Semper Fi” and “Semper Fi Lite” now use Theme Customizer instead of Theme Options to customize the page, allowing for the administrator to visually see the changes to the website before the changes take effect in real time. I have included all the promised functionality into the theme with this update, except for a custom logo which will be in future update. Which means you can change the color on the Links, Menu, Title, and Slogan for now, and more in the future. This update also includes the ablitiy to choose from the three standard options for the banner, but unquie to the Semper Fi you can upload your own image for the banner, where as you can't in Lite. Finally, with this update I have added in the ablity for you quickly remove the footer that says "Good Old Fasioned Hand Written Code by Eric J. Schwarz" from the theme.</td>
        </tr>
        <th>1.1</th>
        <td class="justify">The initial release.</td>
        </tr>
        </tbody>
	</table>
	</span>
    <h3 class="title">About the Theme Semper Fi</h3>
	<span class="content"><p>I dedicate this theme to my Grandfather, Eldon Schwarz, for his strength and courage in WWII. He is the sole survivor of the crew aboard the B17 Flying Fortress #44-6349, of the 483rd Bombardment Group, in the 840th Bomb Squadron and a prisoner of war from August 7, 1943 to November 5, 1945. I miss you Grandpa.</p>
    <p>This theme began with me reading a newspaper from May 8, 1945. Just holding it I could sense that a lot time and planning went into simple things like font, layout, and especially choosing the paper's material. I marveled at the quality that clearly went into this paper. Even with how old the newspaper was, it makes modern newspapers just seem like a mere shadow of themselves clinging to their former glory.</p>
    <p>Because of that I decided that I had to create a theme that feels like a newspaper, rich with details and fine quality. From hidden luxurious floral patterns, images that create the nostalgia of finely crafted paper, to incredibly detailed shadowing, but most importantly, the ability to respond to any width screen. "Semper Fi" is a completely flexible theme able to stretch from 300 pixels wide, all the way to 1920 and beyond. Images, galleries, quotes, text, titles, they all move fluidly to respond to any thing you through at it.</p>
    </span>
	</div>
    
    </div>
    </div>
    
    </div>
<?php }
add_action('admin_menu', 'semperfi_theme_options_add_page');

?>