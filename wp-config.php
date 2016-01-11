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
define('DB_NAME', 'bgscena');

/** MySQL database username */
define('DB_USER', 'vkolova');

/** MySQL database password */
define('DB_PASSWORD', 'vesselina');

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
define('AUTH_KEY',         'OCaeqpo,CVYY zvu}J/Ya=P-8|8*@etSuPau?&/#qk=1)D5fX6MC|g>Zb8^FFfq5');
define('SECURE_AUTH_KEY',  '4-nbO#W2R<*t|I ACE<yWa-OG4^-|e48bh0VzvFN0!HZmp`cZ0(mYEvssinU]kA3');
define('LOGGED_IN_KEY',    'k4n9gsr{)P#_<ZlGaJhF][3EMkcnoc]#[s6HCE&?:nF;VY4{9HfVjfPt<!Y=}(;2');
define('NONCE_KEY',        'F$RlNJRSQ2npl8|aN?wke&Z5HQir4bUjGHM$Rv-~|{uT`M+QCEonh={>m=f lN^r');
define('AUTH_SALT',        'f:!3CfpPI!6d~(5Jw%6UE*S|vh b-4CzQMO`q, 7d0/Yfj.uT8Yy*@qzz~Teg$u{');
define('SECURE_AUTH_SALT', 'G)NF4SGrGC:DZxl.,*~$Gu+8;90~G!k)NAO[Y+2)<mJCkSkwXL>@?zr-4=Wc$:a}');
define('LOGGED_IN_SALT',   'c,;6~&(X&ma-9|r4gDwZhM ?:Wh6.XTlo-p`|{Z2MX|@(VTDs||LSRKjP8:jb}Yb');
define('NONCE_SALT',       '+LRY%T+Aeep+rt3m?1;{iw--bKP|]aPHRc)MG~Kws`(hmy!4mz9u(@|DO+(_Ff^!');

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
