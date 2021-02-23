<?php

include_once(WP_PLUGIN_DIR . '/sforsoftware-sconnect-woocommerce/sconnect/command.php');
include_once(WP_PLUGIN_DIR . '/sforsoftware-sconnect-woocommerce/sconnect/command/interface.php');

class Sforsoftware_Sconnect_Command_Products_Update extends Sforsoftware_Sconnect_Command implements Sforsoftware_Sconnect_Command_Interface
{

	private $_postData;
	private $_responseData;

	public function __construct($request, $pagination = null, $parameters = null)
	{
		parent::__construct($request, $pagination, $parameters);

		require_once(ABSPATH . 'wp-admin/includes/post.php'); // require for post_exists()
		require_once(ABSPATH . 'wp-admin/includes/image.php'); // require for wp_generate_attachment_metadata()

		$this->_postData = json_decode(file_get_contents("php://input"));
	}

	/**
	 * Process the request
	 */
	public function process_request()
	{
		if ($this->_postData && property_exists($this->_postData, 'data') && count($this->_postData->data)) {
			$this->_import_products();

			$this->set_response_body(
				array(
					'data'   => $this->_responseData,
					'aantal' => count($this->_responseData)
				)
			);
			$this->set_response_code(200);

		} else {
			$this->set_response_body(
				array(
					'data'   => array(),
					'aantal' => 0,
				)
			);
			$this->set_response_code(403);
		}
	}

	/**
	 * Import the products
	 */
	private function _import_products()
	{
		foreach ($this->_postData->data as $product_data) {

			$sku = $product_data->Artikelcode;

			/**
			 * Filter: Before Get Product Id By Sku
			 *
			 * used to change the sku value if the sku is not set by the $product_data->Artikelcode
			 *
			 * @attr	$sku
			 */
			if(has_filter('ssw_before_get_product_id_by_sku')){
				$sku = apply_filters(
					'ssw_before_get_product_id_by_sku', array(
						'sku' => $sku,
						'data' => $product_data,
					)
				);
			}

			$product_id = wc_get_product_id_by_sku(trim($sku));

			// If the product doesn't exist add it/if it does exist and override is enabled update the product
			if ($product_id == 0) {
				$product_id = $this->_add_product($product_data);
			} else {
				$this->_update_product($product_id, $product_data);
			}

			// Product is added or updated now complete the process
			$_product = wc_get_product($product_id);
			$_product = $this->_set_post_meta($_product, $product_data);

			// Add the product attributes
			$this->_add_product_attibutes($_product, $product_data);

			// Add the images if the import images is allowed
			if (get_option('gws_import_images')) {
				$_product = $this->_add_images($_product);
			}

			/**
			 * Filter: Before Product Save
			 *
			 * @attr 	$_product
			 * @attr	$product_data
			 */
			if (has_filter('ssw_before_product_save')) {
				$_product = apply_filters(
					'ssw_before_product_save', array(
						'product' => $_product,
						'data'    => $product_data,
					)
				);
			}

			// Update the product attributes
			if (isset($_product)) {
				$_product->save();

				// Update the product post to set the post_modified_date
				$product_post = array('ID' => $_product->get_id());

				if (wp_update_post($product_post) != 0) {
					$this->_responseData[] = array(
						'Artikelcode' => trim($product_data->Artikelcode),
					);
				}
			}
		}
	}

	/**
	 * Add an new product
	 *
	 * @param $product_data
	 * @return int|WP_Error
	 */
	private function _add_product($product_data)
	{
		// Create the post array
		$post_array = array(
			'post_title'   => $product_data->Omschrijving,
			'post_content' => $product_data->Omschrijving,
			'post_status'  => $this->_get_status($product_data),
			'post_type'    => 'product'
		);

		/**
		 * Filter: Before Add Product
		 *
		 */
		if(has_filter('ssw_before_add_product')){
			$post_array = apply_filters(
				'ssw_before_add_product', array(
					'post_array' => $post_array,
					'product_data' => $product_data,
				)
			);
		}

		// Insert the Product and set the terms for simple product
		$product = wp_insert_post($post_array);
		wp_set_object_terms($product, 'simple', 'product_type');

		return $product;
	}

	/**
	 * Update an existing product
	 *
	 * @param $product_id
	 * @param $data
	 * @return int|WP_Error
	 */
	private function _update_product($product_id, $product_data)
	{
		$update_data = array(
			'ID' => $product_id,
		);

		if (get_option('gws_override_name')) {
			$new_slug = sanitize_title($product_data->Omschrijving, $product_id);

			$update_data['post_title'] = $product_data->Omschrijving;
			$update_data['post_name'] = $new_slug;
		}

		if (get_option('gws_override_description')) {
			$update_data['post_content'] = $product_data->Omschrijving;
		}

		/**
		 * Filter: Before Update Product
		 *
		 */
		if(has_filter('ssw_before_update_product')){
			$update_data = apply_filters(
				'ssw_before_update_product', array(
					'post_array' => $update_data,
					'product_data' => $product_data,
					'product_id' => $product_id,
				)
			);
		}

		$product = wp_update_post($update_data);

		return $product;
	}

