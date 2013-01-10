<?php
/**
 * SpringFestival Theme Options
 *
 * @package WordPress
 * @subpackage SpringFestival
 * @since SpringFestival 1.4
 */
 
 
 
function SpringFestival_theme_options_init() {

	register_setting(
		'SpringFestival_options', 
		'SpringFestival_theme_options', 
		'SpringFestival_theme_options_validate'
	);
	add_settings_section(
		'general', 
		'',
		'__return_false',
		'theme_options' 
	);
	add_settings_field( 'layout',     __( 'Default Layout', 'SpringFestival' ), 'SpringFestival_settings_field_layout',     'theme_options', 'general' );
}
add_action( 'admin_init', 'SpringFestival_theme_options_init' );
function SpringFestival_option_page_capability( $capability ) {
	return 'edit_theme_options';
}
add_filter( 'option_page_capability_SpringFestival_options', 'SpringFestival_option_page_capability' );
function SpringFestival_theme_options_add_page() {
	$theme_page = add_theme_page(
		__( 'Theme Options', 'SpringFestival' ),
		__( 'Theme Options', 'SpringFestival' ),
		'edit_theme_options', 
		'theme_options',
		'SpringFestival_theme_options_render_page' 
	);

	if ( ! $theme_page )
		return;

}
add_action( 'admin_menu', 'SpringFestival_theme_options_add_page' );
function SpringFestival_layouts() {
	$layout_options = array(
		'content-sidebar' => array(
			'value' => 'content-sidebar',
			'label' => __( 'Content on left', 'SpringFestival' ),
			'thumbnail' => get_template_directory_uri() . '/inc/images/content-sidebar.png',
		),
		'sidebar-content' => array(
			'value' => 'sidebar-content',
			'label' => __( 'Content on right', 'SpringFestival' ),
			'thumbnail' => get_template_directory_uri() . '/inc/images/sidebar-content.png',
		),
	);


	return apply_filters( 'SpringFestival_layouts', $layout_options );
}
function SpringFestival_get_default_theme_options() {
	$default_theme_options = array(
		'theme_layout' => 'content-sidebar',
	);

	if ( is_rtl() )
 		$default_theme_options['theme_layout'] = 'sidebar-content';

	return apply_filters( 'SpringFestival_default_theme_options', $default_theme_options );
}
function SpringFestival_get_theme_options() {
	return get_option( 'SpringFestival_theme_options', SpringFestival_get_default_theme_options() );
}
function SpringFestival_settings_field_layout() {
	$options = SpringFestival_get_theme_options();
	foreach ( SpringFestival_layouts() as $layout ) {
		?>

<div class="layout image-radio-option theme-layout">
  <label class="description">
    <input type="radio" name="SpringFestival_theme_options[theme_layout]" value="<?php echo esc_attr( $layout['value'] ); ?>" <?php checked( $options['theme_layout'], $layout['value'] ); ?> />
    <span> <img src="<?php echo esc_url( $layout['thumbnail'] ); ?>" width="136" height="122" alt="" /> <?php echo $layout['label']; ?> </span> </label>
</div>
<?php
	}
}
function SpringFestival_theme_options_render_page() {
	?>
<div class="wrap">
  <?php screen_icon(); ?>
  <?php $theme_name = function_exists( 'wp_get_theme' ) ? wp_get_theme() : get_current_theme(); ?>
  <h2><?php printf( __( '%s Theme Options', 'SpringFestival' ), $theme_name ); ?></h2>
  <?php settings_errors(); ?>
  <form method="post" action="options.php">
    <?php
				settings_fields( 'SpringFestival_options' );
				do_settings_sections( 'theme_options' );
				submit_button();
			?>
  </form>
</div>
<?php
}

function SpringFestival_theme_options_validate( $input ) {
	$output = $defaults = SpringFestival_get_default_theme_options();
	if ( isset( $input['theme_layout'] ) && array_key_exists( $input['theme_layout'], SpringFestival_layouts() ) )
		$output['theme_layout'] = $input['theme_layout'];

	return apply_filters( 'SpringFestival_theme_options_validate', $output, $input, $defaults );
}
function SpringFestival_layout_classes( $existing_classes ) {
	$options = SpringFestival_get_theme_options();
	$current_layout = $options['theme_layout'];

	if ( in_array( $current_layout, array( 'content-sidebar', 'sidebar-content' ) ) )
		$classes = array( 'two-column' );
	if ( 'content-sidebar' == $current_layout )
		$classes[] = 'right-sidebar';
	elseif ( 'sidebar-content' == $current_layout )
		$classes[] = 'left-sidebar';
	else
		$classes[] = $current_layout;

	$classes = apply_filters( 'SpringFestival_layout_classes', $classes, $current_layout );

	return array_merge( $existing_classes, $classes );
}
add_filter( 'body_class', 'SpringFestival_layout_classes' );
function SpringFestival_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

	$options  = SpringFestival_get_theme_options();
	$defaults = SpringFestival_get_default_theme_options();



	$wp_customize->add_section( 'SpringFestival_layout', array(
		'title'    => __( 'Layout', 'SpringFestival' ),
		'priority' => 50,
	) );

	$wp_customize->add_setting( 'SpringFestival_theme_options[theme_layout]', array(
		'type'              => 'option',
		'default'           => $defaults['theme_layout'],
		'sanitize_callback' => 'sanitize_key',
	) );

	$layouts = SpringFestival_layouts();
	$choices = array();
	foreach ( $layouts as $layout ) {
		$choices[$layout['value']] = $layout['label'];
	}

	$wp_customize->add_control( 'SpringFestival_theme_options[theme_layout]', array(
		'section'    => 'SpringFestival_layout',
		'type'       => 'radio',
		'choices'    => $choices,
	) );
}
add_action( 'customize_register', 'SpringFestival_customize_register' );
?>
