<?php

include_once(WP_PLUGIN_DIR . '/sforsoftware-sconnect-woocommerce/sconnect/command.php');
include_once(WP_PLUGIN_DIR . '/sforsoftware-sconnect-woocommerce/sconnect/command/interface.php');

class Sforsoftware_Sconnect_Command_Inventory_Update extends Sforsoftware_Sconnect_Command implements Sforsoftware_Sconnect_Command_Interface
{

	private $_postData;
	private $_responseData;

	public function __construct($request, $pagination = null, $parameters = null)
	{
		parent::__construct($request, $pagination, $parameters);
		$this->_postData = json_decode(file_get_contents("php://input"));
	}

	public function process_request()
	{
		if ($this->_postData && property_exists($this->_postData, 'data') && count($this->_postData->data)) {
			foreach ($this->_postData->data as $data) {

				$_product_id = wc_get_product_id_by_sku($data->Artikelcode);
				$_product = wc_get_product($_product_id);

				// Update the stock quantity if the product supports _manage_stock
				if ($_product_id != 0 && $_product->get_manage_stock()) {
					wc_update_product_stock( $_product, $data->Aantal);

					// If the new stock status is more than 0 set the stock status to instock else outofstock
					if($data->Aantal > 0){
						wc_update_product_stock_status($_product_id, 'instock');
					}
					else {
						wc_update_product_stock_status($_product_id, 'outofstock');
					}
				}

				$this->_responseData[] = array(
					'Artikelcode' => trim($data->Artikelcode),
					'Aantal'      => $data->Aantal
				);
			}

			$this->set_response_body(array('data' => $this->_responseData));
			$this->set_response_code(200);

			return;
		}

		$this->set_response_body(array('data' => array()));
		$this->set_response_code(403);
	}
}