<?php

/**
 * this is the file for woocommerce functionality
 *
 * this file is only loaded is woocommerce is enabled as a plugin
 */

// security - stop if file isn't accessed via WP
if (!defined('ABSPATH'))
	exit;

/**
 * Class holds all WooCommerce functionality of this plugin
 */
class feedbackcompany_woocommerce
{
	/**
	 * Constructor initializes, overrides or extends WooCommerce functionality
	 */
	function __construct()
	{
		// callback on order status change to register order with Feedback Company if invitations are enabled
		if (get_option('feedbackcompany_invitation_enabled') !== "0")
			add_action('woocommerce_order_status_changed', array($this, 'register_order'), 99, 3);

		// if product reviews are enabled, replace default 'review tab' and 'product rating' with our own
		if (get_option('feedbackcompany_productreviews_enabled') == true)
		{
			// replace the rating template with our own
			add_filter('wc_get_template', array($this, 'get_template'), 10, 5);
			// delete default and insert our own 'review' tab into the product tabs
			add_filter('woocommerce_product_tabs', array($this, 'add_reviews_tab'), 99);
			// output our custom html for product rating
			add_filter('woocommerce_product_get_rating_html', array($this, 'output_rating'));
		}
		// if product reviews widget is not inline in a tab (see above), include the code someplace else
		if (get_option('feedbackcompany_productreviewsextendedwidget_displaytype') != 'inline')
			add_action('woocommerce_after_single_product', array($this, 'output_reviewswidget'), 99, 3);
	}

	/**
	 * Function to override the default WooCommerce rating template
	 *
	 * https://docs.woocommerce.com/wc-apidocs/function-wc_get_template.html
	 */
	function get_template($located, $template_name, $args, $template_path, $default_path)
	{
		if ('single-product/rating.php' == $template_name)
			$located = plugin_dir_path( __FILE__ ) . 'woocommerce_templates/rating.php';

		return $located;
	}

	/**
	 * Function to override the regular reviews tab and add our own
	 *
	 * Our own reviews are only added if productextended widget display is inline
	 *
	 * http://hookr.io/filters/woocommerce_product_tabs/
	 */
	function add_reviews_tab($tabs)
	{
		unset($tabs['reviews']);

		if (get_option('feedbackcompany_productreviewsextendedwidget_displaytype') == 'inline')
		{
			$tab = array(
				'title' => 'Reviews',
				'priority' => 50,
				'callback' => array($this, 'output_reviews_tab')
			);
			$tabs['reviews'] = $tab;
		}

		return $tabs;
	}

	/**
	 * Function for our own custom rating output on product overview pages
	 *
	 * http://hookr.io/filters/woocommerce_product_get_rating_html/
	 */
	function output_rating($rating_html = null, $rating = null)
	{
		global $product;

		// bugfix - apparently Gutenberg can sometimes call a WooCommerce template when $product is null?
		if ($product === null)
			return $rating_html;

		if ($product->get_image_id())
			$product_image_url = wp_get_attachment_image_src($product->get_image_id(), 'woocommerce_thumbnail')[0];
		else
			$product_image_url = '';

		return feedbackcompany_api_wp()->get_widget_productsummary($product->get_id(), $product->get_name(), $product->get_permalink(), $product_image_url);
	}

	/**
	 * Callback function for outputting the reviews tab content
	 */
	function output_reviews_tab()
	{
		$this->output_reviewswidget();
	}

	/**
	 * Function outputs the reviews widget for a specific product
	 *
	 * This function can be called to output inline in a specific tab or
	 * just display the popup/sidebar widget code on 'woocommerce_after_single_product'
	 *
	 * http://hookr.io/actions/woocommerce_after_single_product/
	 */
	function output_reviewswidget()
	{
		global $product;

		if ($product->get_image_id())
			$product_image_url = wp_get_attachment_image_src($product->get_image_id(), 'woocommerce_thumbnail')[0];
		else
			$product_image_url = '';

		echo feedbackcompany_api_wp()->get_widget_productextended($product->get_id(), $product->get_name(), $product->get_permalink(), $product_image_url);
	}

	/**
	 * Function interfaces with the API library to register an order with Feedback Company
	 *
	 * Callback for 'woocommerce_order_status_changed'
	 *
	 * http://hookr.io/actions/woocommerce_order_status_changed/
	 */
	function register_order($order_id, $status_from, $status_to)
	{
		// check if status change is changed to the one configured as 'action' status via WP settings
		if (get_option('feedbackcompany_invitation_orderstatus') != 'wc-'.$status_to)
		{
			// if not, stop now
			return false;
		}

		// get this specific order
		$order = wc_get_order($order_id);

		// check if WordPress Multilanguage plugin is enabled
		if (feedbackcompany_wordpressmultilanguage_enabled())
		{
			// get order language (Depending on the version to access metadata object of order)
			$order_language = '';

			foreach ($order->get_meta_data() as $metadata)
			{
				// newer Wordpress versions
				if ($metadata instanceof WC_Meta_Data)
				{
					$data = $metadata->get_data();
					if ($data['key'] === 'wpml_language')
					{
						$order_language = $data['value'];
					}

				}
				// earlier Wordpress versions
				elseif (property_exists($metadata, 'key') && property_exists($metadata, 'value'))
				{
					if ($metadata->key === 'wpml_language')
					{
						$order_language = $metadata->value;
					}
				}
			}

			// get the configured language on feedback company plugin
			$wordpress_configured_language = get_option('feedbackcompany_wordpressmultilanguage');
			// check if the current active language on WordPress page is the same as the configured languag
			// if the configured language is not on all and the order language is not equal to configured language than do not register order
			if ($wordpress_configured_language !== 'all' && $order_language !== $wordpress_configured_language) 
			{
				return false;
			}
		}

		// build data array for the api call
		$orderdata = array();
		$orderdata['external_id'] = strval($order_id);
		// add customer data
		$orderdata['customer'] = array(
			'email' => trim($order->get_billing_email()),
			'fullname' => trim($order->get_billing_first_name().' '.$order->get_billing_last_name())
		);
		// add products
		$orderdata['products'] = array();
		foreach ($order->get_items() as $orderitem)
		{
			$item = $orderitem->get_product();
			$product = array();
			$product['external_id'] = strval($orderitem->get_product_id());
			if ($item->get_sku())
				$product['sku'] = strval($item->get_sku());
			$product['name'] = $item->get_name();
			$product['url'] = $item->get_permalink();
			if ($item->get_image_id())
				$product['image_url'] = wp_get_attachment_image_src($item->get_image_id(), 'woocommerce_thumbnail')[0];

			$orderdata['products'][] = $product;
		}

		// make the api call
		feedbackcompany_api_wp()->register_order($orderdata);
	}
}

/** create a new instance of this class to start things off */
$feedbackcompany_woocommerce = new feedbackcompany_woocommerce;
