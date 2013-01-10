=== Embed Posts ===
Contributors: gskhanal
Tags: embed, post, page, excerpt, content, title
Requires at least: 2.6
Tested up to: 3.5
Stable tag: 0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Embed a Post within another Post or Page using [embed_post] shortcode

== Description ==
Embed a Post title or excerpt or content within another Post or Page. Use the shortcode([embed_post post_id="123"]

== Installation ==
1. Upload `embed-post` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Place `[embed_post post_id="123" type="content"]` in posts or pages where `post_id` is the id of post you want to embed and type is what you want to embed, if you do not define type then by default `excerpt` will get embeded. `content` and `title` are other options you can embed.

== Frequently Asked Questions ==

Q. Can I embed unpublished posts?
A. No, this plugin only embeds post which is published.

Q. What will happen if I do not specify `post_id`?
A. Nothing will happen if you do not specify `post_id` in Shortcode.

Q. What will happen if I do not specify `type`?
A. By default, `post excerpt` will be embedded if you do not specify `type` in Shotcode.

== Screenshots ==

== Changelog ==

= 0.1 =
* First Release