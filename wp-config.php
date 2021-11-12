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

// ** MySQL settings ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'dalal_wp1' );

/** MySQL database username */
define( 'DB_USER', 'dalal_wp1' );

/** MySQL database password */
define( 'DB_PASSWORD', '6f3jv2HSU95U' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

define('DISABLE_WP_CRON', true);

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          'G[SV]@q2X(+a5IMPgipWRSEu5x8Z9XRitGVID0yqgd:6t[mk4X1z3@Z9!/hysm!V' );
define( 'SECURE_AUTH_KEY',   ',y3%!{~Mi~@5Ez)yf)7xDa;Qm#%I:M}NwG4Gm+ z6m#4:uMpipb7FI~uD~n%XtY%' );
define( 'LOGGED_IN_KEY',     't+VXD+jf,DR547,J=Th~s>{t&o/kc~.kBssa3[.Ir1RJ0vk-OKFXxC~E{UN~I?4k' );
define( 'NONCE_KEY',         'fC>Z4;%/-:e<5H;vg:T@*GEF$zNj+=@ZZf-My]Psxl#a>pU],#kv;LKGhb0`.*-o' );
define( 'AUTH_SALT',         '&a+)kO1k5k>1{pi<)ig)}!&%.[nD_|,bA`syuu.1%|_=PQ5Rup)+(:S(2xc.JqG+' );
define( 'SECURE_AUTH_SALT',  '-2(Q|>qOEdf2n/|2(1NMP4~esjh`4hq4x]_{k] !WZ8S*sqk,j)iY=RrN77B$fYg' );
define( 'LOGGED_IN_SALT',    'KA1QE/FL/:Gi8;>_(K[tTMV@@;4Jb5wk3XwUz<?8yyCSd=jk!pPSdHI.WRox24#K' );
define( 'NONCE_SALT',        'NIx6DNQaVJk>z/]-i591T!:r)Lv-E9cO ozdHGO+g9)QpB$_Au0vX*N+bu=asA+0' );
define( 'WP_CACHE_KEY_SALT', '/jZ+#)T9N.42 )H].uPjt;9T*QQslN!/fW)5]^_W%N`K^v^uM:0+]Un/;6bsvdfd' );

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';




/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) )
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';