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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'daniel_db' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'admin123' );

/** MySQL hostname */
define( 'DB_HOST', '10.5.0.2' );

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', '-g7Y7~6;v1g3sU1svfnwAFA[5g5~%3Bxm6#!z&F5~;wy_7RT~l23B[5f]Yr92!|U');
define('SECURE_AUTH_KEY', 'BQ5vEH*(:h+bxP;/7apN[*7lXP6Q*jSkZ9-&gx4J~N1a%~[)5/1#c~Us+:z60]*6');
define('LOGGED_IN_KEY', 'o(#530aT/P5#&Z9&0aNbkhm!4FH*Mp41g!3gK4(6+DsA0A5fqk/~mj;1wc))y3O4');
define('NONCE_KEY', ':r|ryI*Mk9UFtzJJXc&MKT_J7D6A_l#2P;;%!&z4rFI430x/t|QV[@6X+6h01II&');
define('AUTH_SALT', '4X;c)I455;h8Jp@3(w5VSDvFS60Q1NPHnR-|3b;ABdjT8zDf*Q9]E;T5Fg63KG0%');
define('SECURE_AUTH_SALT', 'L/k15@3n43x@;+FA3D&Y8:-)@17-8P6W0VY!Z!39@y5R+5y]X0G;@PF5GUqJP+w[');
define('LOGGED_IN_SALT', '3E41A:x42z75P+rfn|7[J4UW;j8Xu_HeW(gaNf9jsm)97u-e7HI(z#]1)7y2V_(]');
define('NONCE_SALT', 'Ti+6#(56WR]s!-[9D2-CD&c1CD2%VtZ+IV|h2SQ94]UGsc5KwLbc/MPX&z8!723w');

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'af_';




/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}
define('WP_MEMORY_LIMIT', '256M');
/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
