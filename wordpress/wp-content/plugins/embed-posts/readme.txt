=== Embed Posts ===
Contributors: dtyler11
Tags: embed, post, page
Requires at least: 2.0
Tested up to: 3.2.1
Stable tag: trunk

Embed a Post within another Post or Page

== Description ==
Embed a Post within another Post or Page. Use double brackets and the post's Slug (ex. [[about-us]]) to embed.

== Installation ==
1. Upload `embedposts` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Place `[[post-slug]]` in posts or pages where post-slug is the Slug of the post you want to embed

== Frequently Asked Questions ==

Q. Why isn't my post embedding?
A. Your theme might not be using the_content() to display the page (get_the_content will not work).

Q. Can I change how the title or post looks?
A. Yes! How the embedded post looks can be changed using the CSS classes post_title and embedded_post.

Q. Can I stop a post from embedding?
A. Yes! Use backticks before and after the double brackets to display the original text. For example, `'`[[about-us]]`'` will be displayed as <code>[[about-us]]</code> rather then rendering the embedded post. This can be useful to show users how to use the plugin. 

== Screenshots ==

== Changelog ==

= 1.4 =
* The title of a embedded post can now be toggled. Set a custom meta field called <code>embed-hide-title</code> with a value of true in either the child embedded post or in the parent page or post. If you set the field in a child post, then the title will not be displayed on any parent page. If you set the field in a parent, then none of the embedded posts on the page will have titles.

= 1.3.1 =
* Added backtick support to disable embedding a post. The original [[post-slug]] text will be displayed in a <code>code element</code>

= 1.3 =
* Embedded Titles now use the h2 element. Use the css class post_title to override the style of each title.
* All types of posted content are now searched for, including children of other posts.

= 1.2 =
* Embedded Post follows same style rules as standard posts
* Efficiency Improvments

= 1.1 =
* Fixed missing function for some themes
* Added 'Edit this Section' link next to post for editors

= 1.0 =
* First Release