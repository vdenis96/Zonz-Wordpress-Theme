<?php

include_once(WP_PLUGIN_DIR . '/sforsoftware-sconnect-woocommerce/sconnect/command.php');
include_once(WP_PLUGIN_DIR . '/sforsoftware-sconnect-woocommerce/sconnect/command/interface.php');

class Sforsoftware_Sconnect_Command_Orders extends Sforsoftware_Sconnect_Command implements Sforsoftware_Sconnect_Command_Interface
{

	private $_ordersCollection;
	private $_totalExportedOrders;
	private $_orders;
	private $_products;

	public function __construct($request, $pagination = null, $parameters = null)
	{
		parent::__construct($request, $pagination, $parameters);

		$form_date = $pagination['vanaf_datum_tijd'];
		$limit_start = $pagination['aantal'] * $pagination['start'];
		$limit_end = $limit_start + $pagination['aantal'];

		global $wpdb;

		$orders_table = $wpdb->prefix . 'sforsoftware_sconnect_export_order';

		$orders_sql = $wpdb->prepare(
			"SELECT `order_id` FROM `$orders_table` WHERE `created_at` >= %d ORDER BY `created_at` ASC LIMIT %d, %d", array(
				$form_date,
				$limit_start,
				$pagination['aantal'],
			)
		);
		$this->_ordersCollection = $wpdb->get_results($orders_sql);

		$order_export_sql = $wpdb->prepare("SELECT `order_id` FROM `$orders_table` WHERE `created_at` >= %d", array($form_date));
		$this->_totalExportedOrders = $wpdb->get_results($order_export_sql);
	}

	/**
	 * Process Request
	 *
	 * @return $this
	 */
	public function process_request()
	{
		if (count($this->_ordersCollection) == 0) {
			$this->set_response_body(
				array(
					'aantal' => 0,
					'data'   => array(),
				)
			);
			$this->set_response_code(200);

			return $this;
		} else {
			$this->_process_orders();
			$this->set_response_body(
				array(
					'aantal' => count($this->_totalExportedOrders),
					'data'   => $this->_orders,
				)
			);
			$this->set_response_code(200);
		}
	}

	/**
	 * Process the orders
	 */
	private function _process_orders()
	{
		if (count($this->_ordersCollection) > 0) {
			foreach ($this->_ordersCollection as $order_id) {

				$order_id = $order_id->order_id;
				$order = wc_get_order($order_id);
				$user = $order->get_user();

				$data = array(
					'Betalingskenmerk'   => $order->get_order_number(),
					'Sjabloon'           => '',
					'Datum'              => date('Y-m-d', strtotime($order->get_date_created())),
					'Kortingspercentage' => $order->get_discount_total(),
					'Omschrijving'       => get_option('gws_default_description'),
					'Ordermemo'          => $this->_get_order_memo($order_id),
					'VerkoperLoginnaam'  => ($user->user_login == '') ? '' : $user->user_login,
					'Kostenplaatsnummer' => '',
					'SoortPrijzen'       => $this->_get_include_tax(),
					'Klantcode'          => $order->get_billing_email(),
					'Klant'              => array(
						'VerzendAdres'    => array(
							'Naam'           => $order->get_shipping_company(),
							'Contactpersoon' => $order->get_formatted_shipping_full_name(),
							'Straat'         => $order->get_shipping_address_1() . ' ' . $order->get_shipping_address_2(),
							// $order->get_formatted_shipping_address();
							'Huisnummer'     => '',
							'Postcode'       => $order->get_shipping_postcode(),
							'Plaats'         => $order->get_shipping_city(),
							'Landcode'       => $order->get_shipping_country(),
						),
						'FactuurAdres'    => array(
							'Naam'           => $order->get_billing_company(),
							'Contactpersoon' => $order->get_formatted_billing_full_name(),
							'Straat'         => $order->get_billing_address_1() . ' ' . $order->get_billing_address_2(),
							// $order->get_formatted_billing_address();
							'Huisnummer'     => '',
							'Postcode'       => $order->get_billing_postcode(),
							'Plaats'         => $order->get_billing_city(),
							'Landcode'       => $order->get_billing_country(),
						),
						'Telefoon'        => $order->get_billing_phone(),
						'MobieleTelefoon' => '',
						'Fax'             => '',
						'Emailadres'      => $order->get_billing_email(),
						'Website'         => '',
						'Memo'            => '',
						'BtwNummer'       => '',
						'KvkNummer'       => '',
					),
					'Regels'             => $this->_process_order_products($order),
				);

				// Filter: Order after hook
				// ========================
				if (has_filter('ssw_after_order')) {
					$data = apply_filters(
						'ssw_after_order', array(
							'order_id' => $order_id,
							'data'     => $data,
						)
					);
				}

				// Group the order data and set the exported timestamp at in the db
				$this->_orders[] = $data;
				$this->_set_exported_at($order_id);
			}
		}
	}

