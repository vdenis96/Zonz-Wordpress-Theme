<?php

include_once( WP_PLUGIN_DIR . '/sforsoftware-sconnect-woocommerce/sconnect/api-request.php' );
include_once( WP_PLUGIN_DIR . '/sforsoftware-sconnect-woocommerce/sconnect/command/products.php' );
include_once( WP_PLUGIN_DIR . '/sforsoftware-sconnect-woocommerce/sconnect/command/orders.php' );
include_once( WP_PLUGIN_DIR . '/sforsoftware-sconnect-woocommerce/sconnect/command/inventory.php' );
include_once( WP_PLUGIN_DIR . '/sforsoftware-sconnect-woocommerce/sconnect/command/connect.php' );
include_once( WP_PLUGIN_DIR . '/sforsoftware-sconnect-woocommerce/sconnect/command/products/update.php' );
include_once( WP_PLUGIN_DIR . '/sforsoftware-sconnect-woocommerce/sconnect/command/inventory/update.php' );

class Sforsoftware_Sconnect_Controller {

    private $_apiRequest;

	/**
	 * Register the endpoints in wordpress
	 */
    public function register_endpoints(){
        register_rest_route( 'sforsoftware', '/sconnect', array( 'methods' => WP_REST_Server::ALLMETHODS, 'callback' => array($this, 'endpoints_action')));
    }

	/**
	 * Direct the command trough the correct model and return the response
	 *
	 * @param WP_REST_Request $request
	 * @return WP_REST_Response
	 */
    public function endpoints_action(WP_REST_Request $request){
        $this->_apiRequest = new Sforsoftware_Sconnect_ApiRequest($request);

        $command                        = $request->get_param('command');
        $pagination                     = array();
        $pagination['start']            = (null !== $request->get_param('start')) ? $request->get_param('start') : 0;
        $pagination['aantal']           = (null !== $request->get_param('aantal')) ? $request->get_param('aantal') : 10;
        $pagination['vanaf_datum_tijd'] = $request->get_param('vanaf_datum_tijd');

        if($this->_apiRequest->validate() && $command){
            $command_model = Sforsoftware_Sconnect_Command::get_command_model($command);
            $command_model = 'Sforsoftware_Sconnect_Command_' . $command_model;

            $command = new $command_model($request, $pagination, null);
            $command->process_request();

            $response = new WP_REST_Response($command->get_response_body(), $command->get_response_code());
            return $response;
        }

        $response = new WP_REST_Response('', 403);
        return $response;
    }
}