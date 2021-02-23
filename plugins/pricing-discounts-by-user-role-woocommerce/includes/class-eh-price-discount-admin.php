<?php
class Class_Eh_Price_Discount_Admin {
	
	public function __construct() {
		
		add_action('woocommerce_before_shop_loop_item_title', array($this,'remove_shop_add_to_cart_option'));//function to remove add to cart at shop
		add_action('woocommerce_before_single_product_summary', array($this,'remove_product_add_to_cart_option'));//function to remove add to cart at product
		
		if(WC()->version < '2.7.0'){
			add_filter( 'woocommerce_get_regular_price', array( $this, 'get_regular_price') , 99, 2 );//function to modify product regular price
			add_filter( 'woocommerce_get_sale_price', array( $this, 'get_selling_price') , 99, 2 );//function to modify product sale price
			add_filter('woocommerce_get_price', array(&$this,'get_price'), 11, 2);//function to modify product price at all level
		}
		else{
			add_filter( 'woocommerce_product_get_regular_price', array( $this, 'get_regular_price') , 99, 2 );
            add_filter( 'woocommerce_product_get_sale_price', array( $this, 'get_selling_price') , 99, 2 );
            add_filter( 'woocommerce_product_get_price', array( &$this, 'get_price' ), 11, 2 );
            
            
            add_filter( 'woocommerce_product_variation_get_regular_price', array( $this, 'get_regular_price') , 99, 2 );
            add_filter( 'woocommerce_product_variation_get_sale_price', array( $this, 'get_selling_price') , 99, 2 );
            add_filter( 'woocommerce_product_variation_get_price', array( &$this, 'get_price' ), 11, 2 );
		}
		add_filter( 'woocommerce_get_variation_regular_price', array( $this, 'get_variation_regular_price' ), 99, 4 );
		add_filter( 'woocommerce_get_variation_price', array( $this, 'get_variation_price' ), 99, 4 );
		
		add_filter( 'woocommerce_get_price_html',array( &$this,'get_price_html' ),10,2);
		$this->init_fields();
	}

	
	public function init_fields()
	{
		$this->hide_regular_price =  get_option('eh_pricing_discount_hide_regular_price') == 'yes' ? false : true;
		$this->role_price_adjustment = get_option('eh_pricing_discount_price_adjustment_options');
		$this->current_user_role = $this->get_priority_user_role(wp_get_current_user()->roles, $this->role_price_adjustment);
		$this->enable_role_tax = get_option('eh_pricing_discount_enable_tax_options') == 'yes' ? true : false;
		$this->role_tax_option = get_option('eh_pricing_discount_price_tax_options');
		$this->tax_user_role = $this->get_priority_user_role(wp_get_current_user()->roles, $this->role_tax_option);
		$this->price_suffix_option = get_option('eh_pricing_discount_enable_price_suffix') != '' ? get_option('eh_pricing_discount_enable_price_suffix') : 'none';
		$this->general_price_suffix = get_option('eh_pricing_discount_price_general_price_suffix');
		$this->role_price_suffix = get_option('eh_pricing_discount_role_price_suffix');
		$this->suffix_user_role = $this->get_priority_user_role(wp_get_current_user()->roles, $this->role_price_suffix);
		$this->price_suffix_user_role = $this->suffix_user_role != '' ? $this->suffix_user_role : 'unregistered_user';
		$this->replace_add_to_cart = get_option('eh_pricing_discount_replace_cart_unregistered_user') == 'yes' ? true : false;
		$this->replace_add_to_cart_button_text = get_option('eh_pricing_discount_replace_cart_unregistered_user_text');
		$this->replace_add_to_cart_button_url = get_option('eh_pricing_discount_replace_cart_unregistered_user_url');
		$this->replace_add_to_cart_user_role = get_option('eh_pricing_discount_replace_cart_user_role');
		$this->replace_add_to_cart_user_role_button_text = get_option('eh_pricing_discount_replace_cart_user_role_text');
		$this->replace_add_to_cart_user_role_url = get_option('eh_pricing_discount_replace_cart_user_role_url');
		
		$separator = stripslashes( get_option( 'woocommerce_price_decimal_sep' ) );
		$this->decimal_separator = $separator ? $separator : '.';

		$this->sales_method = get_option('eh_product_choose_sale_regular');
	}
	
	//function to modify product price at all level
	public function get_price ($price, $product) 
	{
		if(WC()->version < '2.7.0'){
			$temp_data = $product->product_type;
			$temp_regular_price = $product->regular_price;
		}else{
			$temp_data = $product->get_type();
			$temp_regular_price = $product->get_regular_price();
		}

		if($temp_data === 'simple' || $temp_data === 'variation') {
			if($price > 0) {
				$sale_price = $product->get_sale_price();

				$regular_price;
				if($temp_data === 'simple' ) {
					$regular_price = ( $this->get_regular_price( $temp_regular_price, $product ) != '') ? $this->get_regular_price( $temp_regular_price, $product ) : $temp_regular_price;
				}
				if($temp_data === 'variation') {
					$regular_price = $product->get_regular_price('max', false);
				}

				
				$wcrbp_price = ( $sale_price != '' && $sale_price > 0 )? $sale_price : $regular_price;
				//thilak
				$wcrbp_price = wc_format_decimal($wcrbp_price);
				//thilak

				$wcrbp_price =  $this->modify_shop_product_price($wcrbp_price, $product);
                                
				//to remove tax based on user role
				if($this->enable_role_tax && is_array($this->role_tax_option) && key_exists($this->tax_user_role,$this->role_tax_option) && ($this->role_tax_option[$this->tax_user_role]['tax_option'] != 'default')) {
					if(WC()->version < '2.7.0'){	
						remove_filter('woocommerce_get_price', array(&$this,'get_price'), 11, 2);
						$new_price = $this->calculate_taxed_price($qty = 1, $wcrbp_price, $product);
						if($new_price != '') {
							$wcrbp_price = $new_price;
						}
						add_filter('woocommerce_get_price', array(&$this,'get_price'), 11, 2);
					}
					else
					{
						remove_filter('woocommerce_product_get_price', array(&$this,'get_price'), 11, 2);
						remove_filter( 'woocommerce_product_variation_get_price', array( &$this, 'get_price' ), 11, 2 );
						$new_price = $this->calculate_taxed_price($qty = 1, $wcrbp_price, $product);
						if($new_price != '') {
							$wcrbp_price = $new_price;
						}
						add_filter('woocommerce_product_get_price', array(&$this,'get_price'), 11, 2);
						add_filter( 'woocommerce_product_variation_get_price', array( &$this, 'get_price' ), 11, 2 );
					}
				}
				else
				{
					if($this->enable_role_tax && is_array($this->role_tax_option) && key_exists($this->tax_user_role,$this->role_tax_option) && ($this->role_tax_option[$this->tax_user_role]['tax_calsses'] != 'default'))
					{
						(WC()->version < '2.7.0') ? add_filter( 'woocommerce_product_tax_class', array($this,'eh_diff_rate_for_user'), 1, 2 ) : add_filter( 'woocommerce_product_get_tax_class', array($this,'eh_diff_rate_for_user'), 1, 2 ) ;
					}
				}
				$price = wc_format_decimal($wcrbp_price);
			}
		}
                return (string)$price;
	}
	
	//function to modify product regular price
	public function get_regular_price($price, $product) {
		if(WC()->version < '2.7.0'){
			remove_filter( 'woocommerce_get_regular_price', array( $this, 'get_regular_price') , 99, 2 );//function to modify product regular price
			remove_filter('woocommerce_get_price', array(&$this,'get_price'), 11, 2);//function to modify product price at all level
		}
		else{
			remove_filter( 'woocommerce_product_get_regular_price', array( $this, 'get_regular_price') , 99, 2 );//function to modify product regular price
			remove_filter('woocommerce_product_get_price', array(&$this,'get_price'), 11, 2);//function to modify product price at all level
			remove_filter( 'woocommerce_product_variation_get_regular_price', array( $this, 'get_regular_price') , 99, 2 );
            remove_filter( 'woocommerce_product_variation_get_price', array( &$this, 'get_price' ), 11, 2 );
		}

		if(WC()->version < '2.7.0'){
			$temp_data = $product->product_type;
			$temp_variation_id = $product->variation_id;
			$temp_post_id = $product->post->ID;
			$temp_sale_price = $product->sale_price;
		}else{
			$temp_data = $product->get_type();
			$temp_variation_id = $product->get_id();
			$temp_post_id = $product->get_id();
			$temp_sale_price = $product->get_sale_price();
		}
		if($temp_data === 'variation' || $temp_data === 'variable') {
			$price = $this->calculate_regular_price($price, $temp_variation_id, $temp_data,$product);
		}
		if($temp_data === 'simple') {
			$price = $this->calculate_regular_price($price, $temp_post_id, $temp_data,$product);
		}
		if($temp_sale_price > $price) { 
			$price = $temp_sale_price;
		}
		if( 'Discount' != get_option('eh_pricing_product_price_markup_discount') && ($product->get_sale_price() != '' ) ) {
			$price = $product->get_sale_price();
		}
		if(!($this->hide_regular_price) && ($product->get_sale_price() !='')) {
			$price = $product->get_sale_price(); 
		}
		if(WC()->version < '2.7.0'){
			add_filter( 'woocommerce_get_regular_price', array( $this, 'get_regular_price') , 99, 2 );//function to modify product regular price
			add_filter('woocommerce_get_price', array(&$this,'get_price'), 11, 2);//function to modify product price at all level
		}
		else{
			add_filter( 'woocommerce_product_get_regular_price', array( $this, 'get_regular_price') , 99, 2 );//function to modify product regular price
			add_filter('woocommerce_product_get_price', array(&$this,'get_price'), 11, 2);//function to modify product price at all level
			add_filter( 'woocommerce_product_variation_get_regular_price', array( $this, 'get_regular_price') , 99, 2 );
            add_filter( 'woocommerce_product_variation_get_price', array( &$this, 'get_price' ), 11, 2 );
		}
		return wc_format_decimal($price);
	}

