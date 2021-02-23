<?php

class Sforsoftware_Sconnect_Woocommerce
{

	protected $loader;
	protected $version;
	protected $plugin_name;
	protected $required_woocommerce_version;

	public function __construct()
	{

		$this->plugin_name = 'SforSoftware Sconnect Woocommerce';
		$this->version = '1.0.26';
		$this->required_woocommerce_version = '3.3.4';

		$this->load_dependencies();
		$this->define_admin_hooks();
		$this->define_sconnect_hooks();
		$this->define_order_status_hooks();
		$this->define_rest_auth_hooks();
	}

	private function load_dependencies()
	{
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/loader.php';
		$this->loader = new Sforsoftware_Sconnect_Woocommerce_Loader();

		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/admin.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'sconnect/controller.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'sconnect/command/products/update.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'sconnect/order-status.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'sconnect/rest-auth.php';
	}

	private function define_admin_hooks()
	{
		$gws_admin = new Sforsoftware_Sconnect_Woocommerce_Admin($this->get_plugin_name(), $this->get_plugin_version(), $this->get_required_woocommerce_version());

		$this->loader->add_action('admin_menu', $gws_admin, 'add_options_page');
		$this->loader->add_action('admin_init', $gws_admin, 'register_settings');
		$this->loader->add_action('admin_notices', $gws_admin, 'settings_admin_notice');
	}

	private function define_sconnect_hooks()
	{
		$gws_controller = new Sforsoftware_Sconnect_Controller();

		$this->loader->add_action('rest_api_init', $gws_controller, 'register_endpoints');
	}

	private function define_order_status_hooks()
	{
		$gws_status = New Sforsoftware_Order_Status();

		$this->loader->add_action('woocommerce_order_status_changed', $gws_status, 'order_status_changed');
		$this->loader->add_action('before_delete_post', $gws_status, 'order_delete_action', 10, 1);
		$this->loader->add_action('wp_trash_post', $gws_status, 'order_delete_action', 10, 1);
		$this->loader->add_action('untrash_post', $gws_status, 'order_untrashed_action');
	}

	private function define_rest_auth_hooks()
	{
		$gws_rest_auth = new Sforsoftware_Sconnect_Woocommerce_Rest_Auth();

		$this->loader->add_filter('rest_authentication_errors', $gws_rest_auth, 'rest_auth_login', 90);
	}

	public function run()
	{
		$this->loader->run();
	}

	public function get_plugin_name()
	{
		return $this->plugin_name;
	}

	public function get_plugin_version()
	{
		return $this->version;
	}

	public function get_required_woocommerce_version()
	{
		return $this->required_woocommerce_version;
	}

	public function get_loader()
	{
		return $this->loader;
	}

}