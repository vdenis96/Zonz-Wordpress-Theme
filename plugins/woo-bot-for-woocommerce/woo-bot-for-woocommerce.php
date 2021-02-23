<?php

/**
 * Main plugin file.
 *
 * This file is used to mange public-facing and admin-facing aspects of the plugin.
 *
 * PHP version 5.6+
 *
 * @link              https://wp1.co
 * @since             1.0.0
 * @package           Woo_Bot_For_Woocommerce
 *
 * @wordpress-plugin
 * Plugin Name: Woo Bot for WooCommerce
 * Plugin URI: https://wp1.co/wp/woo-bot
 * Description: Woo Bot is a fully configurable simple chat bot that helps online stores to convert abandoning visitors into customers.
 * Version: 1.0.0
 * Author: WP1
 * Author URI: https://wp1.co
 * Developer: WP1
 * Developer URI: http://wp1.co/wp/
 * Text Domain: woo-bot-for-woocommerce
 * Domain Path: /languages
 *
 * Woo: 6663048:afabf03a00fa1b76c4267d69ae6ddd28
 * WC requires at least: 3.3.0
 * WC tested up to: 4.6.0
 *
 * Copyright: © 2009-2015 WooCommerce.
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! defined( 'WOO_BOT_FOR_WOOCOMMERCE_PLUGIN_DIR' ) ) {
	define( 'WOO_BOT_FOR_WOOCOMMERCE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}
if ( ! defined( 'WOO_BOT_FOR_WOOCOMMERCE_PLUGIN_URL' ) ) {
	define( 'WOO_BOT_FOR_WOOCOMMERCE_PLUGIN_URL', plugins_url() . '/woo-bot-for-woocommerce' );
}
if ( ! defined( 'WOO_BOT_FOR_WOOCOMMERCE_PLUGIN_FILE' ) ) {
	define( 'WOO_BOT_FOR_WOOCOMMERCE_PLUGIN_FILE', __FILE__ );
}
if ( ! defined( 'WOO_BOT_FOR_WOOCOMMERCE_PLUGIN_BASENAME' ) ) {
	define( 'WOO_BOT_FOR_WOOCOMMERCE_PLUGIN_BASENAME', plugin_basename( WOO_BOT_FOR_WOOCOMMERCE_PLUGIN_FILE ) );
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WOO_BOT_FOR_WOOCOMMERCE_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-woo-bot-for-woocommerce-activator.php
 */
function activate_woo_bot_for_woocommerce() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woo-bot-for-woocommerce-activator.php';
	Woo_Bot_For_Woocommerce_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-woo-bot-for-woocommerce-deactivator.php
 */
function deactivate_woo_bot_for_woocommerce() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woo-bot-for-woocommerce-deactivator.php';
	Woo_Bot_For_Woocommerce_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_woo_bot_for_woocommerce' );
register_deactivation_hook( __FILE__, 'deactivate_woo_bot_for_woocommerce' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-woo-bot-for-woocommerce.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_woo_bot_for_woocommerce() {

	$plugin = new Woo_Bot_For_Woocommerce();
	$plugin->run();

}
run_woo_bot_for_woocommerce();