	//function to modify product sale price
	public function get_selling_price($price, $product) {
		if(WC()->version < '2.7.0'){
			remove_filter( 'woocommerce_get_sale_price', array( $this, 'get_selling_price') , 99, 2 );//function to modify product sale price
			remove_filter('woocommerce_get_price', array(&$this,'get_price'), 11, 2);//function to modify product price at all level
		}
		else{
			remove_filter( 'woocommerce_product_get_sale_price', array( $this, 'get_selling_price') , 99, 2 );//function to modify product sale price
			remove_filter('woocommerce_product_get_price', array(&$this,'get_price'), 11, 2);//function to modify product price at all level
			remove_filter( 'woocommerce_product_variation_get_sale_price', array( $this, 'get_selling_price') , 99, 2 );
            remove_filter( 'woocommerce_product_variation_get_price', array( &$this, 'get_price' ), 11, 2 );
		}
		if(WC()->version < '2.7.0'){
			$temp_data = $product->product_type;
			$temp_post_id = $product->id;
			$temp_regular_price = $product->regular_price;
			$temp_variation_id = $product->variation_id;
		}else{
			$temp_data = $product->get_type();
			$temp_post_id = $product->get_id();
			$temp_regular_price = $product->get_regular_price();
			$temp_variation_id = $product->get_id();
		}
		if( 'Makup' != get_option('eh_pricing_product_price_markup_discount') ) {
			if(is_array($this->role_price_adjustment) && key_exists($this->current_user_role,$this->role_price_adjustment)) {
				if(($this->role_price_adjustment[$this->current_user_role]['adjustment_price'] !='' || $this->role_price_adjustment[$this->current_user_role]['adjustment_percent'] !='')  )
				{
					if(key_exists('role_price',$this->role_price_adjustment[$this->current_user_role]))
					{
						if($this->sales_method === 'regular')
						{
							if($temp_data === 'variation' || $temp_data === 'variable')
							{

								$price = $product->get_regular_price();

							}
							else
							{
								$price = $temp_regular_price;
							}
						}
					}
					
				}
			}
		}
		if ($price == '') {
			$price = $temp_regular_price;
		}

		if(is_user_logged_in() && strrpos($price, '.')) {
			$decimal_points =(int) (get_option('eh_pricing_discount_decimal_points') !='' ? get_option('eh_pricing_discount_decimal_points') : '2');
                        //$zz = $price.'|'.strlen($price).'|'.strrpos($price, '.').'|'.$decimal_points;
			$price_precision = strlen($price) - strrpos($price, '.') - $decimal_points+1;
			
		} else {
			$price_precision = false;
		} 
		$settings_price_adjustment = get_option('eh_pricing_discount_product_price_user_role');
		
		if(($temp_data === 'simple') || ($temp_data === 'variation') || ($temp_data === 'variable')) {
			if(is_array($settings_price_adjustment) && in_array($this->current_user_role,$settings_price_adjustment) && get_post_meta( $temp_post_id, 'product_based_price_adjustment', true ) === 'yes' ) {

				if($temp_data === 'variation' || $temp_data === 'variable') {

					$price = wc_format_decimal($this->add_user_shop_product_price_settings( $price, $temp_variation_id, $product), $price_precision);
				} else {

					$price = wc_format_decimal($this->add_user_shop_product_price_settings( $price, $temp_post_id, $product), $price_precision);
				}
			} else {
				if($temp_data === 'variation'  || $temp_data === 'variable') {
						$price = wc_format_decimal($this->add_user_shop_general_price_settings( $price, $temp_variation_id ), $price_precision);
				} else {
					$price = wc_format_decimal($this->add_user_shop_general_price_settings( $price, $temp_post_id ), $price_precision);
                                        //$price = wc_format_decimal($this->add_user_shop_general_price_settings( $price, $temp_post_id ), 0);
				}
			}
		}
		if(WC()->version < '2.7.0'){
			add_filter( 'woocommerce_get_sale_price', array( $this, 'get_selling_price') , 99, 2 );//function to modify product sale price
			add_filter('woocommerce_get_price', array(&$this,'get_price'), 11, 2);//function to modify product price at all level
		}
		else{
			add_filter( 'woocommerce_product_get_sale_price', array( $this, 'get_selling_price') , 99, 2 );//function to modify product sale price
			add_filter('woocommerce_product_get_price', array(&$this,'get_price'), 11, 2);//function to modify product price at all level
			add_filter( 'woocommerce_product_variation_get_sale_price', array( $this, 'get_selling_price') , 99, 2 );
            add_filter( 'woocommerce_product_variation_get_price', array( &$this, 'get_price' ), 11, 2 );
		}

		return (string)$price;
	}

	//function to calculate regular price
	public function calculate_regular_price($price, $id, $type, $product) {
		global $product;

		if(($type == 'simple' || $type == 'variation' || $type == 'variable') && $product) {
			if((is_user_logged_in ())) {
				$price = $this->calculate_taxed_price($qty = 1, $price, $product);
			}
		}
		
		return $price;
	}
	
	//function to remove add to cart option for guest user in shop
	public function remove_shop_add_to_cart_option() {
		global $product;
		if(WC()->version < '2.7.0'){
			$temp_data = $product->product_type;
			$temp_post_id = $product->post->ID;
		}else{
			$temp_data = $product->get_type();
			$temp_post_id = $product->get_id();
		}
		if(($temp_data === 'simple' || $temp_data === 'variable')) {
			if(('yes' == get_option('eh_pricing_discount_cart_unregistered_user')) || ('yes' == (get_post_meta($temp_post_id, 'product_adjustment_hide_addtocart_unregistered', true)))) {
				if(!(is_user_logged_in ())) {
					remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
				}
			}
			$this->remove_shop_add_to_cart_user();
		}
	}
	
	//function to replace add to cart with text content for unregistered user and user role in shop
	function add_to_cart_text_content($product)
	{

		if(WC()->version < '2.7.0'){
			$temp_data = $product->product_type;
			$temp_post_id = $product->post->ID;
		}else{
			$temp_data = $product->get_type();
			$temp_post_id = $product->get_id();
		}
		$cart_text_content = '';

		if(($temp_data === 'simple' || $temp_data === 'variable')) {
			if(('yes' == get_option('eh_pricing_discount_cart_unregistered_user')) || ('yes' == (get_post_meta($temp_post_id, 'product_adjustment_hide_addtocart_unregistered', true)))) {
				if(!(is_user_logged_in ())) {
					if($this->replace_add_to_cart) {
						if($this->replace_add_to_cart_button_text !='')
						{
							$cart_text_content = '<a href="'.$this->replace_add_to_cart_button_url.'" class="button alt">'.$this->replace_add_to_cart_button_text.'</a>';
						}
					} else {
						$unregistered_user_cart_text = get_option('eh_pricing_discount_cart_unregistered_user_text');
						if( $unregistered_user_cart_text != '') {
							$cart_text_content = $unregistered_user_cart_text;
						}
					}
					remove_action( 'woocommerce_after_shop_loop_item', 'add_to_cart_text_content', 10 );
				}
			}
			if((is_user_logged_in ())) {
				$remove_settings_cart_roles = get_option('eh_pricing_discount_cart_user_role');
				$remove_product_cart_roles = get_post_meta( $temp_post_id, 'eh_pricing_adjustment_product_addtocart_user_role', false ) == '' ? array() : get_post_meta( $temp_post_id, 'eh_pricing_adjustment_product_addtocart_user_role', false ) ;
				$user_role_cart_text = get_option('eh_pricing_discount_cart_user_role_text');
				if(((is_array( $remove_settings_cart_roles ) && in_array($this->current_user_role, $remove_settings_cart_roles )) || (is_array(current($remove_product_cart_roles)) && (in_array($this->current_user_role,current($remove_product_cart_roles))))) && 
					$user_role_cart_text !='' ) {
					if(is_array($this->replace_add_to_cart_user_role) && in_array($this->current_user_role,$this->replace_add_to_cart_user_role) && $this->replace_add_to_cart_user_role_button_text != '') {
						$cart_text_content = '<a href="'.$this->replace_add_to_cart_user_role_url.'" class="button alt">'.$this->replace_add_to_cart_user_role_button_text.'</a>';
						remove_action( 'woocommerce_after_shop_loop_item', 'add_to_cart_text_content', 10 );
					} else {
						$cart_text_content = $user_role_cart_text;
						remove_action( 'woocommerce_after_shop_loop_item', 'add_to_cart_text_content', 10 );
					}
				}
				
			}
		}
		return $cart_text_content;
	}

