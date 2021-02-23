<?php

class Sforsoftware_Sconnect_Woocommerce_Admin {

    private $plugin_name;
    private $version;
    private $required_woocommerce_version;

    public function __construct( $plugin_name, $version, $required_woocommerce_version ){
        $this->plugin_name  = $plugin_name;
        $this->version      = $version;
        $this->required_woocommerce_version = $required_woocommerce_version;
    }

    public function add_options_page(){
        add_options_page( $this->plugin_name, $this->plugin_name, 'manage_options', 'sforsoftware_sconnect_woocommerce', array($this, 'my_plugin_page'));
    }

    public function my_plugin_page(){
        require plugin_dir_path(dirname(__FILE__)).'admin/templates/options-page.php';
    }

    public function register_settings(){
        register_setting('gws-settings', 'gws_enable_ip_check');
        register_setting('gws-settings', 'gws_allowed_ip', array($this, 'sanitize_allowed_ip'));
        register_setting('gws-settings', 'gws_request_counter');
        register_setting('gws-settings', 'gws_security_token');
        register_setting('gws-settings', 'gws_default_description');
        register_setting('gws-settings', 'gws_order_export');
        register_setting('gws-settings', 'gws_override_products');
        register_setting('gws-settings', 'gws_override_name');
        register_setting('gws-settings', 'gws_override_description');
        register_setting('gws-settings', 'gws_import_images');
        register_setting('gws-settings', 'gws_import_categories');
        register_setting('gws-settings', 'gws_enable_debug_mode');
        register_setting('gws-settings', 'gws_tax_high');
        register_setting('gws-settings', 'gws_tax_low');
        register_setting('gws-settings', 'gws_tax_none');
        register_setting('gws-settings', 'gws_enable_rest_api_ip_check');
    }

    public function sanitize_allowed_ip($allowed_ip){
        $output = array();

        foreach($allowed_ip as $key => $value){
            if(!empty($value)){
                $output[] = $value;
            }
        }

        return apply_filters('sanitize_allowed_ip', $output, $allowed_ip);
    }

    public function settings_admin_notice(){
        if(!$this->_settings_configured()){
            $settings_url = admin_url() . 'options-general.php?page=sforsoftware_sconnect_woocommerce';
            ?>
            <div class="error">
                <p><?php printf(__('<strong>SforSoftware Sconnect Woocommerce:</strong> Instellingen niet geconfigureerd, stel alle instellingen correct in. <a href="%s">Naar instellingen</a> ', 'sforsoftware-sconnect-woocommerce'), esc_url($settings_url)); ?></p>
            </div>
            <?php
        }
        if($this->_debug_activated()){
            $settings_url = admin_url() . 'options-general.php?page=sforsoftware_sconnect_woocommerce';
            ?>
            <div class="notice notice-warning">
                <p><?php printf(__('<strong>SforSoftware Sconnect Woocommerce:</strong> Debug mode ingeschakeld vergeet deze niet uit te schakelen. <a href="%s">Naar instellingen</a> ', 'sforsoftware-sconnect-woocommerce'), esc_url($settings_url)); ?></p>
            </div>
            <?php
        }
        if(!$this->_woocommerce_required_version()){
            $plugins_url = admin_url() . 'plugins.php';
            ?>
            <div class="notice notice-warning">
                <p><?php printf(__('<strong>SforSoftware Sconnect Woocommerce:</strong> Woocommerce versie niet up to date. <strong>Update Woocommerce!</strong> <a href="%s">Naar plugins</a>', 'sforsoftware-sconnect-woocommerce'), esc_url($plugins_url)); ?></p>
            </div>
            <?php
        }



		// If get_plugins() isn't available, require it
		if ( ! function_exists( 'get_plugins' ) )
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		// Create the plugins folder and file variables
		$plugin_folder = get_plugins( '/' . 'woocommerce' );
		$plugin_file = 'woocommerce.php';

		// If the plugin version number is set, return it
		if ( isset( $plugin_folder[$plugin_file]['Version'] ) ) {
			return $plugin_folder[$plugin_file]['Version'];

		} else {
			// Otherwise return null
			return NULL;
		}


    }

	/**
     * Function to check if all the settings are configured
     *
	 * @return bool
	 */
    private function _settings_configured(){
        $token                  = get_option('gws_security_token');
        $order_export           = get_option('gws_order_export');
        $tax_high               = get_option('gws_tax_high');
        $tax_low                = get_option('gws_tax_low');
        $tax_none               = get_option('gws_tax_none');
        $settings_configured    = true;

        if(!isset($token) || empty($token)){
            $settings_configured = false;
        }
        if(!isset($order_export) || empty($order_export)){
            $settings_configured = false;
        }
        if(!isset($tax_high) || $tax_high == 'none'){
            $settings_configured = false;
        }
        if(!isset($tax_low) || $tax_low == 'none'){
            $settings_configured = false;
        }
        if(!isset($tax_none) || $tax_none == 'none'){
            $settings_configured = false;
        }

        return $settings_configured;
    }

	/**
     * Function to check if the debug is activated
     *
	 * @return bool
	 */
    private function _debug_activated(){
        if(get_option('gws_enable_debug_mode')){
            return true;
        }
        return false;
    }

	/**
     * Function to check if Woocommerce has the right version
     *
	 * @return bool
	 */
    private function _woocommerce_required_version(){
        if(is_plugin_active('woocommerce/woocommerce.php')){
            global $woocommerce;
            if(version_compare($woocommerce->version, $this->required_woocommerce_version, '>=')){
                return true;
            }
        }

        return false;
    }
}