	/**
	 * Process the products on the order
	 *
	 * @param $order
	 *
	 * @return mixed
	 */
	private function _process_order_products($order)
	{
		$items = $order->get_items();
		$this->_products = array();

		foreach ($items as $item_id => $item) {

			$product = $item->get_product();

			if($product){
				$_product = array(
					'Type'              => 'Artikel',
					'Artikelcode'       => $this->_set_sku($product->get_sku()),
					'Artikel'           => array(
						'Omschrijving'          => $product->get_name(),
						'SoortPrijzen'          => $this->_get_include_tax(),
						'Verkoopprijs'          => (float)$product->get_regular_price(),
						'Inkoopprijs'           => '',
						'MaxKortingspercentage' => '',
						'Omzetgroepnummer'      => '',
						'BtwSoort'              => $this->_get_tax_class($product->get_tax_class()),
						'BtwPercentage'         => '',
						'Kortingsgroepnummer'   => '',
						'Eenheid'               => '',
						'Leveranciercode'       => '',
						'Voorraadcontrole'      => $this->_get_product_manage_stock($product),
						'MinimumVoorraad'       => '',
						'GewensteVoorraad'      => '',
					),
					'Omschrijving'      => $product->get_name(),
					'Opties'            => $this->_get_variation_options($product),
					'Aantal'            => $item->get_quantity(),
					'Verkoopprijs'      => (float)$this->_get_totals($item),
					'BtwBedrag'         => (float)$this->_get_total_tax($item), //float)$item->get_total_tax(),
					'KortingPercentage' => 0,
				);

				// Filter: Product after hook
				// ==========================
				if (has_filter('ssw_after_product')) {
					$_product = apply_filters(
						'ssw_after_product', array(
							'order'   => $order,
							'item'    => $item,
							'item_id' => $item_id,
							'product' => $_product,
						)
					);
				}

				$this->_products[] = $_product;
			}
		}

		$this->_add_shipping_costs($order);
		//		$this->_add_discount($order);
		$this->_add_grand_total($order);

		return $this->_products;
	}


	/**
	 * Get the order item stock status if the order item is variable item and the manage stock is true it returns parent
	 * if the response is parent return a true to sconnect
	 *
	 * @param $product
	 *
	 * @return bool
	 */
	private function _get_product_manage_stock($product)
	{
		if ($product->get_manage_stock() || $product->get_manage_stock() === 'parent') {
			return true;
		}

		return false;
	}

	/**
	 * Add the discount array
	 *
	 * @param $order
	 *
	 * @return $this
	 */
	private function _add_discount($order)
	{
		if ($order->get_discount_total()) {
			$this->_products[] = array(
				'Type'              => 'Kortingsbedrag',
				'Verkoopprijs'      => $order->get_discount_total(),
				'BtwBedrag'         => $order->get_discount_tax(),
				'KortingPercentage' => 0,
			);

			return $this;
		}
	}

	/**
	 * Add Grand Total
	 *
	 * @param $order
	 *
	 * @return $this
	 */
	private function _add_grand_total($order)
	{
		$this->_products[] = array(
			'Type'              => 'Totaal',
			'Verkoopprijs'      => $order->get_total(),
			'BtwBedrag'         => $order->get_total_tax(),
			'KortingPercentage' => 0,
		);

		return $this;
	}