	function add_to_cart_single_product_text_content($product)
	{

		if(WC()->version < '2.7.0'){
			$temp_data = $product->product_type;
			$temp_post_id = $product->post->ID;
		}else{
			$temp_data = $product->get_type();
			$temp_post_id = $product->get_id();
		}

		$cart_text_content = '';
		if(($temp_data === 'simple' || $temp_data === 'variable')) {
			if(('yes' == get_option('eh_pricing_discount_cart_unregistered_user')) || ('yes' == (get_post_meta($temp_post_id, 'product_adjustment_hide_addtocart_unregistered', true)))) {
				if(!(is_user_logged_in ())) {
					if($this->replace_add_to_cart) {
						if($this->replace_add_to_cart_button_text !='')
						{
							$cart_text_content = '<a href="'.$this->replace_add_to_cart_button_url.'" class="button alt">'.$this->replace_add_to_cart_button_text.'</a>';
						}
					} else {
						$unregistered_user_cart_text = get_option('eh_pricing_discount_cart_unregistered_user_text');
						if( $unregistered_user_cart_text != '') {
							$cart_text_content = $unregistered_user_cart_text;
						}
					}
					remove_action( 'woocommerce_after_single_product_summary', 'add_to_cart_text_content', 10 );
				}
			}
			if((is_user_logged_in ())) {

				$remove_settings_cart_roles = get_option('eh_pricing_discount_cart_user_role');
				$remove_product_cart_roles = get_post_meta( $temp_post_id, 'eh_pricing_adjustment_product_addtocart_user_role', false ) == ''? array() : get_post_meta( $temp_post_id, 'eh_pricing_adjustment_product_addtocart_user_role', false ) ;
				
				$user_role_cart_text = get_option('eh_pricing_discount_cart_user_role_text');

				
				if(((is_array( $remove_settings_cart_roles ) && in_array($this->current_user_role, $remove_settings_cart_roles )) || 
					(is_array(current($remove_product_cart_roles)) && (in_array($this->current_user_role,current($remove_product_cart_roles))))) && 
					$user_role_cart_text !='' ) {

					if(is_array($this->replace_add_to_cart_user_role) && in_array($this->current_user_role,$this->replace_add_to_cart_user_role) && $this->replace_add_to_cart_user_role_button_text != '') {
						$cart_text_content = '<a href="'.$this->replace_add_to_cart_user_role_url.'" class="button alt">'.$this->replace_add_to_cart_user_role_button_text.'</a>';
						remove_action( 'woocommerce_after_single_product_summary', 'add_to_cart_text_content', 10 );
					} else {
						$cart_text_content = $user_role_cart_text;
						remove_action( 'woocommerce_after_single_product_summary', 'add_to_cart_text_content', 10 );
					}
				}
			}
		}
		return $cart_text_content;
	}
	
	//function to remove add to cart option for selected user roles in shop
	public function remove_shop_add_to_cart_user() {
		global $product;
		if(WC()->version < '2.7.0'){
			$temp_data = $product->product_type;
			$temp_post_id = $product->post->ID;
		}else{
			$temp_data = $product->get_type();
			$temp_post_id = $product->get_id();
		}

		if(($temp_data === 'simple' || $temp_data === 'variable')) {
			if((is_user_logged_in ())) {
				$remove_cart_roles = get_option('eh_pricing_discount_cart_user_role');
				$remove_product_cart_roles = get_post_meta( $temp_post_id, 'eh_pricing_adjustment_product_addtocart_user_role', false ) == '' ? array() : get_post_meta( $temp_post_id, 'eh_pricing_adjustment_product_addtocart_user_role', false ) ;
				if((is_array( $remove_cart_roles ) && in_array($this->current_user_role,$remove_cart_roles))|| 
					(is_array(current($remove_product_cart_roles)) && (in_array($this->current_user_role,current($remove_product_cart_roles))))) {
					remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
			}
		}
	}
}

	//function to remove add to cart option for guest user in individual product page
public function remove_product_add_to_cart_option() {
	global $product;
	if(WC()->version < '2.7.0'){
		$temp_data = $product->product_type;
		$temp_post_id = $product->post->ID;
	}else{
		$temp_data = $product->get_type();
		$temp_post_id = $product->get_id();
	}
	if(($temp_data === 'simple' || $temp_data === 'variable')) {
		if(!(is_user_logged_in ())) {
			if(('yes' == get_option('eh_pricing_discount_cart_unregistered_user')) || ('yes' == (get_post_meta($temp_post_id, 'product_adjustment_hide_addtocart_unregistered', true)))) {
				remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
			}
		} else {
			$this->remove_product_add_to_cart_user();
		}
	}
}

	//function to remove add to cart option for selected user roles in individual product page
public function remove_product_add_to_cart_user() {
	global $product;
	if(WC()->version < '2.7.0'){
		$temp_data = $product->product_type;
		$temp_post_id = $product->post->ID;
	}else{
		$temp_data = $product->get_type();
		$temp_post_id = $product->get_id();
	}
	if(($temp_data === 'simple' || $temp_data === 'variable')) {
		$remove_cart_roles = get_option('eh_pricing_discount_cart_user_role');
		$remove_product_cart_roles = get_post_meta( $temp_post_id, 'eh_pricing_adjustment_product_addtocart_user_role', false ) == '' ? array() : get_post_meta( $temp_post_id, 'eh_pricing_adjustment_product_addtocart_user_role', false ) ;
		if((is_array($remove_cart_roles) && in_array($this->current_user_role,$remove_cart_roles)) || 
			(is_array(current($remove_product_cart_roles)) && (in_array($this->current_user_role,current($remove_product_cart_roles))))) {
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
	}
}
}

public function modify_shop_product_price($price, $product)	{
	if(WC()->version < '2.7.0'){
		$temp_post_id = $product->post->ID;
	}else{
		$temp_post_id = $product->get_id();
	}
	$remove_add_to_cart = false;
	$unregistered_user_price_text = get_option('eh_pricing_discount_price_unregistered_user_text');
		//to remove price for guest user based on general or product settings
	if( ( 'yes' == get_option('eh_pricing_discount_price_unregistered_user')) || ( 'yes' == ( get_post_meta( $temp_post_id, 'product_adjustment_hide_price_unregistered', true ) ) ) ){
		if(!(is_user_logged_in ())) {
			if( $unregistered_user_price_text != '') {
				$price = $unregistered_user_price_text;
			} else {
				$price = '';
			}
			$remove_add_to_cart = true;
		}
	}

		//to remove price for specific selected user roles
	if((is_user_logged_in ())) {
		$remove_settings_price_roles = get_option('eh_pricing_discount_price_user_role');
		$remove_product_price_roles = get_post_meta( $temp_post_id, 'eh_pricing_adjustment_product_price_user_role', false ) == '' ? array() : get_post_meta( $temp_post_id, 'eh_pricing_adjustment_product_price_user_role', false ) ;
		$user_role_cart_text = get_option('eh_pricing_discount_price_user_role_text');
		if( is_array( $remove_settings_price_roles ) ) {
			if( ( in_array($this->current_user_role,$remove_settings_price_roles ) ) ) {
				if( $user_role_cart_text != '') {
					$price = $user_role_cart_text;
				} else {
					$price = '';
				}
				$remove_add_to_cart = true;
			}
		}
		if(is_array(current($remove_product_price_roles)) && (in_array($this->current_user_role,current($remove_product_price_roles)))) {
			if( $user_role_cart_text != '') {
				$price = $user_role_cart_text;
			} else {
				$price = '';
			}
			$remove_add_to_cart = true;
		}
	}
	return $price;
}

