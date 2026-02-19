

<?php

/************************************************************************************/
/* Load local config file with sensitive informations. In production enviroment
	wp-config-local.php should be located in upper directory of document_root
	directory. */
/************************************************************************************/
if ( file_exists( dirname( __FILE__ ) . '/../.env' ) ) {
	require ( dirname( __FILE__ ) . '/../.env' );
} elseif ( file_exists( dirname( __FILE__ ) . '/.env' ) ) {
	require ( dirname( __FILE__ ) . '/.env' );
} else {
	die( '.env file is missing' );
}


/************************************************************************************/
/* Constants specific for local/server enviroment */
/************************************************************************************/
if ( IS_LOCALHOST ) {

define('WP_DEBUG', true);
define('WP_DEBUG_LOG', false);
define('WP_DEBUG_DISPLAY', false);
	define( 'DISALLOW_FILE_MODS', false );
	define( 'ALLOW_UNFILTERED_UPLOADS', true );
	define( 'ENABLE_CACHE', false );
    define('DISALLOW_FILE_EDIT', true);


} else if ( ! IS_LOCALHOST ) {

	define( 'WP_DEBUG', false );
	define( 'WP_DEBUG_DISPLAY',false );
	define( 'WP_DEBUG_LOG', false );
	define( 'DISALLOW_FILE_MODS', true );
	define( 'ALLOW_UNFILTERED_UPLOADS', false );

} else {
	die( 'IS_LOCALHOST is undefined' );
}

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
define('AUTH_KEY', 'put your unique phrase here');
define('SECURE_AUTH_KEY', 'put your unique phrase here');
define('LOGGED_IN_KEY', 'put your unique phrase here');
define('NONCE_KEY', 'put your unique phrase here');
define('AUTH_SALT', 'put your unique phrase here');
define('SECURE_AUTH_SALT', 'put your unique phrase here');
define('LOGGED_IN_SALT', 'put your unique phrase here');
define('NONCE_SALT', 'put your unique phrase here');

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


/* Add any custom values between this line and the "stop editing" line. */

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if (! defined('ABSPATH')) {
    define('ABSPATH', __DIR__ . '/');
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
