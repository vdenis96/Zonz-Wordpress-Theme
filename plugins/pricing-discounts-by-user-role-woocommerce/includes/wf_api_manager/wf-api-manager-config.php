<?php
$product_name = 'pricesbyuserrole'; // name should match with 'Software Title' configured in server, and it should not contains white space
$product_version = '1.6.9';
$product_slug = 'pricing-discounts-by-user-role-woocommerce/pricing-discounts-by-user-role-woocommerce.php'; //product base_path/file_name
$serve_url = 'https://www.xadapter.com/';
$plugin_settings_url = admin_url( 'admin.php?page=wc-settings&tab=eh_pricing_discount' ); //Contains the Plugin settings link.

//include api manager
include_once ( 'wf_api_manager.php' );
new WF_API_Manager($product_name, $product_version, $product_slug, $serve_url, $plugin_settings_url);
?>
