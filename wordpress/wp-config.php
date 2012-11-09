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
define('DB_NAME', 'goupsmar_wrdp1');

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
define('AUTH_KEY',         'qAM -7E@$GQR90#h9;?x*W2~#smuKF5x-QPxkI:hT[!q2xr{@5-uLx9~z:PEw7_;');
define('SECURE_AUTH_KEY',  'Ba>EYDPW:+>6#7CudBx(2^O/C>tXJ)SB2~&jrBxy(6+,!d<roXITetu!--zQ V(U');
define('LOGGED_IN_KEY',    'ej}mHy_c0y]{kim3,@F3Jf^g[#(h0/}a*[{Lo#MLYZ<L]P~W+zu7^< CX?deW/8k');
define('NONCE_KEY',        'HjZ[qtXf`u4T zX+VMXoxB`ND-JSZJAI)?<&~F-,c)R2-DN,;cEN!!$y[%wlp2!z');
define('AUTH_SALT',        'V?/6Ey8TYT5uj</YGih^cq5e-pyhtgWl+FC5~UeH+8wjZ]QKnYtA#KF;V)::r#Pb');
define('SECURE_AUTH_SALT', 'hJ#ljvI8>G-DpYc6u~}fD0*RJ!ew}CtlqSA?]s>bIz$5oC]4eCO<sMK|pAzor?0T');
define('LOGGED_IN_SALT',   '$(~@Nb<v/As&[;zCG8DBWGD(I{bOEGvGSY>$SI Ck>xEtt*-/+adk4+vt:f@-oOR');
define('NONCE_SALT',       'l~0r@+gkG`|TP{RS/QpIf?s /|jAoaH`-j:hB@wH+IaV=.qHR@B^q?dl#R|#]T/;');

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
