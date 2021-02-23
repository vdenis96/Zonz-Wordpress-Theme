<?php

class Sforsoftware_Order_Status
{

	public function __construct()
	{
		global $wpdb;

		$this->wpdb = $wpdb;
		$this->table_name = $wpdb->prefix . 'sforsoftware_sconnect_export_order';
	}

	/**
	 * Hooks in to the Woocommerce order when an order status changes
	 *
	 * @param $order_id
	 */
	public function order_status_changed($order_id)
	{
		$order_exports = get_option('gws_order_export');
		$order = wc_get_order($order_id);

		// If the order status is cancelled remove the order from the export table
		if($order->get_status() == 'cancelled'){
			$order_exists = $this->wpdb->get_var($this->wpdb->prepare("SELECT order_id FROM " . $this->table_name . " WHERE order_id = %d", $order_id));

			if (!is_null($order_exists)) {
				$this->wpdb->delete($this->table_name, array('order_id' => $order_id), array('%d'));
			}
		}
		// If the order is in the order_export array add it to the sconnect export table.
		else {
			if (in_array('wc-' . $order->get_status(), $order_exports)) {
				$row_data = array(
					'order_id'   => $order_id,
					'created_at' => time()
				);

				$this->insert_new_order_row($order_id, $row_data, array('%d', '%s'));
			}
		}
	}

	/**
	 * Remove the order from the export table if the order gets deleted or thrashed
	 *
	 * @param $order_id
	 */
	public function order_delete_action($order_id)
	{
		global $post_type;

		if ($post_type !== 'shop_order') {
			return;
		}

		$order_exists = $this->wpdb->get_var($this->wpdb->prepare("SELECT order_id FROM " . $this->table_name . " WHERE order_id = %d", $order_id));

		if (!is_null($order_exists)) {
			$this->wpdb->delete($this->table_name, array('order_id' => $order_id), array('%d'));
		}
	}

	/**
	 * If the order get un trashed and the order status is in te $order_exports re add the order
	 *
	 * @param $order_id
	 */
	public function order_untrashed_action($order_id)
	{
		$order_exports = get_option('gws_order_export');
		$order = wc_get_order($order_id);
		$order_status = get_post_meta($order_id, '_wp_trash_meta_status', true);

		if (in_array($order_status, $order_exports)) {
			$row_data = array(
				'order_id'   => $order_id,
				'created_at' => time()
			);

			$this->insert_new_order_row($order_id, $row_data, array('%d', '%s'));
		}
	}

	/**
	 * Inserts the new row in the export table
	 *
	 * @param $order_id
	 * @param $row_data
	 * @param $row_format
	 */
	private function insert_new_order_row($order_id, $row_data, $row_format)
	{
		$order_exists = $this->wpdb->get_var($this->wpdb->prepare("SELECT order_id FROM " . $this->table_name . " WHERE order_id = %d", $order_id));

		if (is_null($order_exists)) {
			$this->wpdb->insert($this->table_name, $row_data, $row_format);
		}
	}

}