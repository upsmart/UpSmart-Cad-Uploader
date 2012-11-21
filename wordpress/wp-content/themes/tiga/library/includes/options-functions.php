<?php
/**
 * Return theme options functions
 *
 * @package 	Tiga
 * @author		Satrya
 * @license		license.txt
 * @since 		Tiga 0.0.1
 *
 */

/**
 * Custom CSS
 *
 * @since Tiga 0.0.1
 */
add_action( 'wp_head', 'tiga_custom_css', 10 );
function tiga_custom_css() {

	$custom_css = of_get_option('tiga_custom_css');
	if ($custom_css != '') {
		echo "<!-- Custom style -->\n<style type=\"text/css\">\n" . wp_filter_nohtml_kses( $custom_css ) . "\n</style>\n";
	}

}

/**
 * Custom Typography
 *
 * @since Tiga 0.2
 */
add_action('wp_head', 'tiga_custom_typography');
function tiga_custom_typography() {

	if ( !of_get_option( 'tiga_disable_typography' ) ) {
		$output = '';
		
		if ( of_get_option( 'tiga_content_font' ) ) {
			$output .= tiga_content_font_styles( of_get_option( 'tiga_content_font' ) , '.entry-content, .entry-summary');
		}

		if ( of_get_option( 'tiga_heading_font' ) ) {
			$output .= tiga_heading_font_styles( of_get_option( 'tiga_heading_font' ) , 'h1,h2,h3,h4,h5,h6');
		}

		if ( $output != '' ) {
			$output = "\n<style>\n" . $output . "</style>\n";
			echo $output;
		}

	}

}

/** 
 * Returns a typography option for content in a format that can be outputted as inline CSS
 *
 * @since Tiga 0.2
 */
 
function tiga_content_font_styles($option, $selectors) {
	$output = $selectors . ' {';
	$output .= ' color:' . $option['color'] .'; ';
	$output .= 'font-family:' . $option['face'] . '; ';
	$output .= 'font-weight:' . $option['style'] . '; ';
	$output .= 'font-size:' . $option['size'] . ' !important; ';
	$output .= '}';
	$output .= "\n";

	return $output;
}

/** 
 * Returns a typography option for heading in a format that can be outputted as inline CSS
 *
 * @since Tiga 0.2
 */
function tiga_heading_font_styles($option, $selectors) {
	$output = $selectors . ' {';
	$output .= ' color:' . $option['color'] .'; ';
	$output .= 'font-family:' . $option['face'] . '; ';
	$output .= 'font-weight:' . $option['style'] . '; ';
	$output .= '}';
	$output .= "\n";

	return $output;
}

/**
 * Background pattern
 *
 * @since Tiga 0.0.7
 */
add_filter( 'body_class', 'tiga_bg_pattern' );
function tiga_bg_pattern($classes) {
	$pattern = of_get_option('tiga_pattern');
	
	if ( 'pattern-1' == $pattern )
		$classes[] = 'pattern-1';
	elseif ( 'pattern-2' == $pattern )
		$classes[] = 'pattern-2';
	elseif ( 'pattern-3' == $pattern )
		$classes[] = 'pattern-3';
	elseif ( 'pattern-4' == $pattern )
		$classes[] = 'pattern-4';
	elseif ( 'pattern-5' == $pattern )
		$classes[] = 'pattern-5';
	elseif ( 'pattern-6' == $pattern )
		$classes[] = 'pattern-6';
	elseif ( 'pattern-7' == $pattern )
		$classes[] = 'pattern-7';
	elseif ( 'pattern-8' == $pattern )
		$classes[] = 'pattern-8';
	elseif ( 'pattern-9' == $pattern )
		$classes[] = 'pattern-9';
	elseif ( 'pattern-10' == $pattern )
		$classes[] = 'pattern-10';
	else
		$classes[] = 'no-pattern';
		
	return $classes;
}

/**
 * Custom background
 *
 * @since Tiga 0.0.3
 */
add_action( 'wp_head', 'tiga_custom_background', 10 );
function tiga_custom_background() {

	$bg = of_get_option('tiga_custom_bg');
	
	if($bg['image'] OR $bg['color']) { ?>
		<style type="text/css">
			<?php if ($bg['image']) {
				echo 'body { background: '.$bg['color'].' url('. esc_url( $bg['image'] ). ') '.$bg['repeat'].' '.$bg['position'].' '.$bg['attachment'].'; }'. "\n";
			} else {
				echo 'body { background: '.$bg['color']. ' }'. "\n";
			} ?>
		</style>
	<?php }
	
}

/**
 * Favicon
 *
 * @since Tiga 0.0.1
 */