	//function to add adjustment for price over price from settings
public function add_user_shop_general_price_settings( $price, $id ){
	global $product;
	if((is_user_logged_in ())) {
		$product_user_price = get_post_meta( $id, 'product_role_based_price', false ) == '' ? array() : get_post_meta( $id, 'product_role_based_price', false ) ;
		$new_price = 0;
		if(is_array($this->role_price_adjustment) && key_exists($this->current_user_role,$this->role_price_adjustment)) {
			if( is_array(current($product_user_price)) && key_exists('role_price',$this->role_price_adjustment[$this->current_user_role])) {
				if(key_exists($this->current_user_role ,current($product_user_price)) && (current($product_user_price)[$this->current_user_role]['role_price'] !='') ) {
					$price = current($product_user_price)[$this->current_user_role]['role_price'];
				}
			}
			$price = str_replace($this->decimal_separator, '.', $price);
			if($this->role_price_adjustment[$this->current_user_role]['adjustment_price'] !='') {
				$adjustment_price = $this->role_price_adjustment[$this->current_user_role]['adjustment_price'];
				$adjustment_price =  str_replace($this->decimal_separator, '.', $adjustment_price);
				$new_price += floatval($adjustment_price);
			}
			if($this->role_price_adjustment[$this->current_user_role]['adjustment_percent'] !='') {
				$adjustment_percent = $this->role_price_adjustment[$this->current_user_role]['adjustment_percent'];
				$adjustment_percent = str_replace($this->decimal_separator, '.', $adjustment_percent);
				$adjustment_percent_price = round( $price * floatval($adjustment_percent)) / 100;
				$new_price = ( $new_price + $adjustment_percent_price );
			}
			if( 'Discount' != get_option('eh_pricing_product_price_markup_discount')) {
				$price += $new_price;
			} else {
				$price -= $new_price;
			}
		}
	}
	return $price;
}


	//function to add adjustment for price over price from settings
public function add_user_shop_product_price_settings( $price, $id, $product)
{
	if(WC()->version < '2.7.0'){
		$temp_post_id = $product->post->ID;
	}else{
		$temp_post_id = $product->get_id();
	}

	if(is_user_logged_in() && $product) {
		$product_price_adjustment = get_post_meta( $temp_post_id, 'product_price_adjustment', false ) != '' ? get_post_meta( $temp_post_id, 'product_price_adjustment', false ) : array();	
		$product_user_price = get_post_meta( $id, 'product_role_based_price', false ) != '' ? get_post_meta( $id, 'product_role_based_price', false ) : array();
		$new_price = 0;
		if( is_array(current($product_price_adjustment)) && is_array(current($product_user_price)) && key_exists($this->current_user_role,current($product_price_adjustment))) {
			if( key_exists('role_price',current($product_price_adjustment)[$this->current_user_role]) && key_exists($this->current_user_role ,current($product_user_price)) && (current($product_user_price)[$this->current_user_role]['role_price'] !='') ) {
				$price = current($product_user_price)[$this->current_user_role]['role_price'];
			}
		}
		$price = str_replace($this->decimal_separator, '.', $price);
		if( is_array(current($product_price_adjustment)) && key_exists($this->current_user_role,current($product_price_adjustment))) {
			if(current($product_price_adjustment)[$this->current_user_role]['adjustment_price'] !='') {
				$adjustment_price = current($product_price_adjustment)[$this->current_user_role]['adjustment_price'];
				$adjustment_price =  str_replace($this->decimal_separator, '.', $adjustment_price);
				$new_price += floatval($adjustment_price);
			}
			if(current($product_price_adjustment)[$this->current_user_role]['adjustment_percent'] !='') {
				$adjustment_percent = current($product_price_adjustment)[$this->current_user_role]['adjustment_percent'];
				$adjustment_percent = str_replace($this->decimal_separator, '.', $adjustment_percent);
				$adjustment_percent_price = round( $price * floatval($adjustment_percent)) / 100;
				$new_price = ( $new_price + $adjustment_percent_price );
			}
			if( 'Discount' != get_option('eh_pricing_product_price_markup_discount')) {
				$price += $new_price;
			} else {
				$price -= $new_price;
			}
		}
	}
	return $price;
}


public function get_variation_price( $price, $product, $min_or_max, $display, $price_meta_key = 'regular_price') 
{
	if(WC()->version < '2.7.0'){
		$temp_post_id = $product->post->ID;
		$temp_regular_price = $product->regular_price;
	}else{
		$temp_post_id = $product->get_id();
		$temp_regular_price = $product->get_regular_price();
	}
	$price = $this->modify_shop_product_price($price, $product);
	$wcrbp_price = $price;
	$tax_display_mode = get_option( 'woocommerce_tax_display_shop' );
	$prices = array();
	$display = array();
	foreach ($product->get_children() as $variation_id) {
		$variation = (WC()->version < '2.7.0') ? $product->get_child( $variation_id ) : wc_get_product($variation_id) ;
		$send_price = (WC()->version < '2.7.0') ? $variation->price : $variation->get_price() ;

		if( 'Makup' != get_option('eh_pricing_product_price_markup_discount') ) {

			if(is_array($this->role_price_adjustment) && key_exists($this->current_user_role,$this->role_price_adjustment)) {
				
				if(($this->role_price_adjustment[$this->current_user_role]['adjustment_price'] !='' || $this->role_price_adjustment[$this->current_user_role]['adjustment_percent'] !='')  )
				{
					if(key_exists('role_price',$this->role_price_adjustment[$this->current_user_role]))
					{

						if($this->sales_method === 'regular')
						{
							$send_price = (WC()->version < '2.7.0') ? $variation->regular_price : $variation->get_regular_price() ;

						}
						
					}
					
				}
			}
		}	
		if ( $variation ) {
			$settings_price_adjustment = get_option('eh_pricing_discount_product_price_user_role');
			if(is_array($settings_price_adjustment)&& in_array($this->current_user_role,$settings_price_adjustment) && (get_post_meta( $temp_post_id, 'product_based_price_adjustment', true )) == 'yes') {
				$prices[$variation_id] = ($price != '') ? $this->add_user_shop_product_price_settings($send_price, $variation_id, $product) : '';
			} else {
				$prices[$variation_id] = ($price != '') ? $this->add_user_shop_general_price_settings( $send_price, $variation_id) : '';
			}


			if ($this->enable_role_tax && is_array($this->role_tax_option) && key_exists($this->tax_user_role,$this->role_tax_option) && ($this->role_tax_option[$this->tax_user_role]['tax_option'] != 'default')) {
				$new_tax_price = $this->calculate_taxed_price($qty = 1, $prices[$variation_id], $product);
				if($new_tax_price != '') {
					$display[$variation_id] = $new_tax_price;
				} else {
					$display[$variation_id] = ( $tax_display_mode == 'incl' ) ? ( (WC()->version < '2.7.0') ? $variation->get_price_including_tax( 1, $prices[$variation_id] ) : wc_get_price_including_tax($variation, array(1, $prices[$variation_id]) ) ) : ( (WC()->version < '2.7.0') ? $variation->get_price_excluding_tax( 1, $prices[$variation_id] ) : wc_get_price_excluding_tax($variation, array(1, $prices[$variation_id]) ) );
				}
			} else {
				$display[$variation_id] = ( $tax_display_mode == 'incl' ) ? (WC()->version < '2.7.0') ? $variation->get_price_including_tax( 1, $prices[$variation_id]) : wc_get_price_including_tax($variation, array(1, $prices[$variation_id]) )  : ( (WC()->version < '2.7.0') ? $variation->get_price_excluding_tax( 1, $prices[$variation_id]) : wc_get_price_excluding_tax($variation, array(1, $prices[$variation_id]) ) );
			}
		}
	}

	if ( $min_or_max == 'min' ) { 
		 asort($prices); } else { arsort($prices); }

	if ( $display ) {
		$variation_id = key( $prices );				
		$wcrbp_price = $display[$variation_id];
	} else {			
		$wcrbp_price = current($prices);
	}
	return $wcrbp_price;
}

public function get_variation_regular_price( $price, $product, $min_or_max, $display=false ) 
{	
	if(WC()->version < '2.7.0'){
		$temp_post_id = $product->post->ID;
	}else{
		$temp_post_id = $product->get_id();
	}

	$price = $this->modify_shop_product_price($price, $product);
	$wcrbp_price = $price;
		$taxable = $display;//$product->is_taxable();
		$tax_display_mode = get_option( 'woocommerce_tax_display_shop' );
		$prices = array();
		$display = array();
		$new_price = '';
		foreach ($product->get_children() as $variation_id) {
			$variation = (WC()->version < '2.7.0') ? $product->get_child( $variation_id ) : wc_get_product($variation_id) ;
			if ( $variation ) {
				$price = (WC()->version < '2.7.0') ? $variation->regular_price : $variation->get_regular_price();

				if((is_user_logged_in ())) {
					$settings_price_adjustment = get_option('eh_pricing_discount_product_price_user_role');
					if(is_array($settings_price_adjustment)&& in_array($this->current_user_role,$settings_price_adjustment) && (get_post_meta( $temp_post_id, 'product_based_price_adjustment', true )) == 'yes' ) {
						$new_price = ($price != '') ? ((WC()->version < '2.7.0') ? $variation->regular_price : $variation->get_regular_price()) : '';
					} else {
						$new_price = ($price != '') ? ((WC()->version < '2.7.0') ? $variation->regular_price : $variation->get_regular_price()) : '';
					}
					if($new_price !='') {
						$prices[$variation_id] = $new_price;
					} else {
						$prices[$variation_id] = $price;
					}
				} else {
					$prices[$variation_id] = $price;
				}

				if ($this->enable_role_tax && is_array($this->role_tax_option) && key_exists($this->tax_user_role,$this->role_tax_option) && ($this->role_tax_option[$this->tax_user_role]['tax_option'] != 'default')) {
					$new_tax_price = $this->calculate_taxed_price($qty = 1, $prices[$variation_id], $product);

					if($new_tax_price != '') {
						$display[$variation_id] = $new_tax_price;
					} else {
						if($taxable) {
							$display[$variation_id] = ( $tax_display_mode == 'incl' ) ? ( (WC()->version < '2.7.0') ? $variation->get_price_including_tax( 1, $prices[$variation_id] ) : wc_get_price_including_tax($variation, array(1, $prices[$variation_id]) ) ) : ( (WC()->version < '2.7.0') ? $variation->get_price_excluding_tax( 1, $prices[$variation_id] ) : wc_get_price_excluding_tax($variation, array(1, $prices[$variation_id]) ) ) ;
						}
					}
				} else {
					if($taxable) {
						$display[$variation_id] = ( $tax_display_mode == 'incl' ) ? ( (WC()->version < '2.7.0') ? $variation->get_price_including_tax( 1, $prices[$variation_id] ) : wc_get_price_including_tax($variation, array(1, $prices[$variation_id]) ) ) : ( (WC()->version < '2.7.0') ? $variation->get_price_excluding_tax( 1, $prices[$variation_id] ) : wc_get_price_excluding_tax($variation, array(1, $prices[$variation_id]) ) );
					}
				}
			}
		}



		if ( $min_or_max == 'min' ) { asort($prices); } else { arsort($prices); }
		if ( $display ) {
			$variation_id = key( $prices );
			$wcrbp_price = $display[$variation_id];
		} else {
			$wcrbp_price = current($prices);
		}

		if( 'Discount' != get_option('eh_pricing_product_price_markup_discount') && ($product->get_price() != '' ) ) {

			$wcrbp_price = $this->get_variation_price( $price, $product, $min_or_max, $display, 'regular_price');

		}
		return $wcrbp_price;
	}
	
