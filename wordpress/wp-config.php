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
define('DB_NAME', 'development');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 't/>/wise');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '+>4rl#+WfyG[un1gDo.H3fv)4n>,t=]FsPo^kjzW!|2kJ9Ot-d9in}+fR_~wYh:a');
define('SECURE_AUTH_KEY',  'UeR>NY70y#x>+vfbUw$_y?`Z8TWl>J]<93]$;$A+Db!D+6nV-kok+]2~^e)XpKEq');
define('LOGGED_IN_KEY',    '~o__jO?^+X3B.+bMLR7&/M.A<{bq<mO1aHS]Ta)<.+0MJFr-:_4(z6,;!-v,[4aq');
define('NONCE_KEY',        'KA3t3}=aoG8@:#ZIBRFr2; NLqBa1FJ2@Ln;_i~W-?f<?)hM^kn(|Q$[y{[^{enP');
define('AUTH_SALT',        '-5`NtIkyEq-|i/wp}^_bCWd?F.)7|@;8-dR>KQ|Q2_92G|v?4SN|qYM=Ci+4-QZ-');
define('SECURE_AUTH_SALT', 'gZ1s3#&{V`t+BONPBS{D$-)gH=}Y#r-oAJpdmaper5Zc;#4@uBB+whC2>&w<p4-]');
define('LOGGED_IN_SALT',   '{:N!Wrh/{;q^:5-OJ[MZe4t?S?o^;p|J>K-2fv077Ss,@M|;7H=y-i38|NBbA+SN');
define('NONCE_SALT',       'abkdw32om4A4Aqy-?2Ym6_m[VJY)]#6K~8bSw3^I$oD<ZPY4T*^D]|EsZraI?U2S');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', true);

define('MULTISITE', true);
define('SUBDOMAIN_INSTALL', false);
$base = '/';
define('DOMAIN_CURRENT_SITE', 'localhost');
define('PATH_CURRENT_SITE', '/');
define('SITE_ID_CURRENT_SITE', 1);
define('BLOG_ID_CURRENT_SITE', 1);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

/* Enable multisite */
define('WP_ALLOW_MULTISITE', true);
