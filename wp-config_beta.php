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
define('DB_NAME', 'five-insurance_fincrowdbet');

/** MySQL database username */
define('DB_USER', 'five-crowdbeta');

/** MySQL database password */
define('DB_PASSWORD', 'f1v3crowd2017!');

/** MySQL hostname */
define('DB_HOST', '178.32.40.58');

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
define('AUTH_KEY',         '@kR1B$R=(t%K<X}LCb@.9&D,vwI/.-1RoG;m)CwVo2H$*zfjB*c@mMNJ%kq~GwD5');
define('SECURE_AUTH_KEY',  '&8UG_es)ae3V&,h]**3TvwT9/t$K%s]A[SWdixL@f;O4{*[xp(mK/mp@6P/P&w98');
define('LOGGED_IN_KEY',    'Qkr6d;f/>!89WkH$Ojo=(G4hPxiO@ep`?[j?-LOj4Oc,x^$&p=-{p/J$@MB8DWR~');
define('NONCE_KEY',        'W1zRNq;+sFW!=nhX+-ICIYqD9+C;qg2qQ3zQ`BCOBuByT>R1XTWAfFItQn:8T&%d');
define('AUTH_SALT',        '-ELh8Msu<}3}y./u^HgbKKW(j?Eg&5;n{t($0%U_Az`.>(@~d:VY^A83YJ!^~*r;');
define('SECURE_AUTH_SALT', 'ZHFSM3;ZM{~ORV,}g6%o[^XC>hzN(g()%cS(//>N:#!0%Y-[G?*Y0%E8N2dP6eDu');
define('LOGGED_IN_SALT',   'pd#lRiGISvJu6-l3<f?t;$Q[}kxkT#ns<I<f3BE|]|`~WvIXl/7lF?8FAwQLY;zn');
define('NONCE_SALT',       'nR]:XSV7N2]I{e2vH6Jy4lQ@5=.([,SyGFXrW!p%?m|6MIv:Pp!P]NS#>0xy:sj#');

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
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