	public function get_price_html( $price = '',$product ) 
	{
		$main_price=$price;
                
		if(WC()->version < '2.7.0'){
			$temp_data = $product->product_type;
			$temp_post_id = $product->post->id;
		}else{
			$temp_data = $product->get_type();
			$temp_post_id = $product->get_id();
		}
		if('WC_Product_Variable' == get_class( $product )){
        	// Ensure variation prices are synced with variations
			if($product->get_variation_regular_price( 'min' ) === false || 
				$product->get_variation_price( 'min' ) === false || 
				$product->get_variation_price( 'min' ) === '' || 
				$product->get_price() === '' ) {
				$product->variable_product_sync( (WC()->version < '2.7.0') ? $product->id : $product->get_id() ) ;
		}
		    // Get the price
		if ( $product->get_price() === '' && $product->get_variation_regular_price('min') === false) {
			$price = apply_filters( 'woocommerce_variable_empty_price_html', '', $product );
			add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
		} else {
			$show_no_price = false;
			$unregistered_user_price_text = get_option('eh_pricing_discount_price_unregistered_user_text');
			$price_alternate_text = '';
			if( ('yes' == get_option('eh_pricing_discount_price_unregistered_user') ) || ( 'yes' == ( get_post_meta( $temp_post_id, 'product_adjustment_hide_price_unregistered', true ) ) ) ) {
				if(!(is_user_logged_in ())) {
					$show_no_price = true;
					if( $unregistered_user_price_text != ''){
						$price_alternate_text = $unregistered_user_price_text;
					}
				}
			}

				//to remove price for specific selected user roles
			$remove_settings_price_roles = ( get_option('eh_pricing_discount_price_user_role') != '') ? get_option('eh_pricing_discount_price_user_role') : '';
			$remove_product_price_roles = get_post_meta( $temp_post_id, 'eh_pricing_adjustment_product_price_user_role', false ) != '' ? get_post_meta( $temp_post_id, 'eh_pricing_adjustment_product_price_user_role', false ) : array();
			$user_role_price_text = get_option('eh_pricing_discount_price_user_role_text');
			if((is_user_logged_in ())) {
				if((is_array($remove_settings_price_roles) && in_array($this->current_user_role,$remove_settings_price_roles )) || (is_array(current($remove_product_price_roles)) && in_array($this->current_user_role,current($remove_product_price_roles)))) {
					$show_no_price = true;
					if( $user_role_price_text != ''){
						$price_alternate_text = $user_role_price_text;
					}
				}
			}

                // Main price
			if ($this->enable_role_tax && is_array($this->role_tax_option) && key_exists($this->tax_user_role,$this->role_tax_option) && ($this->role_tax_option[$this->tax_user_role]['tax_option'] != 'default')) {
				if($product->is_taxable()) {
					$price1 = $product->get_variation_price('min', true);
					$price2 = $product->get_variation_price('max', true);

					if($this->role_tax_option[$this->tax_user_role]['tax_option'] === 'show_price_excluding_tax' || $this->role_tax_option[$this->tax_user_role]['tax_option'] === 'show_price_excluding_tax_shop'){
					if (('yes' === get_option( 'woocommerce_prices_include_tax' ))) {
					$tax_rates  = WC_Tax::get_base_tax_rates( (WC()->version < '2.7.0') ? $product->tax_class : $product->get_tax_class()  );
					$taxes      = WC_Tax::calc_tax( $price1 * 1, $tax_rates, true );
					$taxes2      = WC_Tax::calc_tax( $price2 * 1, $tax_rates, true );
					$price1     = WC_Tax::round( $price1 * 1 - array_sum( $taxes ) );
					$price2     = WC_Tax::round( $price2 * 1 - array_sum( $taxes2 ) );
					}
					}
					if($this->role_tax_option[$this->tax_user_role]['tax_option'] === 'show_price_including_tax' || $this->role_tax_option[$this->tax_user_role]['tax_option'] === 'show_price_including_tax_shop'){
					if (!('yes' === get_option( 'woocommerce_prices_include_tax' ))) {
					$tax_rates  = WC_Tax::get_base_tax_rates( (WC()->version < '2.7.0') ? $product->tax_class : $product->get_tax_class()  );
					$taxes      = WC_Tax::calc_tax( $price1 * 1, $tax_rates, false );
					$taxes2      = WC_Tax::calc_tax( $price2 * 1, $tax_rates, false );
					$price1     = WC_Tax::round( $price1 * 1 + array_sum( $taxes ) );
					$price2     = WC_Tax::round( $price2 * 1 + array_sum( $taxes2 ) );
					}
					}
					$prices = array($price1, $price2);

				} else {
					$prices = array($product->get_variation_price('min', false), $product->get_variation_price('max', false));
				}

				
				foreach($prices as $key => $value) {
					if($value == 0) {
						unset($prices[$key]);
					} else {
						$price = wc_price( $value );
					}
				}

				if(count($prices) >1){
					$price  =  $prices[0] !== $prices[1] ? sprintf(_x( '%1$s&ndash;%2$s','Price range: from-to','woocommerce'), wc_price( $prices[0] ), wc_price( $prices[1] ) ) : wc_price( $prices[0] );
				}

			} else {
				if($product->is_taxable()) {
					$prices = array($product->get_variation_price('min', true), $product->get_variation_price('max', true));
				} else {
					$prices = array($product->get_variation_price('min', false), $product->get_variation_price('max', false));
				}
				foreach($prices as $key => $value) {
					if($value == 0) {
						unset($prices[$key]);
					} else {
						$price = wc_price( $value );
					}
				}
				if(count($prices) >1){
					$price  =  $prices[0] !== $prices[1] ? sprintf(_x( '%1$s&ndash;%2$s','Price range: from-to','woocommerce'), wc_price( $prices[0] ), wc_price( $prices[1] ) ) : wc_price( $prices[0] );
				}

			}

			if(!empty($prices[0]))
			{
				$price_suffix = $product->get_price_suffix($prices[0]);
			}else
			{
				$price_suffix ='';
			}
           
                // Sale
			if($this->hide_regular_price) {
				if($product->is_taxable()) {
					$price1 = $product->get_variation_regular_price('min', false);
					$price2 = $product->get_variation_regular_price('max', false);

					if(is_user_logged_in())
					{
					if($this->role_tax_option[$this->tax_user_role]['tax_option'] === 'show_price_excluding_tax' || $this->role_tax_option[$this->tax_user_role]['tax_option'] === 'show_price_excluding_tax_shop'){
					if (('yes' === get_option( 'woocommerce_prices_include_tax' ))) {
					$tax_rates  = WC_Tax::get_base_tax_rates( (WC()->version < '2.7.0') ? $product->tax_class : $product->get_tax_class()  );
					$taxes      = WC_Tax::calc_tax( $price1 * 1, $tax_rates, true );
					$taxes2      = WC_Tax::calc_tax( $price2 * 1, $tax_rates, true );
					$price1     = WC_Tax::round( $price1 * 1 - array_sum( $taxes ) );
					$price2     = WC_Tax::round( $price2 * 1 - array_sum( $taxes2 ) );
					}
					}
					if($this->role_tax_option[$this->tax_user_role]['tax_option'] === 'show_price_including_tax' || $this->role_tax_option[$this->tax_user_role]['tax_option'] === 'show_price_including_tax_shop'){
					if (!('yes' === get_option( 'woocommerce_prices_include_tax' ))) {
					$tax_rates  = WC_Tax::get_base_tax_rates( (WC()->version < '2.7.0') ? $product->tax_class : $product->get_tax_class()  );
					$taxes      = WC_Tax::calc_tax( $price1 * 1, $tax_rates, false );
					$taxes2      = WC_Tax::calc_tax( $price2 * 1, $tax_rates, false );
					$price1     = WC_Tax::round( $price1 * 1 + array_sum( $taxes ) );
					$price2     = WC_Tax::round( $price2 * 1 + array_sum( $taxes2 ) );
					}
					}
					}

					$prices = array($price1, $price2);

				} else {
					$prices = array($product->get_variation_regular_price('min', false), $product->get_variation_regular_price('max', false));
				}
				
			}
			
			sort($prices);
			foreach($prices as $key => $value) {
				if($value == 0) {
					unset($prices[$key]);
				} else {
					$saleprice = wc_price( $value );
				}
			}

			if(count($prices) >1){
				if(sprintf(_x( '%1$s&ndash;%2$s','Price range: from-to','woocommerce'), wc_price( $prices[0] ), wc_price( $prices[1] ) ) === $price)
				{
						$saleprice = ($show_no_price) ? $price_alternate_text : $prices[0] !== $prices[1] ? sprintf(_x( '%1$s&ndash;%2$s','Price range: from-to','woocommerce'), wc_price( $prices[0] ), wc_price( $prices[1] ) ) : wc_price( $prices[0] );		
				}else{
					$saleprice = ($show_no_price) ? $price_alternate_text : $prices[0] !== $prices[1] ? sprintf(_x( '<s>%1$s&ndash;%2$s</s>','Price range: from-to','woocommerce'), wc_price( $prices[0] ), wc_price( $prices[1] ) ) : wc_price( $prices[0] );		
				}
				
				}
			if ( $price !== $saleprice ) {
				$price = ($show_no_price) ? $price_alternate_text : apply_filters( 'woocommerce_variable_sale_price_html', (WC()->version < '2.7.0') ? $product->get_price_html_from_to( $saleprice, $price ) : wc_format_price_range($saleprice, $price) . $price_suffix, $product );
				$price_suffix = '';
			}
			$price = $this->modify_shop_product_price($price, $product);
			if($show_no_price) {
				$prices[0] = 0;
				$prices[1] = 0;
				remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
				remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
			}

			if ( !empty($prices[0]) && $prices[0] == 0 && $prices[1] == 0 ) {
				if( strip_tags( $price ) == 'Free!' || $price =='') {
					if(!(is_user_logged_in ())) {
						$price = ($show_no_price) ? $price_alternate_text : apply_filters( 'woocommerce_variable_free_price_html', $price, $product );
					}
					if((is_user_logged_in ())) {
						$price = ($show_no_price) ? $price_alternate_text : apply_filters( 'woocommerce_variable_free_price_html', $price, $product );
					}
				}
			} else {
				$price = ($show_no_price) ? $price_alternate_text : apply_filters( 'woocommerce_variable_price_html', $price . $price_suffix, $product );
				if($price != '') {
					if(!(is_user_logged_in ())) {
						if(!('yes' == get_option('eh_pricing_discount_cart_unregistered_user')) && !('yes' == (get_post_meta($temp_post_id, 'product_adjustment_hide_addtocart_unregistered', true)))) {
							add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
						}
					} else {
						$show_add_cart = true;
						$remove_cart_roles = get_option('eh_pricing_discount_cart_user_role');
						$remove_product_cart_roles = get_post_meta( $temp_post_id, 'eh_pricing_adjustment_product_addtocart_user_role', false ) == '' ? array() : get_post_meta( $temp_post_id, 'eh_pricing_adjustment_product_addtocart_user_role', false ) ;
						if(is_array( $remove_cart_roles ) && !(in_array($this->current_user_role,$remove_cart_roles))) {
							if(is_array(current($remove_product_cart_roles))) {
								if(!(in_array($this->current_user_role,current($remove_product_cart_roles)))) {
									add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
								}
							} else {
								add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
							}
						}
					}
				}
				$price = $this->eh_pricing_add_price_suffix($price,$product);
			}
			$additional_data = '';
			if(is_shop() || is_product()) {
				if(is_product()) {
					$additional_data = $this->add_to_cart_text_content($product);
				} else {
					$additional_data = $this->add_to_cart_single_product_text_content($product);
				}
				$price .= '<br/>'.$additional_data;
			}
		}
		$price = apply_filters('eh_pricing_adjustment_modfiy_price', $price, $this->current_user_role);
	} else if( $temp_data === 'simple' ) {
		$default = $price;
		$price = $product->get_price();

		$price_suffix = $product->get_price_suffix($price);
		
		if((strip_tags( $price ) == 'Free!' || ($price == '' && ((WC()->version < '2.7.0') ? $product->price : $product->get_price() ) === ''))) {
			add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
		} else {
			$price = $this->modify_shop_product_price($price, $product);
                        
			

			if($price > 0) {
				if(!(is_user_logged_in ())) {
					if(!('yes' == get_option('eh_pricing_discount_cart_unregistered_user')) && !('yes' == (get_post_meta($temp_post_id, 'product_adjustment_hide_addtocart_unregistered', true)))) {
						add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
					}
				} else {

					$remove_cart_roles = get_option('eh_pricing_discount_cart_user_role');
					$remove_product_cart_roles = get_post_meta( $temp_post_id, 'eh_pricing_adjustment_product_addtocart_user_role', false ) == '' ? array() : get_post_meta( $temp_post_id, 'eh_pricing_adjustment_product_addtocart_user_role', false ) ;
					if(is_array( $remove_cart_roles ) && !(in_array($this->current_user_role,$remove_cart_roles))) {
						if(is_array(current($remove_product_cart_roles))) {
							if(!(in_array($this->current_user_role,current($remove_product_cart_roles)))) {
								add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
							}
						} else {
							add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
						}
					}
				}

				if($product->get_regular_price() && $price != '' && ($product->get_regular_price() > $product->get_sale_price())) {
					$price = (WC()->version < '2.7.0') ? $product->get_price_html_from_to($product->get_regular_price(), $price) : wc_format_price_range($product->get_regular_price(), $price);
				} else {
					$price = (WC()->version < '2.7.0') ? $product->get_price_html_from_to('', $price) : wc_format_price_range('', $price) ;
				}

				if($product->is_taxable()) {
					if ($this->enable_role_tax && is_array($this->role_tax_option) && key_exists($this->tax_user_role,$this->role_tax_option) && ($this->role_tax_option[$this->tax_user_role]['tax_option'] != 'default')) {
						$price = $default;
					}else
					{
						$price = $default;
					}
				}
                                //Add suffix twice
                                $pos = strpos($price, $price_suffix);
                                if ($pos === false) {
                                    $price .= $price_suffix;
                                }
                                //$price .= $price_suffix;
				if($price != '') {

					$price = $this->eh_pricing_add_price_suffix($price, $product);
				}
			} else {
				remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
				remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
			}



			$additional_data = '';
			if(is_shop() || is_product()) {
				if(is_product()) {
					$additional_data = $this->add_to_cart_text_content($product);
				} else {
					$additional_data = $this->add_to_cart_single_product_text_content($product);
				}
				$price .= '<br/>'.$additional_data;
			}
		}
		$price = apply_filters('eh_pricing_adjustment_modfiy_price', $price, $this->current_user_role);

		

	} else {
		if( $temp_data === 'grouped') {
			if((strip_tags($price)) == 'Free!') {
				$price = '';
				remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
				remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
			}
		} else {
			add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
			add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
		}
	}

	return $price;
}

