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
define('DB_NAME', 		$_SERVER['DB_SCHEMA_IANMOFFITT_CO']);

/** MySQL database username */
define('DB_USER', 		$_SERVER['DB_USER_IANMOFFITT_CO']);

/** MySQL database password */
define('DB_PASSWORD', 	$_SERVER['DB_PASSWORD_IANMOFFITT_CO']);

/** MySQL hostname */
define('DB_HOST', 		$_SERVER['DB_HOST_IANMOFFITT_CO']);

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
define('AUTH_KEY',         'mG!;P-8-(|6o<Y<L!kU23&)|V[Qc=/#M_8.a6V&Nfpyuk+1g-Vx>nv+|uKnTF!bn');
define('SECURE_AUTH_KEY',  'H+BwI)l]!y#e*o|G/+xKGflEpWUE`{tXiol&+ wxk^Kvo4JFnH+wLHi==Qj1*chK');
define('LOGGED_IN_KEY',    'z*PeWu]1)W9wZ2,#5sMiW&$;Nl/k]fY1!s/1-5w*k}:Bu KB)<#|buNaQv:jMs%v');
define('NONCE_KEY',        '^{q/l8p:<4z&<pE1da1>P.7v@1Z|%@|+^ckV+Y+;&F+kj%vwpp.4u|>SzT2-Fom?');
define('AUTH_SALT',        '![VsBmf-t7A= y*r}sY/3qtQ.3)p3M+NcZ<J$w0zLc(.YCEHsH#LN020R7T&w?Zf');
define('SECURE_AUTH_SALT', 'e}=yTU(FV-kqZ``g*cO/N#:wU%tTX-]]1@1=9A;UWQqq89x9ce+?-7LA#smwi#85');
define('LOGGED_IN_SALT',   '(d/|{Oqz%m+$L79Z`QSqV;^}*-s2v[{Vl2+;?$pkkuY^-fq^;@NqH27G_;(ql6P&');
define('NONCE_SALT',       'x]U4N`<XF]wG#*w_0_(<:Udv<*gYrQT|g:@?@?{/)-bOO-JNN2N&1Bjh<7M[>^JH');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

$env = $_SERVER['APP_ENVIRONMENT'];
$domain = $_SERVER['APP_DOMAIN_IANMOFFITT_CO'];

$baseUrl = ('http' . ($_SERVER['HTTPS'] ? 's' : null) . '://') . $domain;

define('WP_HOME', $baseUrl);
define('WP_SITEURL', $baseUrl);
define('WP_CONTENT_URL', $baseUrl."/wp-content");

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

// if(is_admin()) {
// 	add_filter('filesystem_method', create_function('$a', 'return "direct";' ));
// 	define( 'FS_CHMOD_DIR', 0751 );
// }
