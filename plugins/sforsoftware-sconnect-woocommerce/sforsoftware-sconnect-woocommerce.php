<?php
/*
Plugin Name:    Sforsoftware Sconnect Woocommerce
Description:    Woocommerce/Sconnect koppeling
Author:         SforSoftware
Author URI:     https://www.sforsoftware.nl/
Text Domain:    sforsoftware-sconnect-woocommerce
Domain Path:    /i18n/languages/
Version:        1.0.26

License:        GNU General Public License v2.0
License URI:    http://www.gnu.org/licenses/gpl-2.0.html
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

function activate_sforsoftware_sconnect_woocommerce(){
    require_once plugin_dir_path(__FILE__) . 'includes/activator.php';
    Sforsoftware_Sconnect_Woocommerce_Activator::activate();
    Sforsoftware_Sconnect_Woocommerce_Activator::create_db_table();
}
register_activation_hook(__FILE__, 'activate_sforsoftware_sconnect_woocommerce');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/main.php';

function run_sforsoftware_sconnect_woocommerce() {
    /**
     * Check if WooCommerce is active
     **/
    if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
        $plugin = new Sforsoftware_Sconnect_Woocommerce();
        $plugin->run();
    }
}
run_sforsoftware_sconnect_woocommerce();