	//function to determine the user role to use in case of multiple user roles for one user
public function get_priority_user_role($user_roles, $role_priority_list)
{
	if(is_user_logged_in ()) {
		if(!empty($role_priority_list)) {
			foreach ($role_priority_list as $id => $value) {
				if(in_array($id,$user_roles)) {
					return $id;
				}
			}
		} else {
			return $user_roles[0];
		}
	}
}
	//function to check current page is cart
public function is_cart() 
{
	return is_page( wc_get_page_id( 'cart' ) ) || defined( 'WOOCOMMERCE_CART' ) || wc_post_content_has_shortcode( 'woocommerce_cart' );
}

	//function to check current page is checkout
public function is_checkout() 
{
	return is_page( wc_get_page_id( 'checkout' ) ) || wc_post_content_has_shortcode( 'woocommerce_checkout' ) || apply_filters( 'woocommerce_is_checkout', false );
}

	//function to apply tax on price
public function calculate_taxed_price($qty = 1, $wcrbp_price, $product)
{

	switch(isset ($this->role_tax_option[$this->tax_user_role]['tax_option']) ? $this->role_tax_option[$this->tax_user_role]['tax_option'] : 'default') {
		case 'show_price_including_tax':
		$wcrbp_price = $this->calculate_price_including_tax($qty = 1, $wcrbp_price, $product);
		break;
		case 'show_price_excluding_tax':
		$wcrbp_price = $this->calculate_price_excluding_tax($qty = 1, $wcrbp_price, $product);
		break;
		case 'show_price_including_tax_shop':
		if(!(is_cart() || is_checkout())) {
			$wcrbp_price = $this->calculate_price_including_tax_shop($qty = 1, $wcrbp_price, $product);
		} else {
			$wcrbp_price = '';
		}
		break;
		case 'show_price_including_tax_cart_checkout':
		if(is_cart() || is_checkout()) {
			$wcrbp_price = $this->calculate_price_including_tax_cart_checkout($qty = 1, $wcrbp_price, $product);
		} else {
			$wcrbp_price = '';
		}
		break;
		case 'show_price_excluding_tax_shop':
		if(!(is_cart() || is_checkout())) {
			$wcrbp_price = $this->calculate_price_excluding_tax_shop($qty = 1, $wcrbp_price, $product);
		} else {
			$wcrbp_price = '';
		}
		break;
		case 'show_price_excluding_tax_cart_checkout':
		if(is_cart() || is_checkout()) {
			$wcrbp_price = $this->calculate_price_excluding_tax_cart_checkout($qty = 1, $wcrbp_price, $product);
		} else {
			$wcrbp_price = '';
		}
		break;
		default :
		break;
	}
	return $wcrbp_price;
}

