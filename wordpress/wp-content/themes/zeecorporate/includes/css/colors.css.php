<?php 
add_action('wp_head', 'themezee_css_colors');
function themezee_css_colors() {
	
	$options = get_option('themezee_options');
	
	if ( isset($options['themeZee_color_activate']) and $options['themeZee_color_activate'] == 'true' ) {
		
		echo '<style type="text/css">';
		echo '
			a, a:link, a:visited, #sidebar a:link, #sidebar a:visited, .wp-pagenavi .current, #nav a,
			.post-title, .post-title a:link, .post-title a:visited, .page-title, #sidebar .widgettitle
			{
				color: #'.esc_attr($options['themeZee_colors_full']).';
			}
			.comment-author .fn, .comment-reply-link, #slide_keys a:link, #slide_keys a:visited {
				color: #'.esc_attr($options['themeZee_colors_full']).' !important;
			}
		';
		echo '</style>';
	}
}