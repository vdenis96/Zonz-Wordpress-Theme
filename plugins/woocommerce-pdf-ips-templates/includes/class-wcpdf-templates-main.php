<?php
use WPO\WC\PDF_Invoices\Compatibility\WC_Core as WCX;
use WPO\WC\PDF_Invoices\Compatibility\Order as WCX_Order;
use WPO\WC\PDF_Invoices\Compatibility\Product as WCX_Product;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'WooCommerce_PDF_IPS_Templates_Main' ) ) {

	class WooCommerce_PDF_IPS_Templates_Main {
		public function __construct() {
			// Add premium templates to settings page listing
			add_filter( 'wpo_wcpdf_template_paths', array( $this, 'add_templates' ), 1, 1 );

			// Load custom styles from settings
			add_action( 'wpo_wcpdf_custom_styles', array( $this, 'custom_template_styles' ) );

			// hook custom blocks to template actions
			add_action( 'wpo_wcpdf_before_document', array( $this, 'custom_blocks_data' ), 10, 2 );
			add_action( 'wpo_wcpdf_after_document_label', array( $this, 'custom_blocks_data' ), 10, 2 );
			add_action( 'wpo_wcpdf_before_billing_address', array( $this, 'custom_blocks_data' ), 10, 2 );
			add_action( 'wpo_wcpdf_after_billing_address', array( $this, 'custom_blocks_data' ), 10, 2 );
			add_action( 'wpo_wcpdf_before_shipping_address', array( $this, 'custom_blocks_data' ), 10, 2 );
			add_action( 'wpo_wcpdf_after_shipping_address', array( $this, 'custom_blocks_data' ), 10, 2 );
			add_action( 'wpo_wcpdf_before_order_data', array( $this, 'custom_blocks_data' ), 10, 2 );
			add_action( 'wpo_wcpdf_after_order_data', array( $this, 'custom_blocks_data' ), 10, 2 );
			add_action( 'wpo_wcpdf_before_customer_notes', array( $this, 'custom_blocks_data' ), 10, 2 );
			add_action( 'wpo_wcpdf_after_customer_notes', array( $this, 'custom_blocks_data' ), 10, 2 );
			add_action( 'wpo_wcpdf_before_order_details', array( $this, 'custom_blocks_data' ), 10, 2 );
			add_action( 'wpo_wcpdf_after_order_details', array( $this, 'custom_blocks_data' ), 10, 2 );
			add_action( 'wpo_wcpdf_after_document', array( $this, 'custom_blocks_data' ), 10, 2 );

			// make replacements in template settings fields
			add_action( 'wpo_wcpdf_footer', array( $this, 'settings_fields_replacements' ), 999, 2 );
			add_action( 'wpo_wcpdf_extra_1', array( $this, 'settings_fields_replacements' ), 999, 2 );
			add_action( 'wpo_wcpdf_extra_2', array( $this, 'settings_fields_replacements' ), 999, 2 );
			add_action( 'wpo_wcpdf_extra_3', array( $this, 'settings_fields_replacements' ), 999, 2 );
			add_action( 'wpo_wcpdf_shop_name', array( $this, 'settings_fields_replacements' ), 999, 2 );
			add_action( 'wpo_wcpdf_shop_address', array( $this, 'settings_fields_replacements' ), 999, 2 );

			// store regular price in item meta
			add_action( 'woocommerce_checkout_order_processed', array( $this, 'save_regular_item_price' ), 10, 2 );
			add_filter( 'woocommerce_hidden_order_itemmeta', array( $this, 'hide_regular_price_itemmeta' ) );
		}

		/**
		 * Add premium templates to settings page listing
		 */
		public function add_templates( $template_paths ) {
			$template_paths['premium_plugin'] = WPO_WCPDF_Templates()->plugin_path() . '/templates/';
			return $template_paths;
		}

		/**
		 * Load custom styles from settings
		 */
		public function custom_template_styles ( $template_type ) {
			$editor_settings = get_option('wpo_wcpdf_editor_settings');
			if (isset($editor_settings['custom_styles'])) {
				echo $editor_settings['custom_styles'];
			}
		}

		public function get_totals_table_data ( $total_settings, $document ) {
			$totals_table_data = array();
			foreach ($total_settings as $total_key => $total_setting) {
				// reset possibly absent vars
				$method = $percent = $base = $show_unit = $only_discounted = $label = $single_total = NULL;
				// extract vars
				extract($total_setting);

				// remove label if empty!
				if( empty($total_setting['label']) ) {
					unset($total_setting['label']);
				} elseif ( !in_array( $type, array( 'fees' ) ) ) {
					$label = $total_setting['label'] = __( $total_setting['label'], 'woocommerce-pdf-invoices-packing-slips' ); // not proper gettext, but it makes it possible to reuse po translations!
				}

				switch ($type) {
					case 'subtotal':
						// $tax, $discount, $only_discounted
						$order_discount = $document->get_order_discount( 'total', 'incl' );
						if ( !$order_discount && isset($only_discounted) ) {
							continue;
						}
						switch ($discount) {
							case 'before':

								$totals_table_data[$total_key] = (array) $total_setting + $document->get_order_subtotal( $tax );
								break;

							case 'after':
								$subtotal_value = 0;
								$items = $document->order->get_items();
								if( sizeof( $items ) > 0 ) {
									foreach( $items as $item ) {
										$subtotal_value += $item['line_total'];
										if ( $tax == 'incl' ) {
											$subtotal_value += $item['line_tax'];
										}
									}
								}
								$subtotal_data = array(
									'label'	=> __('Subtotal', 'woocommerce-pdf-invoices-packing-slips'),
									'value'	=> $document->format_price( $subtotal_value ),
								);
								$totals_table_data[$total_key] = (array) $total_setting + $subtotal_data;
								break;
						}
						break;
					case 'discount':
						// $tax, $show_codes, $show_percentage
						if ( $discount = $document->get_order_discount( 'total', $tax ) ) {
							if (isset($discount['raw_value'])) {
								// support for positive discount (=more expensive/price corrections)
								$discount['value'] = $document->format_price( $discount['raw_value'] * -1 );
							} else {
								$discount['value'] = '-'.$discount['value'];
							}

							$discount_percentage = $this->get_discount_percentage( $document->order );
							if (isset($show_percentage) && $discount_percentage) {
								$discount['label'] = "{$discount['label']} ({$discount_percentage}%)";
							}

							$used_coupons = implode(', ', $document->order->get_used_coupons() );
							if (isset($show_codes) && !empty($used_coupons)) {
								$discount['label'] = "{$discount['label']} ({$used_coupons})";
							}

							$totals_table_data[$total_key] = (array) $total_setting + $discount;
						}
						break;
					case 'shipping':
						// $tax, $method, $hide_free
						$shipping_cost = WCX_Order::get_prop( $document->order, 'shipping_total', 'view' );
						if ( !(round( $shipping_cost, 3 ) == 0 && isset($hide_free)) ) {
							$totals_table_data[$total_key] = (array) $total_setting + $document->get_order_shipping( $tax );
							if (!empty($method)) {
								$totals_table_data[$total_key]['value'] = $document->order->get_shipping_method();
							}
						}
						break;
					case 'fees':
						// $tax
						if ( $fees = $document->get_order_fees( $tax ) ) {

							// WooCommerce Checkout Add-Ons compatibility
							if ( function_exists('wc_checkout_add_ons')) {
								$wc_checkout_add_ons = wc_checkout_add_ons();
								// we're adding a 'fee_' prefix because that's what woocommerce does in its
								// order total keys and wc_checkout_add_ons uses this to determine the total type (fee)
								$fees = $this->array_keys_prefix($fees, 'fee_', 'add');
								if (method_exists($wc_checkout_add_ons, 'get_frontend_instance')) {
									$wc_checkout_add_ons_frontend = $wc_checkout_add_ons->get_frontend_instance();
									$fees = $wc_checkout_add_ons_frontend->append_order_add_on_fee_meta( $fees, $document->order );
								} elseif ( is_object(wc_checkout_add_ons()->frontend) && method_exists(wc_checkout_add_ons()->frontend, 'append_order_add_on_fee_meta') ) {
									$fees = wc_checkout_add_ons()->frontend->append_order_add_on_fee_meta( $fees, $document->order );
								}
								$fees = $this->array_keys_prefix($fees, 'fee_', 'remove');
							}

							reset($fees);
							$first = key($fees);
							end($fees);
							$last = key($fees);
							
							foreach( $fees as $fee_key => $fee ) {
								$class = 'fee-line';
								if ($fee_key == $first) $class .= ' first';
								if ($fee_key == $last) $class .= ' last';

								$totals_table_data[$total_key.$fee_key] = (array) $total_setting + $fee;
								$totals_table_data[$total_key.$fee_key]['class'] = $class;
							}
						}
						break;
					case 'vat':
						// $percent, $base
						$total_tax = $document->order->get_total_tax();
						$shipping_tax = $document->order->get_shipping_tax();

						if ( isset ( $single_total ) ) {
							$tax = array();

							// override label if set
							// unset($total_setting['label']);
							$tax['label'] = !empty($label) ? $label : __( 'VAT', 'wpo_wcpdf_templates' );


							if ( isset($tax_type) && $tax_type == 'product' ) {
								$tax['value'] = $document->format_price( $total_tax - $shipping_tax );
							} elseif ( isset($tax_type) && $tax_type == 'shipping' ) {
								$tax['value'] = $document->format_price( $shipping_tax );
							} else {
								$tax['value'] = $document->format_price( $total_tax );
							}
							
							$totals_table_data[$total_key] = (array) $total_setting + (array) $tax;
							$totals_table_data[$total_key]['class'] = 'vat tax-line';
						} elseif ($taxes = $document->get_order_taxes()) {
							$taxes = $this->add_tax_base( $taxes, $document->order );

							reset($taxes);
							$first = key($taxes);
							end($taxes);
							$last = key($taxes);

							foreach( $taxes as $tax_key => $tax ) {
								$class = 'tax-line';
								if ($tax_key == $first) $class .= ' first';
								if ($tax_key == $last) $class .= ' last';

								// prepare label format based on settings
								$label_format = '{{label}}';
								if (isset($percent)) $label_format .= ' {{rate}}';

								// prevent errors if base not set
								if ( empty( $tax['base'] ) ) $tax['base'] = 0;

								// override label if set
								$tax_label = !empty($label) ? $label : $tax['label'];
								unset($total_setting['label']);

								if ( isset($tax_type) && $tax_type == 'product' ) {
									if ( apply_filters( 'woocommerce_order_hide_zero_taxes', true ) && $tax['tax_amount'] == 0 ) {
										continue;
									}
									$tax_amount = $tax['tax_amount'];
								} elseif ( isset($tax_type) && $tax_type == 'shipping' ) {
									if ( apply_filters( 'woocommerce_order_hide_zero_taxes', true ) && $tax['shipping_tax_amount'] == 0 ) {
										continue;
									}
									$tax_amount = $tax['shipping_tax_amount'];
								} else {
									$tax_amount = $tax['tax_amount'] + $tax['shipping_tax_amount'];
									if (isset($base) && !empty($tax['base'])) $label_format .= ' ({{base}})'; // add base to label
								}
								$tax['value'] = $document->format_price( $tax_amount );

								// fallback to tax calculation if we have no rate
								// if ( empty( $tax['rate'] ) && method_exists( $document, 'calculate_tax_rate' ) ) {
								// 	$tax['rate'] = $document->calculate_tax_rate( $tax['base'], $tax_amount );
								// }

								$label_format = apply_filters( 'wpo_wcpdf_templates_tax_total_label_format', $label_format );

								$tax['label'] = str_replace( array( '{{label}}', '{{rate}}', '{{base}}' ) , array( $tax_label, $tax['calculated_rate'], $document->format_price( $tax['base'] ) ), $label_format );

								$totals_table_data[$total_key.$tax_key] = (array) $total_setting + $tax;
								$totals_table_data[$total_key.$tax_key]['class'] = $class;
							}
						}
						break;
					case 'vat_base':
						// $percent
						if ($taxes = $document->get_order_taxes()){
							$taxes = $this->add_tax_base( $taxes, $document->order );

							reset($taxes);
							$first = key($taxes);
							end($taxes);
							$last = key($taxes);

							if (empty($total_setting['label'])) {
								$total_setting['label'] = $label = __( 'Total ex. VAT', 'woocommerce-pdf-invoices-packing-slips' );
							}

							foreach( $taxes as $tax_key => $tax ) {
								// prevent errors if base not set
								if ( empty( $tax['base'] ) ) continue;

								$class = 'tax-base-line';
								if ($tax_key == $first) $class .= ' first';
								if ($tax_key == $last) $class .= ' last';

								// prepare label format based on settings
								$label_format = '{{label}}';
								if (isset($percent)) $label_format .= ' ({{rate}})';
								$label_format = apply_filters( 'wpo_wcpdf_templates_tax_base_total_label_format', $label_format );

								$tax['value'] = $document->format_price( $tax['base'] );

								$total_setting['label'] = str_replace( array( '{{label}}', '{{rate}}' ) , array( $label, $tax['rate'] ), $label_format );

								$totals_table_data[$total_key.$tax_key] = (array) $total_setting + $tax;
								$totals_table_data[$total_key.$tax_key]['class'] = $class;
							}
						}
						break;
					case 'total':
						// $tax
						if ( $tax == 'excl' && apply_filters( 'wpo_wcpdf_add_up_grand_total_excl', false ) ) {
							// alternative calculation method that adds up product prices, fees & shipping
							// rather than subtracting tax from the grand total => WC3.0+ only!
							$grand_total_ex = 0;
							foreach ( $document->order->get_items() as $item_id => $item ) {
								$grand_total_ex += $item->get_total(); // total = after discount!
							}
							foreach ( $document->order->get_fees() as $item_id => $item ) {
								$grand_total_ex += $item->get_total(); // total = after discount!
							}
							$grand_total_ex += $document->order->get_shipping_total();
							$grand_total_row = array(
								'label' => __( 'Total ex. VAT', 'woocommerce-pdf-invoices-packing-slips' ),
								'value' => wc_price( $grand_total_ex, array( 'currency' => $document->order->get_currency() ) ),
							);
							$totals_table_data[$total_key] = (array) $total_setting + $grand_total_row;
						} else {
							$totals_table_data[$total_key] = (array) $total_setting + $document->get_order_grand_total( $tax );
						}
						if ( $tax == 'incl') {
							$totals_table_data[$total_key]['class'] = 'total grand-total';
						}
						break;
					case 'order_weight':
						// $show_unit
						$order_weight = array (
							'label'	=> __( 'Total weight', 'wpo_wcpdf_templates' ),
							'value'	=> $this->get_order_weight( $document->order, isset( $show_unit) ),
						);

						$totals_table_data[$total_key] = (array) $total_setting + $order_weight;
						break;
					case 'total_qty':
						$total_qty_total = array (
							'label'	=> __( 'Total quantity', 'wpo_wcpdf_templates' ),
							'value'	=> $this->get_order_total_qty( $document->order ),
						);

						$totals_table_data[$total_key] = (array) $total_setting + $total_qty_total;
						break;
					default:
						break;
				}

				// set class if not set. note that fees and taxes have modified keys!
				if (isset($totals_table_data[$total_key]) && !isset($totals_table_data[$total_key]['class'])) {
					$totals_table_data[$total_key]['class'] = $type;
				}
			}

			return $totals_table_data;
		}


		public function get_order_details_header ( $column_setting, $document ) {
			extract($column_setting);

			if (!empty($label)) {
				$header['title'] = __( $label, 'woocommerce-pdf-invoices-packing-slips' ); // not proper gettext, but it makes it possible to reuse po translations!
			} else {
				switch ($type) {
					case 'position':
						$header['title'] = '';
						break;
					case 'sku':
						$header['title'] = __( 'SKU', 'woocommerce-pdf-invoices-packing-slips' );
						break;
					case 'thumbnail':
						$header['title'] = '';
						break;
					case 'description':
						$header['title'] = __( 'Product', 'woocommerce-pdf-invoices-packing-slips' );
						break;
					case 'quantity':
						$header['title'] = __( 'Quantity', 'woocommerce-pdf-invoices-packing-slips' );
						break;
					case 'price':
						switch ($price_type) {
							case 'single':
								$header['title'] = __( 'Price', 'woocommerce-pdf-invoices-packing-slips' );
								$header['class'] = 'price';
								break;
							case 'total':
								$header['title'] = __( 'Total', 'woocommerce-pdf-invoices-packing-slips' );
								$header['class'] = 'total';
								break;
						}
						break;
					case 'regular_price':
						$header['title'] = __( 'Regular price', 'wpo_wcpdf_templates' );
						break;
					case 'discount':
						$header['title'] = __( 'Discount', 'woocommerce-pdf-invoices-packing-slips' );
						break;
					case 'vat':
						$header['title'] = __( 'VAT', 'woocommerce-pdf-invoices-packing-slips' );
						break;
					case 'tax_rate':
						$header['title'] = __( 'Tax rate', 'woocommerce-pdf-invoices-packing-slips' );
						break;
					case 'weight':
						$header['title'] = __( 'Weight', 'woocommerce-pdf-invoices-packing-slips' );
						break;
					case 'product_attribute':
						$header['title'] = '';
						break;
					case 'product_custom':
						$header['title'] = '';
						break;
					case 'product_description':
						$header['title'] = __( 'Product description', 'wpo_wcpdf_templates' );
						break;
					case 'product_categories':
						$header['title'] = __( 'Categories', 'wpo_wcpdf_templates' );
						break;
					case 'all_meta':
						$header['title'] = __( 'Variation', 'wpo_wcpdf_templates' );
						break;
					case 'item_meta':
						$header['title'] = isset( $meta_key ) ? $meta_key : '';
						break;
					case 'cb':
						$header['title'] = '';
						break;
					case 'static_text':
						$header['title'] = '';
						break;
					default:
						$header['title'] = $type;
						break;
				}
			}

			// set class if not set;
			if (!isset($header['class'])) {
				$header['class'] = $type;
			}

			// column specific classes
			switch ($type) {
				case 'product_attribute':
					if (!empty($attribute_name)) {
						$attribute_name_class = sanitize_title( $attribute_name );
						$header['class'] = "{$type} {$attribute_name_class}";
					}
					break;
				case 'product_custom':
					if (!empty($field_name)) {
						$field_name_class = sanitize_title( $field_name );
						$header['class'] .= " {$field_name_class}";
					}
					break;
				default:
					break;
			}

			// mark first and last column
			if (isset($position)) {
				$header['class'] .= " {$position}-column";
			}

			return $header;
		}

		public function get_order_details_data ( $column_setting, $item, $document ) {
			extract($column_setting);

			switch ($type) {
				case 'position':
					$column['data'] = $line_number;
					break;
				case 'sku':
					$column['data'] = isset($item['sku']) ? $item['sku'] : '';
					break;
				case 'thumbnail':
					$column['data'] = isset($item['thumbnail']) ? $item['thumbnail'] : '';
					break;
				case 'description':
					// $show_sku, $show_weight
					ob_start();
					?>
					<span class="item-name"><?php echo $item['name']; ?></span>
					<?php if ( isset($show_external_plugin_meta) ) : ?>
					<div class="external-meta-start">
					<?php do_action( 'woocommerce_order_item_meta_start', $item['item_id'], $item['item'], $document->order ); ?>
					</div>
					<?php endif; ?>
					<?php do_action( 'wpo_wcpdf_before_item_meta', $document->get_type(), $item, $document->order ); ?>
					<?php if ( isset($show_meta) ) : ?>
					<span class="item-meta"><?php echo $item['meta']; ?></span>
					<?php endif; ?>
					<?php if ( isset($show_sku) || isset($show_weight) ) : ?>
					<dl class="meta">
						<?php $description_label = __( 'SKU', 'woocommerce-pdf-invoices-packing-slips' ); // registering alternate label translation ?>
						<?php if( !empty( $item['sku'] ) && isset($show_sku) ) : ?><dt class="sku"><?php _e( 'SKU:', 'woocommerce-pdf-invoices-packing-slips' ); ?></dt><dd class="sku"><?php echo $item['sku']; ?></dd><?php endif; ?>
						<?php if( !empty( $item['weight'] ) && isset($show_weight) ) : ?><dt class="weight"><?php _e( 'Weight:', 'woocommerce-pdf-invoices-packing-slips' ); ?></dt><dd class="weight"><?php echo $item['weight']; ?><?php echo get_option('woocommerce_weight_unit'); ?></dd><?php endif; ?>
					</dl>
					<?php endif; ?>
					<?php do_action( 'wpo_wcpdf_after_item_meta', $document->get_type(), $item, $document->order ); ?>
					<?php if ( isset($show_external_plugin_meta) ) : ?>
					<div class="external-meta-end">
					<?php do_action( 'woocommerce_order_item_meta_end', $item['item_id'], $item['item'], $document->order ); ?>
					</div>
					<?php endif; ?>

					<?php
					$column['data'] = ob_get_clean();
					break;
				case 'quantity':
					$column['data'] = $item['quantity'];
					break;
				case 'price':
					// $price_type, $tax, $discount
					// using a combined value to make this more readable...
					$price_type_full = "{$price_type}_{$tax}_{$discount}";
					switch ($price_type_full) {
						// before discount
						case 'single_incl_before':
							$column['data'] = $item['single_price'];
							break;
						case 'single_excl_before':
							$column['data'] = $item['ex_single_price'];
							break;
						case 'total_incl_before':
							$column['data'] = $item['price'];
							break;
						case 'total_excl_before':
							$column['data'] = $item['ex_price'];
							break;

						// after discount
						case 'single_incl_after':
							$price = ( $item['item']['line_total'] + $item['item']['line_tax'] ) / max( 1, abs( $item['quantity'] ) );
							$column['data'] = $document->format_price( $price );
							break;
						case 'single_excl_after':
							$column['data'] = $item['single_line_total'];
							break;
						case 'total_incl_after':
							$price = $item['item']['line_total'] + $item['item']['line_tax'];
							$column['data'] = $document->format_price( $price );
							break;
						case 'total_excl_after':
							$column['data'] = $item['line_total'];
							break;
					}

					if ($price_type == 'total') {
						$column['class'] = 'total';
					}
					break;
				case 'regular_price':
					// $price_type, $tax, $only_sale
					$regular_prices = $this->get_regular_item_price( $item['item'], $item['item_id'], $document->order );

					// check if item price is different from sale price
					$single_item_price = ( $item['item']['line_subtotal'] + $item['item']['line_subtotal_tax'] ) / max( 1, $item['quantity'] );
					if ( isset($only_sale) && round( $single_item_price, 2 ) == round( $regular_prices['incl'], 2 ) ) {
						$column['data'] = '';
					} else {
						// get including or excluding tax
						$regular_price = $regular_prices[$tax];
						// single or total
						if ($price_type == 'total') {
							$regular_price = $regular_price * $item['quantity'];
						}
						$column['data'] = $document->format_price( $regular_price );
					}
					break;
				case 'discount':
					// $price_type, $tax
					if ($price_type == 'percent') {
						$discount = ( ($item['item']['line_subtotal'] + $item['item']['line_subtotal_tax']) - ( $item['item']['line_total'] + $item['item']['line_tax'] ) );
						if ($discount > 0) {
							$percent = round( ( $discount / ( $item['item']['line_subtotal'] + $item['item']['line_subtotal_tax'] ) ) * 100 );
							$column['data'] = "{$percent}%";
						} else {
							$column['data'] = "";
						}
						break;
					}
					
					$price_type = "{$price_type}_{$tax}";
					switch ($price_type) {
						case 'single_incl':
							$price = ( ($item['item']['line_subtotal'] + $item['item']['line_subtotal_tax']) - ( $item['item']['line_total'] + $item['item']['line_tax'] ) ) / max( 1, abs( $item['quantity'] ) );
							$column['data'] = $document->format_price( (float) $price * -1 );
							break;
						case 'single_excl':
							$price = ( $item['item']['line_subtotal'] - $item['item']['line_total'] ) / max( 1, abs( $item['quantity'] ) );
							$column['data'] = $document->format_price( (float) $price * -1  );
							break;
						case 'total_incl':
							$price = ($item['item']['line_subtotal'] + $item['item']['line_subtotal_tax']) - ( $item['item']['line_total'] + $item['item']['line_tax'] );
							$column['data'] = $document->format_price( (float) $price * -1  );
							break;
						case 'total_excl':
							$price = $item['item']['line_subtotal'] - $item['item']['line_total'];
							$column['data'] = $document->format_price( (float) $price * -1  );
							break;
					}
					break;
				case 'vat':
					// $price_type, $discount
					$price_type = "{$price_type}_{$discount}";
					switch ($price_type) {
						// before discount
						case 'single_before':
							$price = ( $item['item']['line_subtotal_tax'] ) / max( 1, $item['quantity'] );
							$column['data'] = $document->format_price( $price );
							break;
						case 'single_after':
							$price = ( $item['item']['line_tax'] ) / max( 1, $item['quantity'] );
							$column['data'] = $document->format_price( $price );
							break;
						case 'total_before':
							$column['data'] = $item['line_subtotal_tax'];
							break;
						case 'total_after':
							$column['data'] = $item['line_tax'];
							break;
					}
					break;
				case 'tax_rate':
					$column['data'] = $item['tax_rates'];
					break;
				case 'weight':
					if ( !isset($qty) ) {
						$qty = 'single';
					}

					switch ($qty) {
						case 'single':
							$column['data'] = !empty($item['weight']) ? $item['weight'] : '';
							break;
						case 'total':
							$column['data'] = !empty($item['weight']) ? $item['weight'] * $item['quantity'] : '';
							break;
					}
					if (isset($show_unit) && !empty($item['weight'])) {
						$column['data'] .= get_option('woocommerce_weight_unit');
					}
					break;
				case 'product_attribute':
					if (isset($item['product'])) {
						$attribute_name_class = sanitize_title( $attribute_name );
						$column['class'] = "{$type} {$attribute_name_class}";
						$column['data'] = $document->get_product_attribute( $attribute_name, $item['product'] );
					} else {
						$column['data'] = '';
					}
					break;
				case 'product_custom':
					if (isset($item['product']) && !empty($field_name)) {
						$field_name_class = sanitize_title( $field_name );
						$column['class'] = "{$type} {$field_name_class}";
						$product = wc_get_product( $item['product_id'] );
						$custom = WCX_Product::get_meta( $product, $field_name, true, 'view' );

						// fallback to product properties
						$property_meta_keys = array(
							'_stock'		=> 'stock_quantity',
						);
						if (in_array($field_name, array_keys($property_meta_keys))) {
							$property = $property_meta_keys[$field_name];
						} else {
							$property = str_replace('-', '_', sanitize_title( ltrim($field_name, '_') ) );
						}
						if ( empty( $custom ) && is_callable( array( $product, "get_{$property}" ) ) ) {
							$custom = $product->{"get_{$property}"}( 'view' );
						}

						$column['data'] = $custom;
					} else {
						$column['data'] = '';
					}
					break;
				case 'product_description':
					if (isset($item['product'])) {
						if ( isset( $use_variation_description ) && isset( $item['variation_id'] ) && $item['variation_id'] != 0 && version_compare( WOOCOMMERCE_VERSION, '2.4', '>=' ) ) {
							$column['data'] = $item['product']->get_variation_description();
						} else {
							if ( version_compare( WOOCOMMERCE_VERSION, '3.0', '>=' ) && $item['product']->is_type( 'variation' ) ) {
								$_product = wc_get_product( $item['product']->get_parent_id() );
							} else {
								$_product = $item['product'];
							}
							switch ($description_type) {
								case 'short':
									if ( method_exists( $_product, 'get_short_description' ) ) {
										$column['data'] = $_product->get_short_description();
									} else {
										$column['data'] =  $_product->post->post_excerpt;
									}
									break;
								case 'long':
									if ( method_exists( $_product, 'get_description' ) ) {
										$column['data'] = $_product->get_description();
									} else {
										$column['data'] = $_product->post->post_content;
									}
									break;
							}
						}
					} else {
						$column['data'] = '';
					}
					break;
				case 'product_categories':
					if (isset($item['product'])) {
						if (function_exists('wc_get_product_category_list')) {
							// WC3.0+
							$category_list = wc_get_product_category_list( $item['product']->get_id() );
						} else {
							$category_list = $item['product']->get_categories();
						}
						$column['data'] = strip_tags( $category_list );
					} else {
						$column['data'] = '';
					}
					break;
				case 'all_meta':
					// $product_fallback
					// For an order added through the admin) we can display
					// the formatted variation data (if fallback enabled)
					if ( isset($product_fallback) && empty($item['meta']) && isset($item['product']) && function_exists('wc_get_formatted_variation') ) {
						$variation_data = WCX_Product::get_prop( $item['product'], 'variation_data' );
						$item['meta'] = wc_get_formatted_variation( $variation_data, true );
					}
					$column['data'] = '<span class="item-meta">'.$item['meta'].'</span>';
					break;
				case 'item_meta':
					// $field_name
					if ( !empty($field_name) ) {
						$column['data'] = wc_get_order_item_meta( $item['item_id'], $field_name, true );
					} else {
						$column['data'] = '';
					}
					break;
				case 'cb':
					$column['data'] = '<span class="checkbox"></span>';
					break;
				case 'static_text':
					// $text
					$column['data'] = !empty( $text ) ? $text : '';
					break;

				default:
					$column['data'] = '';
					break;
			}

			// set class if not set;
			if (!isset($column['class'])) {
				$column['class'] = $type;
			}

			// mark first and last column
			if (isset($position)) {
				$column['class'] .= " {$position}-column";
			}

			return apply_filters( 'wpo_wcpdf_templates_item_column_data', $column, $column_setting, $item, $document );
		}

		/**
		 * Output custom blocks (if set for template)
		 */
		public function custom_blocks_data( $template_type, $order ) {
			$editor_settings = get_option('wpo_wcpdf_editor_settings');
			if (!empty($editor_settings["fields_{$template_type}_custom"])) {
				foreach ($editor_settings["fields_{$template_type}_custom"] as $key => $custom_block) {
					// echo "<pre>";var_dump($custom_block);echo "</pre>";die();
					if ( current_filter() != $custom_block['position']) {
						continue;
					}

					// only process blocks with input
					if ( ( $custom_block['type'] == 'custom_field' || $custom_block['type'] == 'user_meta' ) && empty( $custom_block['meta_key'] ) ) {
						continue;
					} elseif ( $custom_block['type'] == 'text' && empty( $custom_block['text'] ) ) {
						continue;
					}

					switch ($custom_block['type']) {
						case 'custom_field':
							$data = WCX_Order::get_meta( $order, $custom_block['meta_key'], true, 'view' );
							// WC3.0+ fallback to properties
							$property = str_replace('-', '_', sanitize_title( ltrim( $custom_block['meta_key'], '_' ) ) );
							if ( empty( $data ) && is_callable( array( $order, "get_{$property}" ) ) ) {
								$data = $order->{"get_{$property}"}( 'view' );
							}
							$class = $custom_block['meta_key'];
							break;
						case 'user_meta':
							$order_id = WCX_Order::get_id( $order );
							if ( get_post_type( $order_id ) == 'shop_order_refund' && $parent_order_id = wp_get_post_parent_id( $order_id ) ) {
								$parent_order = WCX::get_order( $parent_order_id );
								$user_id = $parent_order->get_user_id();
							} else {
								$user_id = $order->get_user_id();
							}
							if ( !empty($user_id) ) {
							    $meta_key = $custom_block['meta_key']; 
							    $data = get_user_meta( $user_id, $meta_key, true );
							} else {
								$data = '';
							}
							$class = $custom_block['meta_key'];
							break;
						case 'text':
							$document = wcpdf_get_document( $template_type, $order );
							$formatted_text = $this->make_replacements( $custom_block['text'], $order, $document );
							$data =  nl2br( wptexturize( $formatted_text ) );
							$class = 'custom-block-text';
							break;						
					}

					// Hide if empty option
					if ( ( $custom_block['type'] == 'custom_field' || $custom_block['type'] == 'user_meta' ) && isset($custom_block['hide_if_empty']) && empty( $data ) ) {
						continue;
					}


					// output table rows if in order data table
					if ( in_array( current_filter(), array( 'wpo_wcpdf_before_order_data', 'wpo_wcpdf_after_order_data') ) ) {
						printf('<tr class="%s"><th>%s</th><td>%s</td></tr>', $class, $custom_block['label'], $data );
					} else {
						if (!empty($custom_block['label'])) {
							printf('<h3 class="%s-label">%s</h3>', $class, $custom_block['label'] );
						}
						// only apply div wrapper if not already in div
						if ( stripos($data, '<div') !== false ) {
							echo $data;
						} else {
							printf('<div class="%s">%s</div>', $class, $data );
						}
					}
				};
			}
		}

		public function settings_fields_replacements( $text, $document ) {
			// make replacements if placeholders present
			if ( strpos( $text, '{{' ) !== false ) {
				$text = $this->make_replacements( $text, $document->order, $document );
			}

			return $text;
		}

		public function make_replacements ( $text, $order, $document = null ) {
			$order_id = WCX_Order::get_id( $order );

			// load parent order for refunds
			if ( get_post_type( $order_id ) == 'shop_order_refund' && $parent_order_id = wp_get_post_parent_id( $order_id ) ) {
				$parent_order = WCX::get_order( $parent_order_id );
			}

			// make an index of placeholders used in the text
			preg_match_all('/\{\{.*?\}\}/', $text, $placeholders_used);
			$placeholders_used = array_shift($placeholders_used); // we only need the first match set

			// load countries & states
			$countries = new WC_Countries;

			// loop through placeholders and make replacements
			foreach ($placeholders_used as $placeholder) {
				$placeholder_clean = trim($placeholder,"{{}}");
				$ignore = array( '{{PAGE_NUM}}', '{{PAGE_COUNT}}' );
				if (in_array($placeholder, $ignore)) {
					continue;
				}

				// first try to read data from order, fallback to parent order (for refunds)
				$data_sources = array( 'order', 'parent_order' );
				foreach ($data_sources as $data_source) {
					if (empty($$data_source)) {
						continue;
					}

					// custom/third party filters
					$filter = "wpo_wcpdf_templates_replace_".sanitize_title( $placeholder_clean );
					if ( has_filter( $filter ) ) {
						$custom_filtered = apply_filters( $filter, null, $$data_source );
						if ( isset( $custom_filtered ) ) {
							$text = str_replace($placeholder, $custom_filtered, $text);
							continue 2;
						}
					}

					// special treatment for country & state
					$country_placeholders = array( 'shipping_country', 'billing_country' );
					$state_placeholders = array( 'shipping_state', 'billing_state' );
					foreach ( array_merge($country_placeholders, $state_placeholders) as $country_state_placeholder ) {
						if ( strpos( $placeholder_clean, $country_state_placeholder ) !== false ) {
							// check if formatting is needed
							if ( strpos($placeholder_clean, '_code') !== false ) {
								// no country or state formatting
								$placeholder_clean = str_replace('_code', '', $placeholder_clean);
								$format = false;
							} else {
								$format = true;
							}

							$country_or_state = WCX_Order::get_prop( $$data_source, $placeholder_clean );

							if ($format === true) {
								// format country or state
								if (in_array($placeholder_clean, $country_placeholders)) {
									$country_or_state = ( $country_or_state && isset( $countries->countries[ $country_or_state ] ) ) ? $countries->countries[ $country_or_state ] : $country_or_state;
								} elseif (in_array($placeholder_clean, $state_placeholders)) {
									// get country for address
									$country = WCX_Order::get_prop( $$data_source, str_replace( 'state', 'country', $placeholder_clean ) );
									$country_or_state = ( $country && $country_or_state && isset( $countries->states[ $country ][ $country_or_state ] ) ) ? $countries->states[ $country ][ $country_or_state ] : $country_or_state;
								}
							}

							if ( !empty( $country_or_state ) ) {
								$text = str_replace($placeholder, $country_or_state, $text);
								continue 3;
							}
						}
					}

					// date offset placeholders
					if ( strpos($placeholder_clean, '|+') !== false ) {
						$calculated_date = '';
						$placeholder_args = explode('|+', $placeholder_clean);
						if (!empty($placeholder_args[1])) {
							$date_name = $placeholder_args[0];
							$date_offset = $placeholder_args[1];
							switch ($date_name) {
								case 'order_date':
									$order_date = WCX_Order::get_prop( $$data_source, 'date_created' );
									$calculated_date = date_i18n( wc_date_format(), strtotime( $order_date->date_i18n('Y-m-d H:i:s') . " + {$date_offset}") );
									break;
								case 'invoice_date':
									$invoice_date_set = WCX_Order::get_meta( $$data_source, "_wcpdf_invoice_date" );
									// prevent creating invoice date when not already set
									if (!empty($invoice_date_set) && !empty($document)) {
										$invoice_date = $document->get_date('invoice');
										$calculated_date = date_i18n( wc_date_format(), strtotime( $invoice_date->date_i18n('Y-m-d H:i:s') . " + {$date_offset}" ) );
									}
									break;
							}
						}
						if (!empty($calculated_date)) {
							$text = str_replace($placeholder, $calculated_date, $text);
							continue 2;
						}
					}

					// Custom placeholders
					$custom = '';
					switch ($placeholder_clean) {
						case 'invoice_number':
							if (!empty($document)) {
								$custom = $document->get_invoice_number();
							}
							break;
						case 'invoice_date':
							$invoice_date = WCX_Order::get_meta( $$data_source, "_wcpdf_invoice_date" );
							// prevent creating invoice date when not already set
							if (!empty($invoice_date) && !empty($document)) {
								$custom = $document->get_invoice_date();
							}
							break;
						case 'site_title':
							$custom = get_bloginfo();
							break;
						case 'shipping_notes':
						case 'customer_note':
							$custom = WCX_Order::get_prop( $$data_source, 'customer_note', 'view' );
							if (!empty($custom)) {
								$custom = wpautop( wptexturize( $custom ) );
							}
							break;
						case 'order_notes':
							$custom = $this->get_order_notes( $$data_source );
							break;
						case 'private_order_notes':
							$custom = $this->get_order_notes( $$data_source, 'private' );
							break;
						case 'order_number':
							if ( method_exists( $$data_source, 'get_order_number' ) ) {
								$custom = ltrim($$data_source->get_order_number(), '#');
							} else {
								$custom = '';
							}
							break;
						case 'order_status':
							if ( version_compare( WOOCOMMERCE_VERSION, '2.2', '>=' ) ) {
								$custom = wc_get_order_status_name( $$data_source->get_status() );
							} else {
								$status = get_term_by( 'slug', $$data_source->status, 'shop_order_status' );
								$custom = __( $status->name, 'woocommerce' );
							}
							break;							
						case 'order_date':
							$order_date = WCX_Order::get_prop( $$data_source, 'date_created' );
							$custom = $order_date->date_i18n( wc_date_format() );
							break;
						case 'order_time':
							$order_date = WCX_Order::get_prop( $$data_source, 'date_created' );
							$custom = $order_date->date_i18n( wc_time_format() );
							break;
						case 'order_weight':
							$custom = $this->get_order_weight( $$data_source );
							break;
						case 'order_qty':
							$custom = $this->get_order_total_qty( $$data_source );
							break;
						case 'date_paid':
						case 'paid_date':
							$date_paid = WCX_Order::get_prop( $$data_source, 'date_paid' );
							$custom = !empty($date_paid) ? $date_paid->date_i18n( wc_date_format() ) : '-';
							break;
						case 'date_completed':
						case 'completed_date':
							$date_completed = WCX_Order::get_prop( $$data_source, 'date_completed' );
							$custom = !empty($date_completed) ? $date_completed->date_i18n( wc_date_format() ) : '-';
							break;
						case 'current_date':
							$custom = date_i18n( wc_date_format() );
							break;
						case 'used_coupons':
							$custom = implode(', ', $$data_source->get_used_coupons() );
							$text = str_replace($placeholder, $custom, $text);
							continue 3; // do not fallback to parent order
							break;
						case 'current_user_name':
							$user = wp_get_current_user();
							if ( $user instanceof \WP_User ) {
								$custom = $user->display_name;
							}
							break;
						case 'formatted_order_total':
							if (!empty($document)) {
								$grand_total 	= $document->get_order_grand_total('incl');
								$custom			= $grand_total['value'];
							}
							break;
						case 'formatted_subtotal':
							if (!empty($document)) {
								$subtotal 		= $document->get_order_subtotal('incl');
								$custom			= $subtotal['value'];
							}
							break;
						case 'formatted_discount':
							if (!empty($document)) {
								$discount 		= $document->get_order_discount('total', 'incl');
								$custom			= isset($discount['value']) ? $discount['value'] : '';
							}
							break;
						case 'formatted_shipping':
							if (!empty($document)) {
								$shipping 		= $document->get_order_shipping('incl');
								$custom			= $shipping['value'];
							}
							break;
						case 'formatted_order_total_ex':
							if (!empty($document)) {
								$grand_total 	= $document->get_order_grand_total('excl');
								$custom			= $grand_total['value'];
							}
							break;
						case 'formatted_subtotal_ex':
							if (!empty($document)) {
								$subtotal 		= $document->get_order_subtotal('excl');
								$custom			= $subtotal['value'];
							}
							break;
						case 'formatted_discount_ex':
							if (!empty($document)) {
								$discount 		= $document->get_order_discount('total', 'excl');
								$custom			= isset($discount['value']) ? $discount['value'] : '';
							}
							break;
						case 'formatted_shipping_ex':
							if (!empty($document)) {
								$shipping 		= $document->get_order_shipping('excl');
								$custom			= $shipping['value'];
							}
							break;
						case 'wc_order_barcode':
							if ( function_exists('WC_Order_Barcodes') ) {
								$barcode_url = WC_Order_Barcodes()->barcode_url( WCX_Order::get_id( $$data_source ) );
								$barcode_text = WCX_Order::get_meta( $$data_source, "_barcode_text" );
								$custom = sprintf('<div style="text-align: center; width: 40mm;" class="wc-order-barcode"><div style="height: 10mm; width: 40mm; overflow:hidden; position:relative"><img src="%s" style="width: 40mm; height:40mm; position: absolute; bottom: 0mm; left: 0;"/></div><span class="wc-order-barcodes-text">%s</span></div>', $barcode_url, $barcode_text );
							}
							break;
						case 'local_pickup_plus_pickup_details':
							$custom = $this->get_local_pickup_plus_pickup_details( $$data_source );
							break;

						default:
							break;
					}
					if ( !empty( $custom ) ) {
						$text = str_replace($placeholder, $custom, $text);
						continue 2;
					}

					// Order Properties
					if (in_array($placeholder_clean, array('shipping_address', 'billing_address'))) {
						$placeholder_clean = "formatted_{$placeholder_clean}";
					}

					$property_meta_keys = array(
						'_order_currency'		=> 'currency',
						'_order_tax'			=> 'total_tax',
						'_order_total'			=> 'total',
						'_order_version'		=> 'version',
						'_order_shipping'		=> 'shipping_total',
						'_order_shipping_tax'	=> 'shipping_tax',
					);
					if (in_array($placeholder_clean, array_keys($property_meta_keys))) {
						$property_name = $property_meta_keys[$placeholder_clean];
					} else {
						$property_name = str_replace('-', '_', sanitize_title( ltrim($placeholder_clean, '_') ) );
					}
					$prop = WCX_Order::get_prop( $$data_source, $property_name, 'view' );
					if ( !empty( $prop ) ) {
						$text = str_replace($placeholder, $prop, $text);
						continue 2;
					}

					// Order Meta
					if ( !$this->is_order_prop( $placeholder_clean ) ) {
						$meta = WCX_Order::get_meta( $$data_source, $placeholder_clean, true, 'view' );
						if ( !empty( $meta ) ) {
							$text = str_replace($placeholder, $meta, $text);
							continue 2;
						} else {
							// Fallback to hidden meta
							$meta = WCX_Order::get_meta( $$data_source, "_{$placeholder_clean}", true, 'view' );
							if ( !empty( $meta ) ) {
								$text = str_replace($placeholder, $meta, $text);
								continue 2;
							}
						}
					}
				}

				// remove placeholder if no replacement was made
				$text = str_replace($placeholder, '', $text);
			}

			return $text;
		}

		public function is_order_prop( $key ) {
			// Taken from WC class
			$order_props = array(
				// Abstract order props
				'parent_id',
				'status',
				'currency',
				'version',
				'prices_include_tax',
				'date_created',
				'date_modified',
				'discount_total',
				'discount_tax',
				'shipping_total',
				'shipping_tax',
				'cart_tax',
				'total',
				'total_tax',
				// Order props
				'customer_id',
				'order_key',
				'billing_first_name',
				'billing_last_name',
				'billing_company',
				'billing_address_1',
				'billing_address_2',
				'billing_city',
				'billing_state',
				'billing_postcode',
				'billing_country',
				'billing_email',
				'billing_phone',
				'shipping_first_name',
				'shipping_last_name',
				'shipping_company',
				'shipping_address_1',
				'shipping_address_2',
				'shipping_city',
				'shipping_state',
				'shipping_postcode',
				'shipping_country',
				'payment_method',
				'payment_method_title',
				'transaction_id',
				'customer_ip_address',
				'customer_user_agent',
				'created_via',
				'customer_note',
				'date_completed',
				'date_paid',
				'cart_hash',
			);
			return in_array($key, $order_props);
		}

		public function get_order_notes( $order, $filter = 'customer' ) {
			$order_id = WCX_Order::get_id( $order );
			if ( get_post_type( $order_id ) == 'shop_order_refund' && $parent_order_id = wp_get_post_parent_id( $order_id ) ) {
				$post_id = $parent_order_id;
			} else {
				$post_id = $order_id;
			}

			$args = array(
				'post_id' 	=> $post_id,
				'approve' 	=> 'approve',
				'type' 		=> 'order_note'
			);

			remove_filter( 'comments_clauses', array( 'WC_Comments', 'exclude_order_comments' ), 10, 1 );

			$notes = get_comments( $args );

			add_filter( 'comments_clauses', array( 'WC_Comments', 'exclude_order_comments' ), 10, 1 );

			if ( $notes ) {
				$formatted_notes = array();
				foreach( $notes as $key => $note ) {
					if ( $filter == 'customer' && !get_comment_meta( $note->comment_ID, 'is_customer_note', true ) ) {
						unset($notes[$key]);
						continue;
					}
					if ( $filter == 'private' && get_comment_meta( $note->comment_ID, 'is_customer_note', true ) ) {
						unset($notes[$key]);
						continue;
					}
					$note_classes   = array( 'note_content' );
					$note_classes[] = ( __( 'WooCommerce', 'woocommerce' ) === $note->comment_author ) ? 'system-note' : '';

					$formatted_notes[$key] = sprintf( '<div class="%s">%s</div>', esc_attr( implode( ' ', $note_classes ) ), wpautop( wptexturize( wp_kses_post( $note->comment_content ) ) ) );
				}
				return implode("\n", $formatted_notes);
			} else {
				return false;
			}
		}

		public function add_tax_base( $taxes, $order ) {
			$tax_rates_base = $this->get_tax_rates_base( $order );
			foreach ($taxes as $key => $tax) {
				if ( isset( $tax_rates_base[$tax['rate_id']] ) ) {
					$taxes[$key]['base'] = $tax_rates_base[$tax['rate_id']]->base;
					$taxes[$key]['calculated_rate'] = $tax_rates_base[$tax['rate_id']]->calculated_rate;
				}
			}

			return $taxes;
		}

		public function get_tax_rates_base( $order ) {
			// only works in WC2.2+
			if ( version_compare( WOOCOMMERCE_VERSION, '2.2', '<' ) ) {
				return $taxes;
			}

			// get tax totals from order and preset base
			$taxes = $this->get_tax_totals( $order );
			foreach ($taxes as $rate_id => $tax) {
				$tax->base = $tax->shipping_tax_amount = 0;
			}

			$hide_zero_tax = apply_filters( 'wpo_wcpdf_tax_rate_base_hide_zero', true );

			// get subtotals from regular line items and fees
			$items = $order->get_items( array( 'fee', 'line_item', 'shipping' ) );
			foreach ($items as $item_id => $item) {
				// get tax data
				if ( $item['type'] == 'shipping' ) {
					$line_taxes = maybe_unserialize( $item['taxes'] );
					// WC3.0 stores taxes as 'total' (like line items);
					if (isset($line_taxes['total'])) {
						$line_taxes = $line_taxes['total'];
					}
				} else {
					$line_tax_data = maybe_unserialize( $item['line_tax_data'] );
					$line_taxes = $line_tax_data['total'];
				}

				foreach ( $line_taxes as $rate_id => $tax ) {
					if ( isset( $taxes[$rate_id] ) ) {
						// convert tax to float, but only if numeric
						$tax = (is_numeric($tax)) ? (float) $tax : $tax;
						if ( $tax != 0 || ( $tax === 0.0 && $hide_zero_tax === false ) ) {
							$taxes[$rate_id]->base += ($item['type'] == 'shipping') ? $item['cost'] : $item['line_total'];
							if ($item['type'] == 'shipping') {
								$taxes[$rate_id]->shipping_tax_amount += $tax;
							}
						}
					}
				}
			}

			// add calculated rate
			foreach ($taxes as $rate_id => $tax) {
				$calculated_rate = $this->calculate_tax_rate( $tax->base, $tax->amount );
				if (function_exists('wc_get_price_decimal_separator')) {
					$tax_rate = str_replace('.', wc_get_price_decimal_separator(), strval($calculated_rate) );
				}
				$taxes[$rate_id]->calculated_rate = $calculated_rate;
			}

			return $taxes;
		}

		public function get_tax_totals( $order ) {
			$taxes = array();
			$merge_by_code = apply_filters( 'wpo_wcpdf_tax_rate_base_merge_by_code', false );
			if ($merge_by_code || version_compare( WOOCOMMERCE_VERSION, '3.0', '<' ) ) {
				// get taxes from WC
				$tax_totals = $order->get_tax_totals();
				// put taxes in new array with tax_id as key
				foreach ($tax_totals as $code => $tax) {
					$tax->code = $code;
					$taxes[$tax->rate_id] = $tax;
				}
			} else {
				// DON'T MERGE BY CODE
				foreach ( $order->get_items( 'tax' ) as $key => $tax ) {
					$code = $tax->get_rate_code();
					$rate_id = $tax->get_rate_id();

					if ( ! isset( $taxes[ $rate_id ] ) ) {
						$taxes[ $rate_id ] = new stdClass();
						$taxes[ $rate_id ]->amount = 0;
					}

					$taxes[ $rate_id ]->id                = $key;
					$taxes[ $rate_id ]->base              = 0;
					$taxes[ $rate_id ]->code              = $code;
					$taxes[ $rate_id ]->rate_id           = $rate_id;
					$taxes[ $rate_id ]->is_compound       = $tax->is_compound();
					$taxes[ $rate_id ]->label             = $tax->get_label();
					$taxes[ $rate_id ]->amount           += (float) $tax->get_tax_total() + (float) $tax->get_shipping_tax_total();
					$taxes[ $rate_id ]->formatted_amount  = wc_price( wc_round_tax_total( $taxes[ $rate_id ]->amount ), array( 'currency' => $order->get_currency() ) );
				}

				if ( apply_filters( 'woocommerce_order_hide_zero_taxes', true ) ) {
					$amounts = array_filter( wp_list_pluck( $taxes, 'amount' ) );
					$taxes   = array_intersect_key( $taxes, $amounts );
				}
			}
			return $taxes;
		}

		public function calculate_tax_rate( $price_ex_tax, $tax ) {
			$precision = apply_filters( 'wpo_wcpdf_calculate_tax_rate_precision', 1 );
			if ( $price_ex_tax != 0) {
				$tax_rate = round( ($tax / $price_ex_tax)*100, $precision ).' %';
			} else {
				$tax_rate = '-';
			}
			return $tax_rate;
		}

		public function save_regular_item_price( $order_id, $posted = array() ) {
			if ( $order = wc_get_order( $order_id ) ) {
				$items = $order->get_items();
				if (empty($items)) {
					return;
				}

				foreach ($items as $item_id => $item) {
					// this function will directly store the item price
					$regular_price = $this->get_regular_item_price( $item, $item_id, $order );
				}
			}
		}

		// get regular price from item - query product when not stored in item yet
		public function get_regular_item_price( $item, $item_id, $order ) {
			// first check if we alreay have stored the regular price of this item
			$regular_price = wc_get_order_item_meta( $item_id, '_wcpdf_regular_price', true );
			if (!empty($regular_price)) {
				return $regular_price;
			}

			$product = $order->get_product_from_item( $item );
			if ($product) {
				$product_regular_price = WCX_Product::get_prop( $product, 'regular_price', 'view' );
				// get different incarnations
				$regular_price = array(
					'incl'	=> WCX_Product::wc_get_price_including_tax( $product, 1, $product_regular_price ),
					'excl'	=> WCX_Product::wc_get_price_excluding_tax( $product, 1, $product_regular_price ),
				);
			} else {
				// fallback to item price
				$regular_price = array(
					'incl'	=> $order->get_line_subtotal( $item, true /* $inc_tax */, false ),
					'excl'	=> $order->get_line_subtotal( $item, false /* $inc_tax */, false ),
				);
			}

			wc_update_order_item_meta( $item_id, '_wcpdf_regular_price', $regular_price );
			return $regular_price;
		}

		public function get_discount_percentage( $order ) {
			if (method_exists($order, 'get_total_discount')) {
				// WC2.3 introduced an $ex_tax parameter
				$ex_tax = false;
				$discount = $order->get_total_discount( $ex_tax );
			} elseif (method_exists($order, 'get_discount_total')) {
				// was this ever included in a release?
				$discount = $order->get_discount_total();
			} else {
				return false;
			}

			$order_total = $order->get_total();

			// shipping and fees are not discounted
			$shipping_total = $order->get_total_shipping() + $order->get_shipping_tax();
			$fee_total = 0;
			if (method_exists($order, 'get_fees')) { // old versions of WC don't support fees
				foreach ( $order->get_fees() as $fees ) {
					$fee_total += $fees['line_total'] + $fees['line_tax'];
				}
			}

			$percentage = ( $discount / ( $order_total + $discount - $shipping_total - $fee_total) ) * 100;

			return round($percentage);
		}

		public function get_order_weight( $order, $add_unit = true ) {
			$items = $order->get_items();
			$weight = 0;
			if( sizeof( $items ) > 0 ) {
				foreach( $items as $item ) {
					$product = $order->get_product_from_item( $item );
					if ( !empty($product) && is_numeric($product->get_weight()) ) {
						$weight += $product->get_weight() * (int) $item['qty'];
					}
				}
			}
			if ( $add_unit == true ) {
				$weight .= get_option('woocommerce_weight_unit');
			}
			return $weight;
		}

		public function get_order_total_qty( $order ) {
			$items = $order->get_items();
			$total_qty = 0;
			if( sizeof( $items ) > 0 ) {
				foreach( $items as $item ) {
					$total_qty += $item['qty'];
				}
			}

			return $total_qty;
		}

		// hide regular price item eta
		public function hide_regular_price_itemmeta( $hidden_keys ) {
			$hidden_keys[] = '_wcpdf_regular_price';
			return $hidden_keys;
		}

		public function array_keys_prefix( $array, $prefix, $add_or_remove = 'add' ) {
			if (empty($array) || !is_array($array) ) {
				return $array;
			}

			foreach ($array as $key => $value) {
				if ( $add_or_remove == 'add' ) {
					$array[$prefix.$key] = $value;
					unset($array[$key]);
				} else { // remove
					$new_key = str_replace($prefix, '', $key);
					$array[$new_key] = $value;
					unset($array[$key]);
				}
			}

			return $array;

		}

		public function get_local_pickup_plus_pickup_details( $order ) {
			if ( function_exists('wc_local_pickup_plus') ) {
				ob_start();

				$local_pickup   = wc_local_pickup_plus();
				$orders_handler = $local_pickup->get_orders_instance();

				if ( $orders_handler && ( $pickup_data = $orders_handler->get_order_pickup_data( $order ) ) ) {
					$shipping_method = $local_pickup->get_shipping_method_instance();
					$package_number = 1;
					$packages_count = count( $pickup_data );
					?>

					<h3><?php echo esc_html( $shipping_method->get_method_title() ); ?></h3>

					<?php foreach ( $pickup_data as $pickup_meta ) : ?>

						<div>
							<?php if ( $packages_count > 1 ) : ?>
								<h5><?php echo sprintf( '%1$s #%2$s', esc_html( $shipping_method->get_method_title() ), $package_number ); ?></h5>
							<?php endif; ?>
							<ul>
								<?php foreach ( $pickup_meta as $label => $value ) : ?>
									<li>
										<strong><?php echo esc_html( $label ); ?>:</strong> <?php echo wp_kses_post( $value ); ?>
									</li>
								<?php endforeach; ?>
							</ul>
							<?php $package_number++; ?>
						</div>

					<?php endforeach; ?>
					<?php
					$order_pickup_data = ob_get_clean();
					return $order_pickup_data;				
				}
			}
		}

	} // end class
} // end class_exists

return new WooCommerce_PDF_IPS_Templates_Main();