	//function to add tax throught the site
public function calculate_price_including_tax($qty = 1, $price, $product)
{

		if(WC()->version < '2.7.0'){
			$temp_data = $product->product_type;
		}else{
			if(is_object($product))
				{
					$temp_data = $product->get_type();		
				}else{
					$temp_data = '';
				}
			
		}

	if($this->is_product_actually_taxable($product)) {
			/* switch ($product->product_type) {
				case 'simple':
					$product->tax_status = 'none';
					break;
				case 'variable':
					$product->tax_status = 'none';
					break;
				case 'variation':
					$product->parent->tax_status = 'none';
					break;
				} 
				if ( $price === '' ) {
					$price = $product->get_price();
				} *
				if (!('yes' === get_option( 'woocommerce_prices_include_tax' )) && $temp_data !='simple' ) { 
					$tax_rates  = WC_Tax::get_base_tax_rates( (WC()->version < '2.7.0') ? $product->tax_class : $product->get_tax_class() );
					$taxes      = WC_Tax::calc_tax( $price * $qty, $tax_rates, false);
					$price      = WC_Tax::round( $price * $qty + array_sum( $taxes ) );
				} else {
					$price = $price * $qty;
				} */
				if($temp_data !='simple')
				{
					$price = $price * $qty;
				}
				(WC()->version < '2.7.0') ? add_filter( 'woocommerce_product_tax_class', array($this,'eh_diff_rate_for_user'), 1, 2 ) : add_filter( 'woocommerce_product_get_tax_class', array($this,'eh_diff_rate_for_user'), 1, 2 ) ;
				
				add_filter('pre_option_woocommerce_tax_display_shop', array($this,'eh_override_tax_display_setting_in'));
				add_filter('pre_option_woocommerce_tax_display_cart', array($this,'eh_override_tax_display_setting_in'));
				add_filter('pre_option_woocommerce_tax_display_checkout', array($this,'eh_override_tax_display_setting_in'));
			}
			return $price;
		}

	//function to remove tax throught the site
		public function calculate_price_excluding_tax($qty = 1, $price, $product)
		{
			if(WC()->version < '2.7.0'){
			$temp_data = $product->product_type;
		}else{
			if(is_object($product))
				{
					$temp_data = $product->get_type();		
				}else{
					$temp_data = '';
				}
		}
			if($this->is_product_actually_taxable($product)) {
			/* switch ($product->product_type) {
				case 'simple':
					$product->tax_status = 'none';
					break;
				case 'variable':
					$product->tax_status = 'none';
					break;
				case 'variation':
					$product->parent->tax_status = 'none';
					break;
			} 
			if ( $price === '' ) {
				$price = $product->get_price();
			} 
			if (('yes' === get_option( 'woocommerce_prices_include_tax' )) && $temp_data != 'simple') {
				$tax_rates  = WC_Tax::get_base_tax_rates( (WC()->version < '2.7.0') ? $product->tax_class : $product->get_tax_class()  );
				$taxes      = WC_Tax::calc_tax( $price * $qty, $tax_rates, true );
				$price      = WC_Tax::round( $price * $qty - array_sum( $taxes ) );
			} else {
				$price = $price * $qty;
			} */
			if($temp_data !='simple')
				{
					$price = $price * $qty;
				}
			(WC()->version < '2.7.0') ? add_filter( 'woocommerce_product_tax_class', array($this,'eh_diff_rate_for_user'), 1, 2 ) : add_filter( 'woocommerce_product_get_tax_class', array($this,'eh_diff_rate_for_user'), 1, 2 ) ;
			

			add_filter('pre_option_woocommerce_tax_display_shop', array($this,'eh_override_tax_display_setting_ex'));
			add_filter('pre_option_woocommerce_tax_display_cart', array($this,'eh_override_tax_display_setting_ex'));
			add_filter('pre_option_woocommerce_tax_display_checkout', array($this,'eh_override_tax_display_setting_ex'));

		}
		
		return $price;
	}
	
	//function to include tax at shop alone
	public function calculate_price_including_tax_shop($qty = 1, $price, $product)
	{
		if(WC()->version < '2.7.0'){
			$temp_data = $product->product_type;
		}else{
			if(is_object($product))
				{
					$temp_data = $product->get_type();		
				}else{
					$temp_data = '';
				}
		}

		if($this->is_product_actually_taxable($product)) {
			/*switch ($product->product_type) {
				case 'simple':
					$product->tax_status = 'none';
					break;
				case 'variable':
					$product->tax_status = 'none';
					break;
				case 'variation':
					$product->parent->tax_status = 'none';
					break;
				} 
				if ( $price === '' ) {
					$price = $product->get_price();
				}
				if (!('yes' === get_option( 'woocommerce_prices_include_tax' )) && $temp_data != 'simple') {
					$tax_rates  = WC_Tax::get_base_tax_rates( (WC()->version < '2.7.0') ? $product->tax_class : $product->get_tax_class()  );
					$taxes      = WC_Tax::calc_tax( $price * $qty, $tax_rates, false);
					$price      = WC_Tax::round( $price * $qty + array_sum( $taxes ) );
				} else {
					$price = $price * $qty;
				} */
			if($temp_data !='simple')
				{
					$price = $price * $qty;
				}
				(WC()->version < '2.7.0') ? add_filter( 'woocommerce_product_tax_class', array($this,'eh_diff_rate_for_user'), 1, 2 ) : add_filter( 'woocommerce_product_get_tax_class', array($this,'eh_diff_rate_for_user'), 1, 2 ) ;
				

				add_filter('pre_option_woocommerce_tax_display_shop', array($this,'eh_override_tax_display_setting_in'));
			}
			return $price;
		}

