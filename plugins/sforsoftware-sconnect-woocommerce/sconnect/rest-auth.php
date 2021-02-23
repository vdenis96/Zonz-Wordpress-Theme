<?php

class Sforsoftware_Sconnect_Woocommerce_Rest_Auth {

	/**
	 * REST AUTHORIZATION BYPASS
	 *
	 * @param $result
	 * @return string
	 */
	public function rest_auth_login($result){
		if(isset($_GET['command'])){
			if($this->_is_allowed_route() && $this->_is_allowed_ip()){
				$result = 'logged_in';
			}
		}

		return $result;
	}

	/**
	 * Check if the REST route is a Sconnect route
	 *
	 * @return bool
	 */
	private function _is_allowed_route(){

		if(isset($_GET['command'])){
			if(array_key_exists($_GET['command'], Sforsoftware_Sconnect_Command::get_allowed_commands())){
				return true;
			}
		}

		return false;
	}

	/**
	 * Checks if the users IP is a valid IP
	 *
	 * @return bool
	 */
	private function _is_allowed_ip(){
		if(checked(get_option('gws_enable_rest_api_ip_check'), 'on', false)){
			$request_ip = $this->_get_client_ip();
			$allowed_ip = get_option('gws_allowed_ip');

			if(is_array($allowed_ip)){
				if(in_array($request_ip, $allowed_ip)){
					return true;
				}
			}
		}
		return false;
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
}