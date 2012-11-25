<?php
/*
Plugin Name: BP Post Buttons
Plugin URI: http://www.jmonkeyengine.com
Description: Adds a button bar to buddypress posting textfields
Version: 1.0
Author: Normen Hansen
Author URI: http://www.jmonkeyengine.com
License: BSD
*/

//tags, label => tag
//end tag with "]" to use bracket style tags
$BP_POST_BUTTONS_TAGS = array(
        '<strong>bold</strong>'=>'strong',
        '<em>italic</em>'=>'em',
        '<del>delete</del>'=>'del',
        'list'=>'ul',
        'listitem'=>'li',
        'blockquote'=>'blockquote',
);

//smilies, button smilie => insert smilie
$BP_POST_BUTTONS_SMILIES = array(
        ':)'=>':)',
        ';)'=>';)',
        ':D'=>':D',
        '8)'=>'8)',
        ':|'=>':|',
        ':('=>':(',
        ':x'=>':x',
        ':?'=>':?',
        ':o'=>':o',
        ':eek:'=>'8O',
        ':evil:'=>':evil:'
);

//comment these out when modifying theme directly
add_action('groups_forum_new_reply_before','bp_post_buttons_reply');
add_action('bp_before_group_forum_post_new','bp_post_buttons_topic');
add_action('bp_group_before_edit_forum_topic','bp_post_buttons_topic');
add_action('bp_group_before_edit_forum_post','bp_post_buttons_post');


function bp_post_buttons() {
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'bp-post-buttons-js', WP_PLUGIN_URL . '/bp-post-buttons/include/js/insert_tags.js' );
}
add_action( 'init', 'bp_post_buttons');

function bp_post_buttons_enqueue_styles() {
    wp_register_style('bp-post-buttons-css', WP_PLUGIN_URL . '/bp-post-buttons/include/style/bp_post_buttons.css');
    wp_enqueue_style('bp-post-buttons-css');
}
add_action( 'wp_print_styles', 'bp_post_buttons_enqueue_styles' );

function bp_post_buttons_tags($form, $element) {
    global $BP_POST_BUTTONS_TAGS;
    echo('<div class="bppb_headroom">');
    foreach ($BP_POST_BUTTONS_TAGS as $k => $v) {
        if(strpos($v,"]")!=FALSE) {
            echo("<a href=\"javascript:insert_tag('$form','$element','[$v','[/$v');\">$k</a>");
        }
        else {
            echo("<a href=\"javascript:insert_tag('$form','$element','<$v>','</$v>');\">$k</a>");
        }
    }
    echo("</div>");
}

function bp_post_buttons_inserts($form, $element, $snippetstag) {
    //uncomment and use insert_body instead of insert_body_disabled to hide by default
//    echo('<span class="insert_head"><b>[Insert Media..]&nbsp;</b></span>');
    echo('<div class="insert_body_disabled bppb_headroom">');
    echo("<a href=\"javascript:insert_tag_url('$form','$element','<a href=\'','</a>');\">link</a>");
    echo("<a href=\"javascript:insert_tag_url('$form','$element','<img src=\'','</img>');\">image</a>");
    bp_post_buttons_imgur();
    //echo("<a href=\"javascript:insert_tag_youtube('$form','$element','','\\n&lt;!--leave this comment below the link!--&gt;');\">embed youtube..</a>");
    bp_post_buttons_snippets($snippetstag);
    echo("</div>");
}

function bp_post_buttons_smilies($form, $element) {
    global $BP_POST_BUTTONS_SMILIES;
    echo('<span class="smile_head"><b>[Show Smilies..]</b></span>');
    echo('<div class="smile_body bppb_headroom">');
    foreach ($BP_POST_BUTTONS_SMILIES as $k => $v) {
        echo("<a href=\"javascript:insert_tag('$form','$element','$v','');\">".convert_smilies($k)."</a>");
    }
    echo("</div>");
}

function bp_post_buttons_header() {
    wp_enqueue_script( 'bp-post-buttons-js');
    echo("<div class='generic-button'>");
}

function bp_post_buttons_footer() {
    echo("</div>");
}

function bp_post_buttons_snippets($type) {
    if(function_exists('bp_code_snippets_init')) {
        $url = BP_CS_PLUGIN_URL.'/bp-cs-snippet-selector.php?type='.$type.'&tab=add&TB_iframe=true&amp;height=500&amp;width=640';
        ?>
<a href="<?php echo $url;?>" class="thickbox button" title="<?php _e('Add existing snippet..','bp-code-snippets');?>">insert snippet..</a>
        <?php
    }
}

function bp_post_buttons_imgur() {
    $url = WP_PLUGIN_URL . '/bp-post-buttons/include/imgur-intro.php?TB_iframe=true&amp;height=620&amp;width=700';
    ?>
<a href="<?php echo $url;?>" class="thickbox button" title="<?php _e('Upload image via imgur..','bp-post-buttons');?>">upload image..</a>
    <?php
}

function bp_post_buttons_show($form, $element, $snippetstag) {
    bp_post_buttons_header();
    bp_post_buttons_tags($form,$element);
    bp_post_buttons_inserts($form,$element,$snippetstag);
    bp_post_buttons_smilies($form,$element);
    bp_post_buttons_footer();
}

function bp_post_buttons_reply() {
    bp_post_buttons_show("forum-topic-form","reply_text","forum-reply");
}

function bp_post_buttons_topic() {
    bp_post_buttons_show("forum-topic-form","topic_text","forum");
}

function bp_post_buttons_post() {
    bp_post_buttons_show("forum-topic-form","post_text","edit-post");
}

?>
