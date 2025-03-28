<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '{<M {ndMNGuAjn;n3|^T&2BM4xIV`7xT-(Zm_=z?<&3S3$opYt*Q|9%@(n-{hsq9' );
define( 'SECURE_AUTH_KEY',  'TTk6Mf[pCXGlz^wF,5!D<?NZm2/mM(>n&<gU)?B<rXx,M^9:NBaYcm+-D.mF!;7r' );
define( 'LOGGED_IN_KEY',    'aS_h*?-)Be4):0W+tk9^lg`R1@kl0iJp%)2z3^Dw`mIB7,YP>d;_im-y,AQ:TTR&' );
define( 'NONCE_KEY',        'hf6@.N[TPPZ6^5~rj$.uPlE<XQjR)$>=`*k24y-T(T<nZ`UvY#z;uPKW/;!vod U' );
define( 'AUTH_SALT',        '3Sk#n)Kr63+2//8jZS]gd/kT}=7Va_;A,2tjKx)Mu|Q}N1M*7KaB9LFnNLRTRe1U' );
define( 'SECURE_AUTH_SALT', 'gdPnd*58l4RN:(K,zz[N=,Q{o GF5BDpl+XQ#0H>Bb%m,o/:6mGCR!q3+Gf.S*a<' );
define( 'LOGGED_IN_SALT',   'gOKgk&d{+Nss2k2ck)~8d=6QuT*HqWy2077|$s5-+>Ul0}C@~GMC?CUSV4z}{2%H' );
define( 'NONCE_SALT',       'og*q):T^%9B8.C.^kzYk;InvnLDJiHj$J3~Dp5t-ZGcobupY@b~R.C^rEu=%/Y9O' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
