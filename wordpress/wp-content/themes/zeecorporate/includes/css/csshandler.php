<?php

// Load additional Predefined Color Schemes if Custom Colors is deactivated
function themezee_load_custom_css() { 
	$options = get_option('themezee_options');
	
	// Load PredefinedColor CSS
	if ( !isset($options['themeZee_color_activate']) or $options['themeZee_color_activate'] != 'true' ) {
		$cssfile = $options['themeZee_stylesheet'] <> '' ? $options['themeZee_stylesheet'] : 'standard.css';
		$stylesheet = get_template_directory_uri() . '/includes/css/colorschemes/' . $cssfile;
		wp_register_style('zeeCorporate_colorscheme', $stylesheet, array('zeeCorporate_stylesheet'));
		wp_enqueue_style( 'zeeCorporate_colorscheme');
	}
}
add_action('wp_enqueue_scripts', 'themezee_load_custom_css');

// Include CSS Files
locate_template('/includes/css/colors.css.php', true);
locate_template('/includes/css/layout.css.php', true);

?>