<?php
/*
Plugin Name: Prices by User Role for WooCommerce
Plugin URI: https://www.xadapter.com/product/prices-by-user-role-for-woocommerce/
Description:  Hide add to cart for guest, specific user. Hide price for guest, specific user for all simple product and specific single product. Create user role specific product price. Enforce markup/discount on price for selected user roles.
Version: 1.6.9
Author: ExtensionHawk
Author URI: https://www.xadapter.com/vendor/extensionhawk/

*/

// to check wether accessed directly
if (!defined('ABSPATH')) {
	exit;
}

// for Required functions
if (!function_exists('eh_is_woocommerce_active')) {
	require_once ('eh-includes/eh-functions.php');
}

// to check woocommerce is active
if (!(eh_is_woocommerce_active())) {
	return;
}


function eh_pricing_pre_activation_check(){
	//check if basic version is there
	if ( is_plugin_active('prices-by-user-role/pricing-discounts-by-user-role-woocommerce.php')) {
        deactivate_plugins( basename( __FILE__ ) );
		wp_die( __("Oops! You tried installing the premium version without deactivating and deleting the basic version. Kindly deactivate and delete Prices by User Role for WooCommerce (BASIC) and then try again", "wf-woocommerce-packing-list" ), "", array('back_link' => 1 ));
	}
}

register_activation_hook( __FILE__, 'eh_pricing_pre_activation_check' );

if(!class_exists('Pricing_discounts_By_User_Role_WooCommerce')){
	class Pricing_discounts_By_User_Role_WooCommerce {
		
		// initializing the class
		public function __construct() {
			add_filter('plugin_action_links_' . plugin_basename(__FILE__) , array( $this,'eh_pricing_discount_action_links')); //to add settings, doc, etc options to plugins base
			add_action('init', array( $this,'eh_pricing_discount_admin_menu')); //to add pricing discount settings options on woocommerce shop
			add_action('admin_menu', array(	$this,'eh_pricing_discount_admin_menu_option')); //to add pricing discount settings menu to main menu of woocommerce
		}
		
		// function to add settings link to plugin view
		public function eh_pricing_discount_action_links($links) {
			$plugin_links = array(
				'<a href="' . admin_url( 'admin.php?page=wc-settings&tab=eh_pricing_discount' ) . '">' . __( 'Settings', 'eh-woocommerce-pricing-discount' ) . '</a>',
				'<a href="https://www.xadapter.com/category/product/prices-by-user-role-for-woocommerce/" target="_blank">' . __('Documentation', 'eh-woocommerce-pricing-discount') . '</a>',
                '<a href="https://www.xadapter.com/online-support/" target="_blank">' . __('Support', 'eh-woocommerce-pricing-discount') . '</a>'
			);
			return array_merge($plugin_links, $links);
		}
		
		// function to add menu in woocommerce
		public function eh_pricing_discount_admin_menu() 
		{
			include_once ( 'includes/wf_api_manager/wf-api-manager-config.php' );
			require_once('includes/class-eh-price-discount-admin.php');
			require_once('includes/class-eh-price-discount-settings.php');
		}
		
		public function eh_pricing_discount_admin_menu_option() {
			global $pricing_discount_settings_page;
			$pricing_discount_settings_page = add_submenu_page('woocommerce', __('Pricing & Discount', 'eh-woocommerce-pricing-discount') , __('Pricing & Discount', 'eh-woocommerce-pricing-discount') , 'manage_woocommerce', 'admin.php?page=wc-settings&tab=eh_pricing_discount');
		}
	}

	new Pricing_discounts_By_User_Role_WooCommerce();
}