	/**
	 * Add the Sipping Costs
	 *
	 * @param $order
	 *
	 * @return $this
	 */
	private function _add_shipping_costs($order)
	{
		$this->_products[] = array(
			'Type'              => 'Verzendkosten',
			'Artikelcode'       => '',
			'Artikel'           => array(
				'Omschrijving' => '',
				'Soortprijzen' => $this->_get_include_tax(),
				// TODO: CHECK $order->get_prices_include_tax()
				'Verkoopprijs' => $order->get_shipping_total(),
				'BtwSoort'     => $this->_get_tax_class(get_option('woocommerce_shipping_tax_class')),
			),
			'Omschrijving'      => '',
			// TODO: IF HAS SHIPPING DESCRIPTION ADD
			'Aantal'            => 1,
			'Verkoopprijs'      => $this->calc_shipping_totals($order),
			'BtwBedrag'         => $order->get_shipping_tax(),
			'KortingPercentage' => 0,
		);

		return $this;
	}

	/**
	 * Calc the shipping costs with tax
	 *
	 * @param $order
	 *
	 * @return mixed
	 */
	private function calc_shipping_totals($order)
	{
		$include_tax = get_option('woocommerce_prices_include_tax');

		if ($include_tax == 'yes') {
			return $order->get_shipping_total() + $order->get_shipping_tax();
		}

		return $order->get_shipping_total();
	}

	/**
	 * Get the totals for a product
	 *
	 * @param $item
	 *
	 * @return float|int
	 */
	private function _get_totals($item)
	{
		$include_tax = get_option('woocommerce_prices_include_tax');

		if ($include_tax == 'yes') {
			return ($item->get_total() + $item->get_total_tax()) / $item->get_quantity();
		}

		return $item->get_total() / $item->get_quantity();
	}

	/**
	 * Get the tax totals for a product.
	 *
	 * @param $item
	 *
	 * @return float|int
	 */
	private function _get_total_tax($item){
		return $item->get_total_tax() / $item->get_quantity();
	}

	/**
	 * Get the tax class to send to SConnect
	 *
	 * @param $tax_class
	 *
	 * @return string
	 */
	private function _get_tax_class($tax_class)
	{
		if ($tax_class == get_option('gws_tax_high')) {
			return 'Hoog';
		}
		if ($tax_class == get_option('gws_tax_low')) {
			return 'Laag';
		}
		if ($tax_class == get_option('gws_tax_none')) {
			return 'Geen';
		}
	}

	/**
	 * Return the include or exclude tax settings.
	 *
	 * @return string
	 */
	private function _get_include_tax()
	{
		$include_tax = get_option('woocommerce_prices_include_tax');

		if ($include_tax == 'yes') {
			return 'InclusiefBtw';
		}

		return 'ExclusiefBtw';
	}

	/**
	 * Get the variation options
	 *
	 * @param $product
	 *
	 * @return array
	 */
	private function _get_variation_options($product)
	{
		if ($product->get_type() != 'variation') {
			return null;
		}

		$options = $product->get_variation_attributes();
		$options_data = null;

		if (count($options)) {
			$options_data = array();
			foreach ($options as $key => $value) {
				$key = str_replace('attribute_', '', $key);

				$options_data[] = array(
					'label'  => wc_attribute_label($key),
					'waarde' => $value,
				);
			}
		}

		return $options_data;
	}

	/**
	 * Set the exported_at timestamp in the database
	 *
	 * @param $order_id
	 */
	private function _set_exported_at($order_id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . 'sforsoftware_sconnect_export_order';
		$order_exists = $wpdb->get_var($wpdb->prepare("SELECT order_id FROM " . $table_name . " WHERE order_id = %d", $order_id));

		if (!is_null($order_exists)) {
			$row_data = array(
				'exported_at' => current_time('timestamp'),
			);
			$wpdb->update($table_name, $row_data, array('order_id' => $order_id), array('%s'), array('%s'));
		}
	}

	/**
	 * Set the Sku for the product in order. If no Sku is found return fallback
	 *
	 * @param $sku
	 *
	 * @return string
	 */
	private function _set_sku($sku)
	{
		if (isset($sku) && !empty($sku)) {
			return $sku;
		} else {
			return 'NIET GEVONDEN';
		}
	}

	/**
	 * Return the order memo
	 *
	 * @param $order_id
	 * @return string
	 */
	private function _get_order_memo($order_id){
		if($order_memo = get_post_field('post_excerpt', $order_id)){
			return $order_memo;
		}

		return '';
	}
}