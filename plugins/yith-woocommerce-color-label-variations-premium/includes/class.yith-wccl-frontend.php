<?php
/**
 * Frontend class
 *
 * @author Yithemes
 * @package YITH WooCommerce Color and Label Variations Premium
 * @version 1.0.0
 */

if ( ! defined( 'YITH_WCCL' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'YITH_WCCL_Frontend' ) ) {
	/**
	 * Frontend class.
	 * The class manage all the frontend behaviors.
	 *
	 * @since 1.0.0
	 */
	class YITH_WCCL_Frontend {

		/**
		 * Single instance of the class
		 *
		 * @var \YITH_WCCL_Frontend
		 * @since 1.0.0
		 */
		protected static $instance;

		/**
		 * Plugin version
		 *
		 * @var string
		 * @since 1.0.0
		 */
		public $version = YITH_WCCL_VERSION;

		/**
		 * Returns single instance of the class
		 *
		 * @return \YITH_WCCL_Frontend
		 * @since 1.0.0
		 */
		public static function get_instance(){
			if( is_null( self::$instance ) ){
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor
		 *
		 * @access public
		 * @since 1.0.0
		 */
		public function __construct() {

			add_action( 'woocommerce_before_single_product', array( $this, 'create_attributes_json' ) );

			// enqueue scripts
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

			// add select options in loop
			add_filter( 'woocommerce_loop_add_to_cart_link', array( $this, 'add_select_options' ), 99, 2 );

			// add image shop_catalaog to available variation array
			add_filter( 'single_product_large_thumbnail_size', array( $this, 'set_shop_catalog_image' ) );

			// ajax add to cart
			add_action( 'wp_ajax_yith_wccl_add_to_cart', array( $this, 'yith_wccl_add_to_cart_ajax' ) );
			add_action( 'wp_ajax_nopriv_yith_wccl_add_to_cart', array( $this, 'yith_wccl_add_to_cart_ajax' ) );

			add_filter( 'woocommerce_available_variation', array( $this, 'loop_variations_attr' ), 99, 3 );

			add_action( 'woocommerce_before_single_product', array( $this, 'remove_scripts_gift_card' ) );
		}

		/**
		 * Dequeue scripts if product is gift card
		 *
		 * @since 1.0.7
		 * @author Francesco Licandro
		 */
		public function remove_scripts_gift_card(){
			global $product;

			if( is_product() && $product->product_type === 'gift-card' ){
				wp_dequeue_script( 'wc-add-to-cart-variation' );
				wp_dequeue_script( 'yith_wccl_frontend' );
				wp_dequeue_style( 'yith_wccl_frontend' );
			}
		}

		/**
		 * Enqueue scripts
		 *
		 * @since 1.0.0
		 * @author Francesco Licandro <francesco.licandro@yithemes.com>
		 */
		public function enqueue_scripts(){

			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

			wp_register_script( 'yith_wccl_frontend', YITH_WCCL_ASSETS_URL . '/js/yith-wccl'. $suffix .'.js', array( 'jquery', 'wc-add-to-cart-variation' ), $this->version, true );
			wp_register_style( 'yith_wccl_frontend', YITH_WCCL_ASSETS_URL . '/css/yith-wccl.css' , false, $this->version );

			wp_enqueue_script( 'wc-add-to-cart-variation' );
			wp_enqueue_script( 'yith_wccl_frontend' );
			wp_enqueue_style( 'yith_wccl_frontend' );


			wp_localize_script( 'yith_wccl_frontend', 'yith_wccl_general', array(
				'ajaxurl'       => admin_url( 'admin-ajax.php' ),
				'cart_redirect' => get_option( 'woocommerce_cart_redirect_after_add' ) == 'yes',
				'cart_url'      => WC()->cart->get_cart_url(),
				'view_cart'     => esc_attr__( 'View Cart', 'ywcl' ),
				'tooltip'       => get_option( 'yith-wccl-enable-tooltip' ) == 'yes',
				'tooltip_pos'   => get_option( 'yith-wccl-tooltip-position' ),
				'tooltip_ani'   => get_option( 'yith-wccl-tooltip-animation' ),
				'description'   => get_option( 'yith-wccl-enable-description' ) == 'yes',
				'add_cart'      => apply_filters( 'yith_wccl_add_to_cart_button_content', get_option( 'yith-wccl-add-to-cart-label' ) ),
				'grey_out'		=> get_option( 'yith-wccl-attributes-style' ) == 'grey',
				'outstock'		=> __( 'Out of stock', 'woocommerce' )
			) );

			$color      = get_option( 'yith-wccl-tooltip-text-color' );
			$background = get_option( 'yith-wccl-tooltip-background' );

			$inline_css = "
			.select_option .yith_wccl_tooltip > span {
                background: {$background};
                color: {$color};
            }
            .select_option .yith_wccl_tooltip.bottom span:after {
                border-bottom-color: {$background};
            }
            .select_option .yith_wccl_tooltip.top span:after {
                border-top-color: {$background};
            }";

			wp_add_inline_style( 'yith_wccl_frontend', $inline_css );
		}

		/**
		 * Add select options to loop
		 *
		 * @since 1.0.0
		 * @param $html
		 * @param $product
		 * @return mixed
		 * @author Francesco Licandro <francesco.licandro@yithemes.com>
		 */
		public function add_select_options( $html = '', $product = false ){

			if( ! $product )
				global $product;

			if( ( isset( $product ) && get_post_type( $product ) && $product->product_type != 'variable' )
			    || get_option( 'yith-wccl-enable-in-loop' ) != 'yes'
			    || ( isset( $_REQUEST[ 'action' ] ) && $_REQUEST['action'] = 'yith-woocompare-view-table' ) ) {
				return $html;
			}

			$attributes = $product->get_variation_attributes();

			// form position
			$position = get_option( 'yith-wccl-position-in-loop' );
			$new_html = $inputbox = '';

			if( class_exists( 'WooCommerce_Thumbnail_Input_Quantity' ) ) {
				$incremental = new WooCommerce_Thumbnail_Input_Quantity();
				$inputbox = $incremental->print_input_box(null);
			}

			ob_start();

			wc_get_template( 'yith-wccl-variable-loop.php', array(
				'product'               => $product,
				'available_variations'  => $product->get_available_variations(),
				'attributes'   			=> $attributes,
				'selected_attributes' 	=> $product->get_variation_default_attributes(),
				'attributes_types'      => $this->get_variation_attributes_types( $attributes )
			), '', YITH_WCCL_DIR . 'templates/' );


			$form = ob_get_clean();

			switch( $position ) {
				case 'before':
					$new_html = $inputbox . $form . $html;
					break;
				case 'after':
					$new_html = $inputbox . $html . $form;
					break;
			}

			return apply_filters( 'yith_wccl_html_form_in_loop', $new_html );
		}

		/**
		 * Print select option in loop
		 *
		 * @since 1.0.0
		 * @author Francesco Licandro <francesco.licandro@yithemes.com>
		 */
		public function print_select_options(){
			echo $this->add_select_options();
		}

		/**
		 * Get an array of types and values for each attribute
		 *
		 * @access public
		 * @since 1.0.0
		 */
		public function get_variation_attributes_types( $attributes ) {
			global $wpdb;
			$types = array();
			$defined_attr = YITH_WCCL_Admin()->_custom_tax;

			if( ! empty( $attributes ) ) {
				foreach( $attributes as $name => $options ) {

					$attribute_name = str_replace( 'pa_', '', $name );

					$attribute = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "woocommerce_attribute_taxonomies WHERE attribute_name = '$attribute_name'");

					if ( isset( $attribute ) && array_key_exists( $attribute->attribute_type, $defined_attr ) ) {
						$types[$name] = $attribute->attribute_type;
					}
				}
			}

			return $types;
		}

		/**
		 * Create custom attribute json
		 *
		 * @since 1.0.0
		 * @author Francesco Licandro <francesco.licandro@yithemes.com>
		 */
		public function create_attributes_json( $product_id = false, $return = false ) {

			if( ! $product_id ) {
				global $product;
			}
			else {
				$product = wc_get_product( $product_id );
			}

			if( $product->product_type != 'variable' ){
				return false;
			}

			$attr     = array();
			// gel all attribute taxonomy
			$taxonomies = wc_get_attribute_taxonomies();
			// get attributes for current product
			$attributes = $product->get_variation_attributes();

			foreach( $taxonomies as $tax ) {
				// get custom tax type
				$custom_tax = YITH_WCCL_Admin()->_custom_tax;

				// get terms
				$terms = wc_get_product_terms( $product->id, wc_attribute_taxonomy_name( $tax->attribute_name ), array( 'fields' => 'all' ) );

				if( empty( $terms ) ) {
					continue;
				}

				// check is tax is valid
				if( ! array_key_exists( wc_attribute_taxonomy_name( $tax->attribute_name ), $attributes ) || ! array_key_exists( $tax->attribute_type, $custom_tax ) ) {
					continue;
				}

				// else add type and description
				$attr[ 'attribute_' . wc_attribute_taxonomy_name( $tax->attribute_name ) ] = array(
					'type'      => $tax->attribute_type,
					'descr'     => $this->get_attribute_taxonomy_descr( $tax->attribute_id )
				);

				foreach ( $terms as $term ) {
					// get value of attr
					$value   = get_woocommerce_term_meta( $term->term_id, wc_attribute_taxonomy_name( $tax->attribute_name ) . '_yith_wccl_value');
					$tooltip = get_woocommerce_term_meta( $term->term_id, wc_attribute_taxonomy_name( $tax->attribute_name ) . '_yith_wccl_tooltip');

					$attr[ 'attribute_' . wc_attribute_taxonomy_name( $tax->attribute_name ) ]['terms'][ $term->slug ] = array( 'value' => $value, 'tooltip' => $tooltip );
				}
			}

			if( ! $return ) {
				wp_localize_script( 'yith_wccl_frontend', 'yith_wccl', array(
					'attributes'    => json_encode( $attr )
				));
			}
			else {
				return $attr;
			}

		}

		/**
		 * Get product attribute taxonomy description for table yith_wccl_meta
		 *
		 * @since  1.0.0
		 * @param integer $id
		 * @return null|string
		 * @author Francesco Licandro <francesco.licandro@yithemes.com>
		 */
		public function get_attribute_taxonomy_descr( $id ) {

			global $wpdb;

			$meta_value = $wpdb->get_var( $wpdb->prepare( "SELECT meta_value FROM " . $wpdb->prefix . "yith_wccl_meta WHERE wc_attribute_tax_id = %d", $id ) );

			return isset( $meta_value ) ? $meta_value : '';
		}

		/**
		 * Set shop catalaog image to available variation array
		 *
		 * @since 1.0.0
		 * @return string
		 * @author Francesco Licandro <francesco.licandro@yithemes.com>
		 */
		public function set_shop_catalog_image() {

			if( is_product() ) {
				return 'shop_single';
			}

			return 'shop_catalog';
		}

		/**
		 * Add to cart in ajax
		 *
		 * @since 1.0.0
		 * @author Francesco Licandro <francesco.licandro@yithemes.com>
		 */
		public function yith_wccl_add_to_cart_ajax(){

			if( ! isset( $_REQUEST['product_id'] ) || ! isset( $_REQUEST['variation_id'] ) ) {
				die();
			}

			$product_id = intval( $_REQUEST['product_id'] );
			$variation_id = intval( $_REQUEST['variation_id'] );
			$quantity = isset( $_REQUEST['quantity'] ) ? $_REQUEST['quantity'] : 1;

			parse_str( $_REQUEST['attr'], $attributes );

			// get product status
			$product_status    = get_post_status( $product_id );

			if( empty( $attributes ) )
				die();


			if( WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $attributes ) && 'publish' === $product_status ) {

				do_action( 'woocommerce_ajax_added_to_cart', $product_id );

				if ( get_option( 'woocommerce_cart_redirect_after_add' ) == 'yes' ) {
					wc_add_to_cart_message( $product_id );
				}
				// Fragments and mini cart are returned
				WC_AJAX::get_refreshed_fragments();
			}
			else {

				// If there was an error adding to the cart, redirect to the product page to show any errors
				$data = array(
					'error'       => true,
					'product_url' => apply_filters( 'woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id )
				);
			}

			wp_send_json( $data );

			die();

		}

		/**
		 * Filter loop attributes for variation form
		 *
		 * @since 1.0.6
		 * @param array $attr
		 * @param object $product
		 * @param object $variation
		 * @return array
		 * @author Francesco Licandro
		 */
		public function loop_variations_attr( $attr, $product, $variation ){

			if( ! is_shop() || class_exists( 'JCKWooThumbs' ) ) {
				return $attr;
			}

			$image = $image_srcset = $image_sizes = '';

			if ( has_post_thumbnail( $variation->get_variation_id() ) ) {
				$attachment_id   = get_post_thumbnail_id( $variation->get_variation_id() );
				$attachment      = wp_get_attachment_image_src( $attachment_id, 'shop_catalog' );
				$image           = $attachment ? current( $attachment ) : '';
				$image_srcset    = function_exists( 'wp_get_attachment_image_srcset' ) ? wp_get_attachment_image_srcset( $attachment_id, 'shop_catalog' ) : '';
				$image_sizes     = function_exists( 'wp_get_attachment_image_sizes' ) ? wp_get_attachment_image_sizes( $attachment_id, 'shop_catalog' ) : '';
			}

			$attr['image_src'] 		= $image;
			$attr['image_srcset'] 	= $image_srcset;
			$attr['image_sizes'] 	= $image_sizes;

			return $attr;
		}

	}
}
/**
 * Unique access to instance of YITH_WCCL_Frontend class
 *
 * @return \YITH_WCCL_Frontend
 * @since 1.0.0
 */
function YITH_WCCL_Frontend(){
	return YITH_WCCL_Frontend::get_instance();
}