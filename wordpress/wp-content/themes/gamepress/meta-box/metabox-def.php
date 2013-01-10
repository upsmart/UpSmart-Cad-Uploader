<?php
/**
 * Registering meta boxes
 *
 * All the definitions of meta boxes are listed below with comments.
 * Please read them CAREFULLY.
 *
 * You also should read the changelog to know what has been changed before updating.
 *
 * For more information, please visit:
 * @link http://www.deluxeblogtips.com/meta-box/docs/define-meta-boxes
 */

/********************* META BOX DEFINITIONS ***********************/

/**
 * Prefix of meta keys (optional)
 * Use underscore (_) at the beginning to make keys hidden
 * Alt.: You also can make prefix empty to disable it
 */
// Better has an underscore as last sign
$prefix = 'gamepress_';

global $meta_boxes;

$meta_boxes = array();

// 1st meta box
$meta_boxes[] = array(
	// Meta box id, UNIQUE per meta box
	'id' => 'GamePress',

	// Meta box title - Will appear at the drag and drop handle bar
	'title' => __('Game review', 'gamepress'),

	// Post types, accept custom post types as well - DEFAULT is array('post'); (optional)
	'pages' => array( 'gamepress_reviews'),

	// Where the meta box appear: normal (default), advanced, side; optional
	'context' => 'normal',

	// Order of meta box: high (default), low; optional
	'priority' => 'high',

	// List of meta fields
	'fields' => array(
		//INTRO - TEXT
		array(
			'name' => __('Intro text', 'gamepress'),
			'desc' => __('One sentence intro text, displayed below the post title', 'gamepress'),
			'id' => $prefix . 'intro',
			'type' => 'textarea',
			'cols' => '40',
			'rows' => '3'
		),
		//SCORE - TEXT
		array(
			'name' => __('Overall score', 'gamepress'),
			'desc' => __('Number between 1 - 10 (eg. 5.6)', 'gamepress'),
			'id' => $prefix . 'score',
			'type' => 'text',
			'std' => '',
		),
		//THE GOOD - TEXT
		array(
			'name' => __('The Good', 'gamepress'),
			'desc' => __('List the things that You liked about the game in short sentences. Separate each entry, for example with |.', 'gamepress'),
			'id' => $prefix . 'the_good',
			'type' => 'textarea',
			'cols' => '40',
			'rows' => '3'
		),
		//THE BAD - TEXT
		array(
			'name' => __('The Bad', 'gamepress'),
			'desc' => __('List the things that You didn\'t like about the game in short sentences. Separate each entry, for example with |.', 'gamepress'),
			'id' => $prefix . 'the_bad',
			'type' => 'textarea',
			'cols' => '40',
			'rows' => '3'
		),
		// COVER - IMAGE UPLOAD
		array(
			'name'	=> __('Game cover image', 'gamepress'),
			'desc'	=> __('Game cover displayed next to the title with Game Review posts', 'gamepress'),
			'id'	=> $prefix . 'cover',
			'type'	=> 'image'
		)
	)
);


/********************* META BOX REGISTERING ***********************/

/**
 * Register meta boxes
 *
 * @return void
 */
function GamePress_register_meta_boxes()
{
	global $meta_boxes;

	// Make sure there's no errors when the plugin is deactivated or during upgrade
	if ( class_exists( 'RW_Meta_Box' ) )
	{
		foreach ( $meta_boxes as $meta_box )
		{
			new RW_Meta_Box( $meta_box );
		}
	}
}
// Hook to 'admin_init' to make sure the meta box class is loaded before
// (in case using the meta box class in another plugin)
// This is also helpful for some conditionals like checking page template, categories, etc.
add_action( 'admin_init', 'GamePress_register_meta_boxes' );