=== BP Post Buttons ===
Contributors: normen
Donate Link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=X7SZG3SM4JYGY
Tags: buddypress, forum, buttons, smilies, smileys
Requires at least: WP 2.9.2, BP 1.2.3
Tested up to: WP 3.0, BP 1.2.4
Stable tag: trunk

Adds a button bar to buddypress forum posting textfields

== Description ==
This plugin adds a button bar to buddypress forum posting textfields.
Features:

<ul>
<li>Insert bold, italic, blockquote etc.</li>
<li>Insert URLs, Images etc</li>
<li>Insert smilies</li>
<li>Buttons can be shown/hidden via javascript</li>
<li>Uses imgur image uploading service to add images to posts</li>
</ul>

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload the bp-post-buttons folder to the /wp-content/plugins/ directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==
= I'd like to have the buttons above the text area =
Due to the limits of the standard buddypress hooks, this requires a theme and plugin edit:

Plugin - bp-post-buttons.php:
- remove all add_action lines below the smilies

Theme - groups/single/forum/edit.php:
Insert in edit-topic div:
&lt;?php if(function_exists('bp_post_buttons')){bp_post_buttons_topic();}?&gt;
Insert in edit-post div:
&lt;?php if(function_exists('bp_post_buttons')){bp_post_buttons_post();}?&gt;

Theme - groups/single/forum/topic.php:
Insert in post-topic-reply div:
&lt;?php if(function_exists('bp_post_buttons')){bp_post_buttons_reply();}?&gt;

== Changelog ==

= 1.0 =
* fix for WP 3.2

= 0.9.1 =
* add missing file

= 0.9 =
* improve URL entry for links
* add imgur image upload hints

= 0.8 =
* first version

== Upgrade Notice ==
