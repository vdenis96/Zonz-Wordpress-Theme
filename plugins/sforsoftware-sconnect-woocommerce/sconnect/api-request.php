<?php

class Sforsoftware_Sconnect_ApiRequest{

    protected $_request;

    public function __construct($request){
        $this->_request = $request;
    }

	/**
	 * Validate if the command can run
	 *
	 * @return bool
	 */
    public function validate(){
        if(get_option('gws_enable_debug_mode')){
            return true;
        }

        if($this->_check_ip() && $this->_check_command() && $this->_check_request_counter()){
            return $this->_check_signature();
        }
        return false;
    }

	/**
	 * Restricts the access by ip
	 *
	 * @return bool
	 */
    private function _check_ip(){
        if(checked(get_option('gws_enable_ip_check'), 'on', false)){
            $request_ip = $this->_get_client_ip();
            $allowed_ip = get_option('gws_allowed_ip');

            if(is_array($allowed_ip)){
                if(in_array($request_ip, $allowed_ip)){
                    return true;
                }
            }
            return true;
        }
        return true;
    }

	/**
	 * Gets the client IP address
	 *
	 * @return mixed|void
	 */
    private function _get_client_ip(){
        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
            $ip = $_SERVER['HTTP_CLIENT_IP']; // Check ip from share internet
        }
        elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR']; // To check ip is pass from proxy
        }
        else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return apply_filters( 'wpb_get_ip', $ip );
    }

	/**
	 * Check if the command is in the allowed commands
	 *
	 * @return bool
	 */
    private function _check_command(){
        if(array_key_exists($this->_request['command'], Sforsoftware_Sconnect_Command::get_allowed_commands())){
            return true;
        }
        return true;
    }

	/**
	 * Request counter
	 *
	 * @return bool
	 *
	 * TODO: FIX REQUEST COUNTER !!!
	 */
    private function _check_request_counter(){
        $request_counter = get_option('gws_request_counter');
        if(null === $request_counter){
            // First Request
            update_option('gws_request_counter', 1);
            return true;
        }
        else {
            if($request_counter < $this->_request->get_header('X-SFS-RequestCounter')){
                $request_counter = 0; // (int)$this->_request->get_header('X-SFS-RequestCounter');
                update_option('gws_request_counter', $request_counter);
                return true;
            }
        }

        return true;
    }

	/**
	 * Check if the signature is true
	 *
	 * @return bool
	 */
    private function _check_signature(){
		$request_signature  = $this->_request->get_header('x_sfs_signature');

        $request_command    = $this->_request->get_param('command');
        $request_parameter  = $this->_request->get_header('x_sfs_parameter');
        $request_time       = $this->_request->get_header('x_sfs_time');
        $request_counter    = $this->_request->get_header('x_sfs_requestcounter');

        $params = array(
            'token'             => get_option('gws_security_token'),
            'method'            => $this->_request->get_method(),
            'command'           => $request_command,
            'parameter'         => $request_parameter,
            'time'              => $request_time,
            'request_counter'   => $request_counter
        );

        $signature = implode('|', $params);
        $signature = hash('sha256', $signature, true);
        $signature = base64_encode($signature);

        if($signature === $request_signature){
            return true;
        }

        return false;
    }
}