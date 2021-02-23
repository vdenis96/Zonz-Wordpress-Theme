<?php
/**
 * Plugin Name: YITH WooCommerce Color and Label Variations Premium
 * Plugin URI: https://yithemes.com/
 * Description:  YITH WooCommerce Color and Label Variations Premium replaces the dropdown select of your variable products with Colors and Labels
 * Version: 1.0.7
 * Author: Yithemes
 * Author URI: https://yithemes.com/
 * Text Domain: ywcl
 * Domain Path: /languages/
 *
 * @author Yithemes
 * @package YITH WooCommerce Color and Label Variations Premium
 * @version 1.0.7
 */
/*  Copyright 2015  Your Inspiration Themes  (email : plugins@yithemes.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if ( !defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

if ( ! function_exists( 'is_plugin_active' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

function yith_wccl_premium_install_woocommerce_admin_notice() {
	?>
	<div class="error">
		<p><?php _e( 'YITH WooCommerce Color and Label Variations Premium is enabled but not effective. It requires WooCommerce in order to work.', 'ywcl' ); ?></p>
	</div>
<?php
}

if ( ! function_exists( 'yith_plugin_registration_hook' ) ) {
	require_once 'plugin-fw/yit-plugin-registration-hook.php';
}
register_activation_hook( __FILE__, 'yith_plugin_registration_hook' );

// Free version deactivation if installed __________________

if( ! function_exists( 'yit_deactive_free_version' ) ) {
	require_once 'plugin-fw/yit-deactive-plugin.php';
}
yit_deactive_free_version( 'YITH_WCCL_FREE_INIT', plugin_basename( __FILE__ ) );

if ( ! defined( 'YITH_WCCL_VERSION' ) ){
	define( 'YITH_WCCL_VERSION', '1.0.7' );
}

if ( ! defined( 'YITH_WCCL' ) ) {
	define( 'YITH_WCCL', true );
}

if ( ! defined( 'YITH_WCCL_PREMIUM' ) ) {
	define( 'YITH_WCCL_PREMIUM', true );
}

if ( ! defined( 'YITH_WCCL_FILE' ) ) {
	define( 'YITH_WCCL_FILE', __FILE__ );
}

if ( ! defined( 'YITH_WCCL_URL' ) ) {
	define( 'YITH_WCCL_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'YITH_WCCL_DIR' ) ) {
	define( 'YITH_WCCL_DIR', plugin_dir_path( __FILE__ )  );
}

if ( ! defined( 'YITH_WCCL_TEMPLATE_PATH' ) ) {
	define( 'YITH_WCCL_TEMPLATE_PATH', YITH_WCCL_DIR . 'templates' );
}

if ( ! defined( 'YITH_WCCL_ASSETS_URL' ) ) {
	define( 'YITH_WCCL_ASSETS_URL', YITH_WCCL_URL . 'assets' );
}

if ( ! defined( 'YITH_WCCL_INIT' ) ) {
	define( 'YITH_WCCL_INIT', plugin_basename( __FILE__ ) );
}

if ( ! defined( 'YITH_WCCL_SLUG' ) ) {
	define( 'YITH_WCCL_SLUG', 'yith-woocommerce-color-label-variations' );
}

if ( ! defined( 'YITH_WCCL_SECRET_KEY' ) ) {
	define( 'YITH_WCCL_SECRET_KEY', 'bnmQwc5wUlnX24pgLm8I' );
}

// install plugin
if ( ! function_exists( 'yith_wccl_install' ) ) {
	require_once 'includes/function.yith-wccl-install.php';
}
register_activation_hook( __FILE__, 'yith_wccl_install' );


function yith_wccl_premium_init() {

	load_plugin_textdomain( 'ywcl', false, dirname( plugin_basename( __FILE__ ) ). '/languages/' );

	// Load required classes and functions
	require_once('includes/class.yith-wccl.php');
	require_once('includes/class.yith-wccl-admin.php');
	require_once('includes/class.yith-wccl-frontend.php');

	// Let's start the game!
	YITH_WCCL();
}
add_action( 'yith_wccl_premium_init', 'yith_wccl_premium_init' );


function yith_wccl_premium_install() {

	if ( ! function_exists( 'WC' ) ) {
		add_action( 'admin_notices', 'yith_wccl_premium_install_woocommerce_admin_notice' );
	}
	else {
		do_action( 'yith_wccl_premium_init' );
	}

	// check for update table
	if( function_exists( 'yith_wccl_update_db_check' ) ) {
		yith_wccl_update_db_check();
	}
}
add_action( 'plugins_loaded', 'yith_wccl_premium_install', 11 );