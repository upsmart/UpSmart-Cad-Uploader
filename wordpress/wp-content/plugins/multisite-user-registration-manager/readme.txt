=== Multisite User Registration Manager ===
Contributors: zaantar
Tags: user, users, registration, manager, multisite, moderation, moderating
Donate link: http://zaantar.eu/index.php?page=Donate
Requires at least: 3.4
Tested up to: 3.5
Stable tag: 3.1

Provides a system for registration requests and their processing in multisite. Two-level moderation. 

== Description ==

Provides a system for registration requests and their processing in multisite. 

Blog administrator can place a shortcode `[murm-form]` on their blog, which will display a registration form. Visitors can then use this form to request registration. Such a request must be first moderated by the blog admin. If it is accepted, it's passed on to the blog superadmin, who also has to approve it in order to pass. After approval the plugin creates given user accounts with subscriber rigths on the blog from which it was requested and sends login information to the user. 

Per-blog settings

* can be deactivated
* notification via nag or e-mail on new requests
* antispam (needs Akismet API key)
* requests on the blog admin level can be automatically approved

Per-site settings

* optional e-mail notifications
* custom e-mail templates
* blog administrators can be allowed to delete a request without notifying it's author
* requests on the network admin level can be automatically approved (this means that only blog administrators decide about their requests, if no error occures)

This plugin was developed for a small blog server (cca 30 blogs), so that superadmin would have control of newly created accounts (because some people were confused and got themselves unneccessary multiple user accounts).

Makes use of the [Wordpress Logging Service](http://wordpress.org/extend/plugins/wordpress-logging-service/).

In future there will be more options to modify default plugin behaviour.

== Installation ==

* Install as usual. 
* Access settings for single blog via Options --> MURM
* Request moderation page for blog admin is at Users --> MURM Requests.
* Access network settings via Network Admin --> Options --> MURM
* Request moderation page for superadmin is at Network Admin --> Users --> MURM Requests.

== Frequently Asked Questions ==

[Ask me](mailto:zaantar@zaantar.eu?subject=[murm])

= What about bugs? =

This plugin is developed for private use, but has perspective for more extensive usage. I can't guarantee any support in the future nor further development, but it is to be expected. 

Kindly inform me about bugs, if you find any, or propose new features: [zaantar@zaantar.eu](mailto:zaantar@zaantar.eu?subject=[murm]).

If you experience any problems:

1. Update to the latest version.
2. Use [Wordpress Logging Service](http://wordpress.org/extend/plugins/wordpress-logging-service/) plugin and register MURM with it (as well as `murm-extended` log category) or activate fallback logging to file in Network Admin --> Options --> MURM
3. Reproduce the error.
4. Send me the logs (from WLS or from log.txt file, which is created within plugin directory) with description of the problem.

== Changelog ==

= 3.1 =
* compatibility with WordPress 3.5
* a bit more information on extended log
* (hopefully) fixed: not sending e-mail notification to blog admins
* rename Murm::load_textdomain() to load_plugin_textdomain() to hopefully solve an issue with Codestyling Localization plugin (http://wordpress.org/support/topic/language-problem-11)
* correct usage of $wpdb->prepare()
* Added a note about solving Akismet issues.
* Update Wishlist.

= 3.0.4 =
* wishlist created (see Other notes)
* "Network: true" added to the plugin header
* minor bug fixes

= 3.0.3 =
* fixed: bug while deleting registration request on blog level

= 3.0.2 =
* fixed: problem with loading network options

= 3.0.1 =
* quick bugfix: using non-existing function in extended logging mode
* sorry guys, i know the plugin is a mess now, but i currently have no free time at all - in case of further trouble (a) seek answers in support forum (b) use older version (below 3.0) (c) please be patient

= 3.0 =
* massive code restructuralization - getting ready for adding more features

= 2.1.5 =
* akismet settings on network level
* possibility to permban registration spammers if Superadmin Helper plugin is present and permban feature activated
* log levels of spam registration messages adjusted
* possibility to disable logging of spam registrations

= 2.1.4 =
* minor bug fixes
* autoapprove option on admin level (activate in Options --> MURM)

= 2.1.3 =
* fix: skipping mail to blog admin on approval on the network level only if it's the current user
* reimplemented optional fallback logging to file
* better logging of mysql errors, etc.
* added estonian translation

= 2.1.2 =
* added donation button on network settings page (with an option to hide it)
* removed broken fallback logging into the file
* optional sending of e-mails (network option)
* optional request auto-approving on network admin level
* fix: removed czech description from plugin header
* updated POT file and czech translation

= 2.1 =
* "mailto:" links on request moderation pages
* fixed: showing notification for unprocessed requests only to blog admins
* fixed: not sending notification e-mails from user to itself
* add automatically "http://" before blog URLs when missing
* superadmin can disable admin's ability to delete a request without notifying it's author
* extended debug logging option
* moved WLS settings to the network admin settings, where they belong
* keyword description on network admin settings
* shortcode form keeping entered values after input error

= 2.0.2 =
* i18n bugfix (arrrrg!)

= 2.0.1 =
* i18n bugfix

= 2.0 =
* code restructuralization and cleanup
* added site options page
* custom mail messages

= 1.5.1 =
* minor i18n bugfixes
* added POT file and czech translation

= 1.5 =
* minor improvements and bugfixes
* i18zed
* added to wordpress plugin repository

= 1.4 =
* cooperation with Wordpress Logging Service
* minor improvements

= 1.3 =
* debug logging

= 1.2 =
* valid username/e-mail input check
* antispam (Akismet)

= 1.1 =
* feature: delete registration requests without disapproval e-mail (admin and superadmin)
* minor bugfixes

= 1.0 =
* first version

== Upgrade Notice ==

= 3.0 =
Massive code restructuralization. MAY contain some bugs. I need feedback - will spped up the release of next version.

== Wishlist ==

Below are listed requests I know about and I'm going to process... eventually. Please be VERY patient.

* "I have a request of asking for password on registration form.  That way the user can pick out a password and have it automatically populated in the site user data."
* sidebar registration widget
* attach custom admin message to user on request approval/denial
* correct i18n, custom blog admin messages
* custom shortcode css (error/ok) + information
* save all settings as a single wp option
* combine with pure-murm?
* "I would like to add more fields like "country" in the Registration Page."
* custom new user default role
* recaptcha on registration
