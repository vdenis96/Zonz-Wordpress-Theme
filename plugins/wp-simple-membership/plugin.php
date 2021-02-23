<?php 
/**
 * Plugin Name: Wp Simple Membership
 * Description: Simple Wordpress Membership plugin. Front End login, Register, and My profile
 * Author: Allah Noor
 * Author URI: https://www.fiverr.com/brilliantcoder
 * Version: 1.0
 **/
 
  define('BC_PLUGINPATH', plugin_dir_path( __FILE__ ));
  define('BC_PLUGIN_URL', plugins_url('',__FILE__));
  define('BC_PLUGIN_BASENAME', dirname( plugin_basename( __FILE__ ) ));
  add_action( 'wp_enqueue_scripts', 'bc_admin_scripts',10,3);
  add_action( 'init', 'bc_textdomain' );
  function bc_admin_scripts() {
		wp_register_style( 'bc-style', BC_PLUGIN_URL. '/css/style.css' ,'', '',false);
		wp_enqueue_style( 'bc-style' );

  }
  require_once BC_PLUGINPATH."inc/settings.php";
  require_once BC_PLUGINPATH."inc/functions.php";
  require_once BC_PLUGINPATH."inc/membership.php";
  register_activation_hook( __FILE__, 'bc_default_settings');
  $BcMembership = new BcMembership;
  
  
 