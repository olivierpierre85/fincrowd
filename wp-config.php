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
define('DB_NAME', 'fincrowsbeta');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

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
define('AUTH_KEY',         'N{q1#R+{LhfY{1thQ_2e=BioHxsKwr|!NngR@HQn)o-a5FVFX!9!SOCG-8|$Rj@O');
define('SECURE_AUTH_KEY',  'bYyB|^@;ptSY+E`!c^CyO&li8Q%H>~C7JRk=| tq5$4$g/gT|3b!;u[i*JQjg8>p');
define('LOGGED_IN_KEY',    'Nb<~-e+wwxtX`%U@VT}4_a4]2>x?:I;+r$RhwZT^T2{]~iWAR.t!7Zy2^yVy(zdv');
define('NONCE_KEY',        '[;YuM0R_$Ts6A >1!m22J3$]%^3W^Bk/zFo:zC_Qn+@NKrSO;H/Uir(xR!HO#bp+');
define('AUTH_SALT',        '_%Sn9On<T8^x]`MgY(my>{~9#] dN;dUE}H?N.s7`d1oO1JEnk}9weWzs[.oTDlT');
define('SECURE_AUTH_SALT', 'AV9Vs%PUwlne-3o-zzWA~xaGt(f{/(W%uvx&qz;AVVc+2:H;;X?z[ULo`wlo*MpP');
define('LOGGED_IN_SALT',   '2qZNGPqCPMzdS#)} S*k0}&uS~yk7?-i(6.cqc$P~{$1]6J3<YRr)j,0,b.e]B @');
define('NONCE_SALT',       'hOZ63%!nFqbQc&W<^D=ng0$@19+$TU(QZ!c1]%F?mSL6Kp,c/nS?*,ZKx;7EA5nS');

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
