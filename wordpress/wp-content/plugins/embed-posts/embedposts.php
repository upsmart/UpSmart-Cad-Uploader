<?php
/*
Plugin Name: Embed Posts
Plugin URI: http://davidtyler.we.bs/page/web/wordpress-plugin-embedded-posts
Description: Embed a Post within another Post or Page. Use double brackets and the post's Slug (ex. [[about-us]]) to embed.
Version: 1.4
Author: David Tyler
Author URI: http://davidtyler.we.bs
*/

function includePosts_edit($post_id){
	if(current_user_can('edit_posts')){
		return ' <a href="'.get_edit_post_link($post_id).'">Edit this Section</a>';
		}
	return '';
	}
function includePosts_get_post_by_slug($post_name, $output = OBJECT){
	global $wpdb;
	$post = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_name = %s", $post_name ));
	if ( $post ) return get_post($post, $output);
	return null;
	}
function includePosts ($content = '') {
	preg_match_all('/(?<=\\[\\[).+?(?=\\]\\])/', $content, $matches, PREG_PATTERN_ORDER);
	$numMatches = count($matches[0]);
	for ($i = 0; $i < $numMatches; $i++){
		$postSlug = $matches[0][$i];
		$post = includePosts_get_post_by_slug($postSlug);
		if($post){
			$hideTitle = (get_post_meta($post->ID,'embed-hide-title',true) || get_post_meta(get_the_ID(),'embed-hide-title',true));
			$title = ($hideTitle)?'':'<div><h2 class="post_title">'.$post->post_title.'</h2> '.includePosts_edit($post->ID).'</div>';
			$text = '<div class="embedded_post">'.$title.'<p>'.format_to_post($post->post_content).'</p></div>';
			$content = preg_replace('/<!--.*?-->/', '', $content);
			$content = str_replace("\r\n[[", '[[', $content);
			$zcontent = str_replace("`[[$postSlug]]`", "<code>[[$postSlug]]</code>", $content);
			if($zcontent == $content) $content = str_replace("[[$postSlug]]", $text, $content);
			else $content = $zcontent;
			}
		}
	return $content;
	}

add_filter('the_content', 'includePosts', 1);

?>