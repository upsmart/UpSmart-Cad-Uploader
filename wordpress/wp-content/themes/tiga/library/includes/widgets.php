<?php
/**
 * Tiga Widgets
 * 
 * Register our sidebars and widgetized areas
 *
 * @package 	Tiga
 * @author		Satrya
 * @license		license.txt
 * @since 		Tiga 0.0.1
 *
 */
 
add_action( 'widgets_init', 'tiga_widgets_init' );
function tiga_widgets_init() {
	
	register_widget('tiga_twitter'); // register custom twitter widget
	register_widget('tiga_social'); // register custom social widget
	register_widget('tiga_subscribe'); // register custom subscribe widget
	
    register_sidebar(array(
		'name'          => __( 'General', 'tiga'),
		'description'   => __('This sidebar appears on the right side of your site', 'tiga'),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<span class="widget-title">',
		'after_title'   => '</span>',
	));
	
	register_sidebar(array(
		'name'          => __( 'Footer Sidebar 1', 'tiga'),
		'description'   => __('This sidebar appears on the footer side of your site', 'tiga'),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<span class="widget-title">',
		'after_title'   => '</span>',
	));
	
	register_sidebar(array(
		'name'          => __( 'Footer Sidebar 2', 'tiga'),
		'description'   => __('This sidebar appears on the footer side of your site', 'tiga'),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<span class="widget-title">',
		'after_title'   => '</span>',
	));
	
	register_sidebar(array(
		'name'          => __( 'Footer Sidebar 3', 'tiga'),
		'description'   => __('This sidebar appears on the footer side of your site', 'tiga'),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<span class="widget-title">',
		'after_title'   => '</span>',
	));
	
	$layout = of_get_option('tiga_layouts');
	if( 'onecolumn' != $layout ) :
	register_sidebar(array(
		'name'          => __( 'Footer Sidebar 4', 'tiga'),
		'description'   => __('This sidebar appears on the footer side of your site', 'tiga'),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<span class="widget-title">',
		'after_title'   => '</span>',
	));
	endif;
}

/**
 * This function removes default styles set by WordPress recent comments widget.
 *
 * @since Tiga 0.0.1
 */
add_action( 'widgets_init', 'tiga_remove_recent_comments_style' );
function tiga_remove_recent_comments_style() {
	global $wp_widget_factory;
	remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );
}
?>