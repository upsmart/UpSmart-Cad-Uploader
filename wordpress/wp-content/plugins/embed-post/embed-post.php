<?php
/**
Plugin Name: Embed Post
Description: Test plugin
Author: Ghanshyam Khanal
Version: 0.1
Author URI: http://gskhanal.com/
*/

function embed_post_shortcode( $atts ){
    extract( shortcode_atts( array(
        'post_id' => 0,
        'type' => 'excerpt',
    ), $atts ) );
    if( (int)$post_id < 1){
        return '';
    }else{
        global $post;
        $post = get_post($post_id);
        if($post->post_status == "publish"){
            setup_postdata( $post );
            switch( $type ){
                case 'content':
                    $content     = '<div class="embed_post">';
                    $content    .= get_the_content();
                    $content    .= '</div>';
                    wp_reset_query();
                    return $content;
                    break;
                case 'title':
                    $title   = '<div class="embed_post">';
                    $title  .= '<a href="'. get_permalink() . '" title="Read More">';
                    $title  .= get_the_title();
                    $title  .= '</a>';
                    $title  .= '</div>';
                    wp_reset_query();
                    return $title;
                    break;
                case 'excerpt': default:
                    $excerpt     = '<div class="embed_post">';
                    $excerpt    .= strip_tags(get_the_excerpt());
                    $excerpt    .= '<a href="'. get_permalink() . '" title="Read More" class="embed_post_more">Read More...</a>';
                    $excerpt    .= '</div>';
                    wp_reset_query();
                    return $excerpt;
                    break;
            }
        }
    }
}
add_shortcode('embed_post', 'embed_post_shortcode');
?>
