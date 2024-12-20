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
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'prixz_ecommerce' );

/** Database username */
define( 'DB_USER', 'admin' );

/** Database password */
define( 'DB_PASSWORD', 'emj0VuockwjT2X0' );

/** Database hostname */
define( 'DB_HOST', 'prixz-database.cryqyoq80eey.us-east-1.rds.amazonaws.com' );

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
define( 'AUTH_KEY',         '|a96qGtf61{*QqR#uT8J.{ObaY|ZkY~gbA;IN@KCw]%( jO;y!zT+tS8yJ>iE(ZB' );
define( 'SECURE_AUTH_KEY',  '^gcY%m1?U@A8f2M)x.i0bv8;qq-pLKp0+C;E+q4ov8h,5WLv~=B#&cS)|$x(49G.' );
define( 'LOGGED_IN_KEY',    'VykG^u#nT[lRPv:#20lvbb(_;6T}[?RkU^M?@a]9S_yQb*zAr&RhjI4q|5{>DlVX' );
define( 'NONCE_KEY',        'c,Yw20U*`}&Tpf8VBp!wZD*&Ic:MPzHQ&R}i[1S#zFzKOVp}%gQRe?B[X|y !*Y@' );
define( 'AUTH_SALT',        '1t(QhS^UgDE2|}?ffs3uz2L!Tnwy)l*%ml)0oYUb2j)jjQP3oIRkEkEz,$Mny]K,' );
define( 'SECURE_AUTH_SALT', '.mG4c/Ut@*[?]Gq^P:Z&hi@5tL[O94A 6aQHYGEHlhY0h0{Dz&P%M#@kjg`>iZ=.' );
define( 'LOGGED_IN_SALT',   '(^sQ7E{.LB6.5W:HHjY4,,TtSfFq/%<i#(>vca~0~]t(Z3O~hA;rk_1dqR^]/{IG' );
define( 'NONCE_SALT',       '|5kZt!~&*=E7Wd+7rp.6BDFs3yt=WnEt($~Fe@aQa&dF~9r^~EU}m01wUP=&4)DL' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
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
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */

 
define('WP_DEBUG', false);
define('WP_DEBUG_LOG', false);
define('WP_DEBUG_DISPLAY', false);


@ini_set('upload_max_filesize', '128M');
@ini_set('post_max_size', '128M');
@ini_set('max_execution_time', '300');
@ini_set('max_input_time', '300');


/* Add any custom values between this line and the "stop editing" line. */

define('FS_METHOD', 'direct');


/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
