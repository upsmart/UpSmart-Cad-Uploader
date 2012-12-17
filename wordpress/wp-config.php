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
define('AUTH_KEY',         '#tA+n/EzU@p4[VV$&@(v$UYxb_`lO0xvr3oZkf`Ka<!h@J.2jW^4eMQ_QjC[T -1');
define('SECURE_AUTH_KEY',  '<I(P,fhf1E40R`(@^q3,z*u==mZ@%x_J3V2pn!^{#mV30zH)CrYv/Fo5)?+~P{:e');
define('LOGGED_IN_KEY',    '#{qQ~m#)x^{G|A$$XbuX,JmS|O?+cQF%X90T(k`f*GSu){LgO8hC>GY)mMd>3[1F');
define('NONCE_KEY',        '6 ;xmPd^_c*hp|}T2_JA2dE0<>A<.~*NR%jp=pj$[[$Vp~WWNE*wfee_<J/3p,+X');
define('AUTH_SALT',        'mU|aWj=s1ysz@oj{y/osQAbogt9D&7UD=4K5v7Sa2sa&X/:1cTu)uNhG$mRmmPdo');
define('SECURE_AUTH_SALT', 'ZyWi:-}Uk6.J!nN`nH5t9O0n+x1HDO/Px3Nua7)`kW5ZaRMPNg|e/v~h.G;@ry<1');
define('LOGGED_IN_SALT',   'o,LfYuhnv1;9z(}3o^)UitP-`+/70+KDWp2;=,ZZ,l^-BJ1<{0C$~_Yc8<Y.pb5;');
define('NONCE_SALT',       'LXHht(p2fS_DZk7-|OG?g7ZAT~!n=7o?=_BW*dLXnJ(N*yGPy!~2SbQ6@C?sB1Yo');

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

