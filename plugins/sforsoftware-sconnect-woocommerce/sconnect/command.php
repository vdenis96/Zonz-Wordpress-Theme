<?php

class Sforsoftware_Sconnect_Command
{
	protected $_pagination;
	protected $_parameters;
	protected $response_code;
	protected $response_body;

	public function __construct($request, $pagination = null, $parameters = null)
	{
		$this->request = $request;
		$this->_pagination = $pagination;
		$this->_parameters = $parameters;
	}

	/**
	 * All the allowed commands
	 *
	 * @return array
	 */
	public static function get_allowed_commands()
	{
		$_allowed_commands = array(
			'koppeling_maken'     => 'Connect',
			'orders'              => 'Orders',
			'voorraad'            => 'Inventory',
			'voorraad_bijwerken'  => 'Inventory_Update',
			'artikelen'           => 'Products',
			'artikelen_bijwerken' => 'Products_Update',
		);

		return $_allowed_commands;
	}

	/**
	 * Get the command model
	 *
	 * @param $command
	 * @return mixed
	 */
	public static function get_command_model($command)
	{
		$_commands = self::get_allowed_commands();

		return $_commands[$command];
	}

	/**
	 * Set the response code
	 *
	 * @param $response_code
	 */
	public function set_response_code($response_code)
	{
		$this->response_code = $response_code;
	}

	/**
	 * Get the response code
	 *
	 * @return mixed
	 */
	public function get_response_code()
	{
		return $this->response_code;
	}

	/**
	 * Set the response body
	 *
	 * @param $response_body
	 */
	public function set_response_body($response_body)
	{
		$this->response_body = $response_body;
	}

	/**
	 * Get the Response body
	 *
	 * @return mixed
	 */
	public function get_response_body()
	{
		return $this->response_body;
	}

	/**
	 * Get the start page from the params
	 *
	 * @return mixed
	 */
	public function get_current_page()
	{
		if ($this->_pagination['start'] == 0) {
			$this->_pagination['start'] = 1;
		}

		return $this->_pagination['start'];
	}

	/**
	 * Get the page size from the params
	 *
	 * @return mixed
	 */
	public function get_page_size()
	{
		return $this->_pagination['aantal'];
	}
}