	/**
	 * Set the post meta for the product
	 *
	 * @param WC_Product $_product
	 * @param $product_data
	 * @return WC_Product
	 * @throws WC_Data_Exception
	 */
	private function _set_post_meta(WC_Product $_product, $product_data)
	{
		/**
		 * Filter: Before Set Post Meta
		 *
		 * @attr	$_product
		 * @attr	$product_data
		 */
		if(has_filter('ssw_before_set_post_meta')){
			$product_data = apply_filters(
				'ssw_before_set_post_meta', array(
					'product' => $_product,
					'data'	  => $product_data,
				)
			);
		}

		// Set the post meta values
		$_product->set_sku(trim($product_data->Artikelcode));
		$_product->set_regular_price($product_data->Verkoopprijs);
		$_product->set_price($product_data->Verkoopprijs);
		$_product->set_tax_class($this->_set_tax_class($product_data->BtwId));
		$_product->set_manage_stock($this->_set_manage_stock($product_data->Voorraadcontrole));
		$_product->set_stock_status($this->_set_stock_status($product_data));
		$_product->set_stock_quantity($product_data->VoorraadWeb);
		$_product->set_backorders('no');
		$_product->set_catalog_visibility('visible');
		$_product->set_downloadable('no');
		$_product->set_virtual('no');

		return $_product;
	}

	/**
	 * Set the manage stock option
	 *
	 * @param $stock
	 * @return string
	 */
	private function _set_manage_stock($stock)
	{
		if ($stock == 1) {
			return 'yes';
		} else {
			return 'no';
		}
	}

	/**
	 * Set the tax class option
	 *
	 * @param $tax_class
	 * @return string
	 */
	private function _set_tax_class($tax_class)
	{
		if ($tax_class == 0 || $tax_class == 3) {
			return get_option('gws_tax_none');
		} elseif ($tax_class == 1) {
			return get_option('gws_tax_low');
		} elseif ($tax_class == 2) {
			return get_option('gws_tax_high');
		}

		return get_option('gws_tax_high');
	}

	/**
	 * Get the Order item Status
	 *
	 * @param $data
	 * @return string
	 */
	private function _get_status($product_data)
	{
		if ($product_data->Nonactief == true) {
			return 'private';
		} else {
			if ($product_data->web == 'Ja') {
				return 'publish';
			} else {
				return 'private';
			}
		}
	}

	/**
	 * Set the stock status
	 *
	 * @param $data
	 * @return string
	 */
	private function _set_stock_status($data)
	{
		if ($data->Voorraadcontrole == 0) {
			return 'instock';
		} elseif ($data->Voorraadcontrole == 1 && $data->VoorraadWeb > 0) {
			return 'instock';
		}

		return 'outofstock';
	}

	/**
	 * Add Extra attributes here.
	 * The Attributes are saved in the post meta so we can use them as taxonomies
	 *
	 * @param $_product
	 * @param $data
	 * @return bool|int
	 */
	private function _add_product_attibutes($_product, $data){
		// Add the default attributes here if needed
		$attributes = array();

		/**
		 * Filter: After Product Update Get Attributes
		 *
		 * @attr 	$product_id
		 * @attr	$product_data
		 */
		if (has_filter('ssw_after_product_add_attributes')) {
			$attributes = apply_filters('ssw_after_product_add_attributes', array('attributes' => $attributes, 'product' => $_product, 'data' => $data,));
		}

		return update_post_meta($_product->get_id(), '_product_attributes', $attributes);
	}

	/**
	 * Add The images for each product
	 *
	 * @param WC_Product $_product
	 * @return WC_Product
	 */
	private function _add_images(WC_Product $_product)
	{
		$product_image = $_product->get_image_id();
		$product_gallery = $_product->get_gallery_image_ids();

		if (!empty($product_image)) {
			wp_delete_attachment($product_image, false);
		}
		if (count($product_gallery)) {
			foreach ($product_gallery as $gallery_image) {
				wp_delete_attachment($gallery_image, false);
			}
		}

		// Add The Images
		$gallery_ids = array();
		$x = 0;

		$wp_uploads_dir = wp_upload_dir();
		$import_dir = $wp_uploads_dir['basedir'] . '/import/';

		chdir($import_dir);
		$files = glob(trim($_product->get_sku()) . '*');

		// Sort the files
		if (is_array($files) && count($files) > 0) {
			foreach ($files as $key => $file) {
				$file = explode('.', $file);
				if (is_array($file) && count($file) == 2) {
					if ($file[0] == trim($_product->get_sku())) {
						unset($files[$key]);

						array_unshift($files, implode('.', $file));
						break;
					}
				}
			}
		}

		// Foreach image that matches SKU add as product image
		foreach ($files as $file) {
			$filename = basename($file);
			$upload_file = wp_upload_bits($filename, null, file_get_contents($file));

			if (!$upload_file['error']) {
				$wp_file_type = wp_check_filetype($filename, null);
				$attachment = array(
					'post_mime_type' => $wp_file_type['type'],
					'post_title'     => preg_replace('/\.[^.]+$/', '', $filename),
					'post_content'   => '',
					'post_status'    => 'inherit'
				);

				$attachment_id = wp_insert_attachment($attachment, $upload_file['file']);

				if (!is_wp_error($attachment_id)) {
					require_once(ABSPATH . "wp-admin" . '/includes/image.php');
					$attachment_data = wp_generate_attachment_metadata($attachment_id, $upload_file['file']);
					wp_update_attachment_metadata($attachment_id, $attachment_data);

					if ($x === 0) {
						// Add the First Image as post thumbnail
						set_post_thumbnail($_product->get_id(), $attachment_id);
					} else {
						$gallery_ids[] = $attachment_id;
					}
				}
			}

			$x++;
		}

		// Add the images to the gallery
		if (is_array($gallery_ids) && count($gallery_ids) > 0) {
			$_product->set_gallery_image_ids($gallery_ids);
		}

		return $_product;
	}
}