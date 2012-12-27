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
define('DB_PASSWORD', '319154');

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
define('AUTH_KEY',         '7|+rUfgtCu4+-gMeJKwva&_t)jv}=ZEkH]_-Xw#&|tdcH`*x)mBy?KQ-R#KFKknx');
define('SECURE_AUTH_KEY',  'akM-?|+hXRV0*;F^_.SBJ)mgB)*LlQ[jTa1` I9SzC*z`P@Qr @E],w)_~+,#1Xj');
define('LOGGED_IN_KEY',    '0/52XghI)[j!&<Oz}_B4[Z(4|8_C+B+LtYc-sX|z$_}5>YCewDo5}90e,VOV= l:');
define('NONCE_KEY',        'rw-{8a>=fjm3<bqc4rnaa |tbt@u9O/>?V=|;QcEKwO2p&78(qr@z~d/RW57+eNg');
define('AUTH_SALT',        '%]1n`<bF2u7B$5wQ-*fY;Orq)nIW}BLO&nkQ<vm*CDcQBA2aIaMDpa]JPxH6M86:');
define('SECURE_AUTH_SALT', ' LR*]*zjqhock-w;EAJ+) ji(/XWOQ cp-p^[ ps)Aa]V@: #pUe|szNRz2o.$&3');
define('LOGGED_IN_SALT',   '07*Z+ _BXa)kljWIO&D|{qt2kpO5U0]frP[GpG$4dBxFhuK5;.h%94&K)lE,Tqfg');
define('NONCE_SALT',       'd*ZU+ILZ#VXP|jq7DSf{r]F-6biJr_8/-iK?1Y4#1=zVpO!$:#?E>{vhYC))AL=!');

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
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