add_action( 'wp_head', 'tiga_custom_favicon', 5 );
function tiga_custom_favicon() {

	if (of_get_option('tiga_custom_favicon'))
		echo '<link rel="shortcut icon" href="'. esc_url( of_get_option('tiga_custom_favicon') ) .'">'."\n";

}

/**
 * Iframe blocker
 *
 * @since Tiga 0.0.1
 */
add_action( 'wp_head', 'tiga_iframe_blocker', 11 );
function tiga_iframe_blocker() {
		
	if(of_get_option('tiga_iframe_blocker') == 'enable'):?>
		<script language="javascript" type="text/javascript"> 
			if (top.location != self.location) top.location.replace(self.location); 
		</script>
	<?php endif;

}

/**
 * Custom layout classes
 *
 * @since Tiga 0.0.1
 */
add_filter( 'body_class', 'tiga_custom_layouts' );
function tiga_custom_layouts($classes) {
	$layouts = of_get_option('tiga_layouts');
	
	if ( 'rcontent' == $layouts )
		$classes[] = 'two-columns right-primary left-secondary';
	elseif ( 'lcontent' == $layouts )
		$classes[] = 'two-columns left-primary right-secondary';
	else
		$classes[] = 'one-column only-primary';
	
	return $classes;
}

/**
 * One-column css
 *
 * @since Tiga 0.1
 */
add_action( 'wp_enqueue_scripts', 'tiga_onecol_style', 10 );
function tiga_onecol_style() {

	$layouts = of_get_option('tiga_layouts');
	if ( 'onecolumn' == $layouts ) :
		wp_enqueue_style('tiga-onecolumn', get_template_directory_uri() . '/library/css/one-column.css', '', '0.1', 'all');
	endif;

}

/**
 * Sets the post excerpt length
 *
 * @since Tiga 0.1
 */
add_filter( 'excerpt_length', 'tiga_excerpt' );
function tiga_excerpt( $length ) {
	$home_layout = of_get_option('tiga_home_layouts');

	if( 'one-col' == $home_layout )
		return 50;
	else
		return 35;
}

/**
 * Google meta verification
 *
 * @since Tiga 0.0.1
 */
add_action( 'wp_head', 'tiga_meta_google', 2 );
function tiga_meta_google() {

	$output = of_get_option('tiga_meta_google');
	if ( $output ) 
		echo '<meta name="google-site-verification" content="' . wp_filter_nohtml_kses( $output ) . '"> ' . "\n";

}

/**
 * Bing meta verification
 *
 * @since Tiga 0.0.1
 */
add_action( 'wp_head', 'tiga_meta_bing', 2 );
function tiga_meta_bing() {

	$output = of_get_option('tiga_meta_bing');
	if ( $output ) 
		echo '<meta name="msvalidate.01" content="' . wp_filter_nohtml_kses( $output ) . '"> ' . "\n";

}

/**
 * Alexa meta verification
 *
 * @since Tiga 0.0.1
 */
add_action( 'wp_head', 'tiga_meta_alexa', 2 );
function tiga_meta_alexa() {

	$output = of_get_option('tiga_meta_alexa');
	if ( $output ) 
		echo '<meta name="alexaVerifyID" content="' . wp_filter_nohtml_kses( $output ) . '"> ' . "\n";

}

/**
 * Analytics code
 *
 * @since Tiga 0.0.1
 */
add_action( 'wp_footer','tiga_analytics' );
function tiga_analytics() {

	$output = of_get_option('tiga_analytic_code');
	if ( $output ) 
		echo "\n" . stripslashes($output) . "\n";

}

/**
 * for textarea sanitization and $allowedposttags + embed and script.
 *
 * @since Tiga 0.0.1
 * @update Tiga 0.2.1
 */
add_action('admin_init', 'tiga_change_santiziation', 100);
function tiga_change_santiziation() {
    remove_filter( 'of_sanitize_textarea', 'of_sanitize_textarea' );
    add_filter( 'of_sanitize_textarea', 'tiga_sanitize_textarea' );
}

function tiga_sanitize_textarea($input) {
    global $allowedposttags;
    $custom_allowedtags["embed"] = array(
		"src" => array(),
		"type" => array(),
		"allowfullscreen" => array(),
		"allowscriptaccess" => array(),
		"height" => array(),
			"width" => array()
		);
	$custom_allowedtags["script"] = array(
		"src" => array(), 
		"type" => array()
		);
	$custom_allowedtags = array_merge($custom_allowedtags, $allowedposttags);
	$output = wp_kses( $input, $custom_allowedtags);
    return $output;
}
?>