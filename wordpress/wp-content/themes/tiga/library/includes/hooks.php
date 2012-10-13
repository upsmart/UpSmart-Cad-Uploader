<?php
/**
 * Tiga Theme Custom Hooks
 * 
 * This file defines custom action hooks for the Tiga Theme.
 *
 * @package 	Tiga
 * @author		Satrya
 * @license		license.txt
 * @since 		Tiga 0.2
 *
 */

/**
 * Action hook after #page
 *
 * @file 	header.php
 * @since 	Tiga 0.2
 */
function tiga_before() {
    do_action('tiga_before');
}

/**
 * Action hook inside #masthead
 *
 * @file 	header.php
 * @since 	Tiga 0.2
 */
function tiga_header() {
    do_action('tiga_header');
}

/**
 * Action hook before #main
 *
 * @file 	header.php
 * @since 	Tiga 0.2
 */
function tiga_main_before() {
    do_action('tiga_main_before');
}

/**
 * Action hook after #main
 *
 * @file 	header.php
 * @since 	Tiga 0.2
 */
function tiga_main() {
    do_action('tiga_main');
}

/**
 * Action hook before #content
 *
 * @file 	index.php, single.php, page.php, 
 *			404.php, archive.php, author.php, image.php, search.php,
 *			template-archives.php, template-fullwidth.php
 * @since 	Tiga 0.2
 */
function tiga_content_before() {
    do_action('tiga_content_before');
}

/**
 * Action hook after #content
 *
 * @file 	index.php, single.php, page.php, 
 *			404.php, archive.php, author.php, image.php, search.php,
 *			template-archives.php, template-fullwidth.php
 * @since 	Tiga 0.2
 */
function tiga_content() {
    do_action('tiga_content');
}

/**
 * Action hook after end #content
 *
 * @file 	index.php, single.php, page.php, 
 *			404.php, archive.php, author.php, image.php, search.php,
 *			template-archives.php, template-fullwidth.php
 * @since 	Tiga 0.2
 */
function tiga_content_after() {
    do_action('tiga_content_after');
}

/**
 * Action hook after #secondary
 *
 * @file 	sidebar.php
 * @since 	Tiga 0.2
 */
function tiga_sidebar_before() {
    do_action('tiga_sidebar_before');
}

/**
 * Action hook after #footer-sidebar
 *
 * @file 	sidebar-footer.php
 * @since 	Tiga 0.2
 */
function tiga_sidebar_footer_before() {
    do_action('tiga_sidebar_footer_before');
}

/**
 * Action hook after .site-info
 *
 * @file 	footer.php
 * @since 	Tiga 0.2
 */
function tiga_credits() {
    do_action('tiga_credits');
}
?>