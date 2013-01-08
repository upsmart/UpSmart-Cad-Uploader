<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'upsmart');

/** MySQL database username */
define('DB_USER', 'upsmart');

/** MySQL database password */
define('DB_PASSWORD', 'y42SP69v4y');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

define('WP_MEMORY_LIMIT', '240M');


/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '0r933knpixffdbkr3uoknt1svf0k4jkvfj34lznfvz4irnimhy3dzd21wtqqoxgf');
define('SECURE_AUTH_KEY',  'vytzxwzrbff26sae67uo3xejmpnajiyum9cvtp9ukyrit5a6ivopew8pwoe74p1b');
define('LOGGED_IN_KEY',    'acr2jyic2mc71o1tmlu6yoenlggg5kjf4os8eo3ww0s12uc1f7rrxiroevoetwm0');
define('NONCE_KEY',        'mb10hneisymhcpytcq99z5r1tcmb7dm7ipdx7ohcqaefiyyjktdfwdgbakbuqy40');
define('AUTH_SALT',        'wxvxegnaqgrs4jgc6iuyfueubu8tyr97kiicizsfatwovnf1kq1p2h0yc9gjmujz');
define('SECURE_AUTH_SALT', 'oq631mbdvxumhxuqipkljrosdrvdzmaeotxmwse7rbqj6z2cx9ysq3yl3fpaahqb');
define('LOGGED_IN_SALT',   'c55ztynccrcpz4zjdf16ezwgptfsdnxfzhdq4gouws9wvewkushsqxpoi54dcpud');
define('NONCE_SALT',       'qb58bxnb39zpcr1nogxxe70bg4ibdkmtyqdczimgsm7zluqyl6jaxjpnz5fodvxp');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'vcm_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress.  A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define ('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

define('WP_ALLOW_MULTISITE', true);

define('MULTISITE', true);
//Needed to allow domains for subsites to work.
define('SUBDOMAIN_INSTALL', true);
/*$base = '/';
define('DOMAIN_CURRENT_SITE', 'www.go-upsmart.com');
define('PATH_CURRENT_SITE', '/');
define('SITE_ID_CURRENT_SITE', 1);
define('BLOG_ID_CURRENT_SITE', 1);*/

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Allows domain mapping for wordpres multisite. */
define( 'SUNRISE', 'on' );

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

