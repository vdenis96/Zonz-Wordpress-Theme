<?php

include_once( WP_PLUGIN_DIR . '/sforsoftware-sconnect-woocommerce/sconnect/command.php' );
include_once( WP_PLUGIN_DIR . '/sforsoftware-sconnect-woocommerce/sconnect/command/interface.php' );
include_once( WP_PLUGIN_DIR . '/sforsoftware-sconnect-woocommerce/sconnect/version.php' );

class Sforsoftware_Sconnect_Command_Connect extends Sforsoftware_Sconnect_Command implements Sforsoftware_Sconnect_Command_Interface {

    public function __construct($request, $pagination = null, $parameters = null){
        parent::__construct($request, $pagination, $parameters);
    }

    public function process_request(){
        $_response = array();
        $_response['api_informatie'] = array(
            'naam' => 'SConnect.API.Magento',
            'omschrijving' => 'Order en voorraad plugin',
            'bedrijf' => 'SforSoftware',
            'copyright' => 'Copyright (C) 2017 - SforSoftware',
            'version' => Sforsoftware_Sconnect_Model_Version::VERSION
        );
        $_response['api_configuratie'] = array(
            'methoden' => implode('|', array_keys(Sforsoftware_Sconnect_Command::get_allowed_commands())),
            'voorraad_bijhouden' => $this->_get_stock_manage(),
            'voorraad_decimalen' => 0
        );

        $this->set_response_body($_response);
        $this->set_response_code(200);

        return $this;
    }

    private function _get_stock_manage(){
        if(get_option('woocommerce_manage_stock') == 'yes'){
            return 1;
        }
        return 0;
    }
}