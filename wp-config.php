<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'fincrowd');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         'g>h4!2;yT=9y%Z`?^q^836g4AEs}T-ya_#,h2f`[S1!rNu{!Ojl/{z=JqF^u]_XL');
define('SECURE_AUTH_KEY',  'vJN/MB(SJc([i3t(4jquQl_H(5~AD|6b|}s):;XJ Rk^+[;lWEM?c_}9}.(`7TWt');
define('LOGGED_IN_KEY',    'JIhYyq@,sSeN]1nY?WL<qOBd;$kB,Zx*&(@>7%X_~=LcQzc<k?)$SQDXD}>u=59`');
define('NONCE_KEY',        '2>qVK}dCA@U%:af].6y=#gCse&qb ^%~[07e:Ol>tZwl%i0*(oqju%gF@!Slc4{N');
define('AUTH_SALT',        ']K34?V+}Z!OAmQ1-Fa%$`fzH}*4AxcghPGGDjpSN71Jr..QB^,P;T5(FV~Z.IYs^');
define('SECURE_AUTH_SALT', 'h^_6bL^,Dg/`;?o$0qR2&j<VAT=3X`OFtT*eO-snAKU6HI?x:.lgMT+[%@:|7inN');
define('LOGGED_IN_SALT',   '*d22!&R~D/<Q]z9_K7}wz/1YH!2L-$)sLT9m|p70a$`)]pnmbY@PO~;+a.Tl:QzJ');
define('NONCE_SALT',       'YXGrwLM3#b#c-?R?Bm++9jU?CEEokxd9t#B0wUDpLGSR9_fv{+e?4}IK8ymSm/G+');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', true);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