	//function to remove tax at shop alone
		public function calculate_price_excluding_tax_shop($qty = 1, $price, $product)
		{
			if(WC()->version < '2.7.0'){
			$temp_data = $product->product_type;
		}else{
			if(is_object($product))
				{
					$temp_data = $product->get_type();		
				}else{
					$temp_data = '';
				}
		}
			if($this->is_product_actually_taxable($product)) {
			/* switch ($product->product_type) {
				case 'simple':
					$product->tax_status = 'none';
					break;
				case 'variable':
					$product->tax_status = 'none';
					break;
				case 'variation':
					$product->parent->tax_status = 'none';
					break;
			} 
				
			if ( $price === '' ) {
				$price = $product->get_price();
			} 

			if (('yes' === get_option( 'woocommerce_prices_include_tax' )) && $temp_data !='simple') {
				$tax_rates  = WC_Tax::get_base_tax_rates( (WC()->version < '2.7.0') ? $product->tax_class : $product->get_tax_class() );
				$taxes      = WC_Tax::calc_tax( $price * $qty, $tax_rates, true );
				$price  = WC_Tax::round( $price * $qty - array_sum( $taxes ) );
			} else {
				$price = $price * $qty;
			} */
			if($temp_data !='simple')
				{
					$price = $price * $qty;
				}
			

			(WC()->version < '2.7.0') ? add_filter( 'woocommerce_product_tax_class', array($this,'eh_diff_rate_for_user'), 1, 2 ) : add_filter( 'woocommerce_product_get_tax_class', array($this,'eh_diff_rate_for_user'), 1, 2 ) ;
			
			add_filter('pre_option_woocommerce_tax_display_shop', array($this,'eh_override_tax_display_setting_ex'));
		}
		return $price;
	}
	
	//function to remove tax at cart and shop only
	public function calculate_price_excluding_tax_cart_checkout($qty = 1, $price, $product)
	{
		if(WC()->version < '2.7.0'){
			$temp_data = $product->product_type;
		}else{
			if(is_object($product))
				{
					$temp_data = $product->get_type();		
				}else{
					$temp_data = '';
				}
		}
		if($this->is_product_actually_taxable($product)) {



		/*	switch ($product->product_type) {
				case 'simple':
					$product->tax_status = 'none';
					break;
				case 'variable':
					$product->tax_status = 'none';
					break;
				case 'variation':
					$product->parent->tax_status = 'none';
					break;
			} 

			
			if ( $price === '' ) {
				$price = $product->get_price();
			} 

    		if (('yes' === get_option( 'woocommerce_prices_include_tax' )) && $temp_data !='simple') {
				$tax_rates  = WC_Tax::get_base_tax_rates( (WC()->version < '2.7.0') ? $product->tax_class : $product->get_tax_class()  );
				$taxes      = WC_Tax::calc_tax( $price * $qty, $tax_rates, true );
				$price      = WC_Tax::round( $price * $qty - array_sum( $taxes ) );
			} else {
				$price = $price * $qty;
			} */
			if($temp_data !='simple')
				{
					$price = $price * $qty;
				}

			(WC()->version < '2.7.0') ? add_filter( 'woocommerce_product_tax_class', array($this,'eh_diff_rate_for_user'), 1, 2 ) : add_filter( 'woocommerce_product_get_tax_class', array($this,'eh_diff_rate_for_user'), 1, 2 ) ;
			
			add_filter('pre_option_woocommerce_tax_display_cart', array($this,'eh_override_tax_display_setting_ex'));
			add_filter( 'pre_option_woocommerce_tax_display_checkout', array($this,'eh_override_tax_display_setting_ex'));

		}

		
		
		return $price;
	}
	public function eh_diff_rate_for_user( $tax_class, $product ) {
    // Getting the current user 
		$current_user = wp_get_current_user();
		$current_user_data = get_userdata($current_user->ID);

		if ( isset ($this->role_tax_option[$this->tax_user_role]['tax_calsses']) ? $this->role_tax_option[$this->tax_user_role]['tax_calsses'] : 'default' )
			if($this->role_tax_option[$this->tax_user_role]['tax_calsses'] != 'default')
			{

				$tax_class = $this->role_tax_option[$this->tax_user_role]['tax_calsses'];	

			}

			return $tax_class;
		}

		public function eh_override_tax_display_setting_ex()
		{
			return "excl";
		}
		public function eh_override_tax_display_setting_in()
		{
			return "incl";
		}

	//function to add tax at cart and checkout only
		public function calculate_price_including_tax_cart_checkout($qty = 1, $price, $product)
		{
			if(WC()->version < '2.7.0'){
			$temp_data = $product->product_type;
		}else{
			if(is_object($product))
				{
					$temp_data = $product->get_type();		
				}else{
					$temp_data = '';
				}
		}
			if($this->is_product_actually_taxable($product)) {
		/*	switch ($product->product_type) {
				case 'simple':
					$product->tax_status = 'none';
					break;
				case 'variable':
					$product->tax_status = 'none';
					break;
				case 'variation':
					$product->parent->tax_status = 'none';
					break;
			} 
			if ( $price === '' ) {
				$price = $product->get_price();
			} 
			if (!('yes' === get_option( 'woocommerce_prices_include_tax' )) && $temp_data !='simple') {
				$tax_rates  = WC_Tax::get_base_tax_rates( (WC()->version < '2.7.0') ? $product->tax_class : $product->get_tax_class()  );
				$taxes      = WC_Tax::calc_tax( $price * $qty, $tax_rates, false );
				$price      = WC_Tax::round( $price * $qty + array_sum( $taxes ) );
			} else {
				$price = $price * $qty;
			} */
			if($temp_data !='simple')
				{
					$price = $price * $qty;
				}	

			(WC()->version < '2.7.0') ? add_filter( 'woocommerce_product_tax_class', array($this,'eh_diff_rate_for_user'), 1, 2 ) : add_filter( 'woocommerce_product_get_tax_class', array($this,'eh_diff_rate_for_user'), 1, 2 ) ;
	
			add_filter('pre_option_woocommerce_tax_display_cart', array($this,'eh_override_tax_display_setting_in'));
			add_filter( 'pre_option_woocommerce_tax_display_checkout', array($this,'eh_override_tax_display_setting_in'));
		}
		return $price;
	}
	
	//function to re assign the tax option value to product
	public function reset_tax_value($product) 
	{


		if($product) {
			if(WC()->version < '2.7.0'){
				$temp_data = $product->product_type;
				$temp_post_id = $product->post->id;
			}else{
				if(is_object($product))
				{
					$temp_data = $product->get_type();
					$temp_post_id = $product->get_id();
				}
				else
				{
					$temp_data = '';
					$temp_post_id = '';
				}
			}
			switch ($temp_data) {
				case 'simple':
				$temp_product = new WC_Product($temp_post_id);
				$product->tax_status = (WC()->version < '2.7.0') ? $temp_product->tax_status : $temp_product->get_tax_status() ;
				break;
				case 'variable':
				$temp_product = new WC_Product($temp_post_id);
				$product->tax_status = (WC()->version < '2.7.0') ? $temp_product->tax_status : $temp_product->get_tax_status() ;
				break;
				case 'variation':
				$temp_product = new WC_Product($temp_post_id);
				$product->parent->tax_status = (WC()->version < '2.7.0') ? $temp_product->tax_status : $temp_product->get_tax_status() ;
				break;
			}
		}
	}
	
	//function to check actual product is taxable or not
	public function  is_product_actually_taxable($product) 
	{
		

		if(WC()->version < '2.7.0'){
			$temp_post_id = $product->post->id;
		}else{
			if(is_object($product))
			{
				$temp_post_id = $product->get_id();	
			}else
			{
				$temp_post_id = '';
			}
			
		}

		$temp_product = (WC()->version < '2.7.0') ? new WC_Product($temp_post_id) : wc_get_product($temp_post_id);
		
		if(($temp_product) && $temp_product->is_taxable()) {
			return true;
		} else {
			return false;
		}
	
}

	//function to add price suffix
	public function eh_pricing_add_price_suffix($price, $product)
	{
		$price_suffix;
		if($this->price_suffix_option == 'general') {
			$price_suffix = ' <small class="woocommerce-price-suffix">' . $this->general_price_suffix . '</small>';
		} else if($this->price_suffix_option == 'role_specific') {
			$user_role;
			if(is_user_logged_in ()) {
				$user_role = $this->price_suffix_user_role;
			} else {
				$user_role = 'unregistered_user';
			}
			if(is_array($this->role_price_suffix) && key_exists($user_role,$this->role_price_suffix)) {
				$price_suffix = ' <small class="woocommerce-price-suffix">' . $this->role_price_suffix[$user_role]['price_suffix'] . '</small>';
			}
		}
		if(!empty($price_suffix)) {
			$find = array(
				'{price_including_tax}',
				'{price_excluding_tax}'
				);
			$replace = array(
				wc_price( (WC()->version < '2.7.0') ? $product->get_price_including_tax() : wc_get_price_including_tax($product) ),
				wc_price(  (WC()->version < '2.7.0') ? $product->get_price_excluding_tax() : wc_get_price_excluding_tax($product) ) 
				);
			$price_suffix = str_replace( $find, $replace, $price_suffix );
			$price .= $price_suffix;
		}
		return $price;
	}
}
new Class_Eh_Price_Discount_Admin();
