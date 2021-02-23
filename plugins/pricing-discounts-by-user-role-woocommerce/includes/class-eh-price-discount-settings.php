<?php
require_once( WP_PLUGIN_DIR . '/woocommerce/includes/admin/settings/class-wc-settings-page.php' );
class Eh_Pricing_Discount_Settings  extends WC_Settings_Page{

	public function __construct() {
		global $user_adjustment_price;
		$this->init();
		$this->id = 'eh_pricing_discount';
	}

    public function init() {
		include( 'class-wf-admin-notice.php' );
		$this->user_adjustment_price = get_option('eh_pricing_discount_price_adjustment_options');
        add_filter( 'woocommerce_settings_tabs_array', array($this,'add_settings_tab'), 50 );
		add_filter('eh_pricing_discount_manage_user_role_settings',array($this,'add_manage_role_settings'),30);
		add_action('woocommerce_admin_field_priceadjustmenttable',array( $this, 'pricing_admin_field_priceadjustmenttable'));//to add price adjustment table to settings
		add_action('woocommerce_admin_field_taxoptiontable',array( $this, 'pricing_admin_field_taxoptiontable'));//to add tax options table to settings
		add_action('woocommerce_admin_field_rolepricesuffix',array( $this, 'pricing_admin_field_rolepricesuffix'));//to add price suffix option to settings
		add_action('woocommerce_admin_field_pricing_discount_manage_user_role',array( $this, 'pricing_admin_field_pricing_discount_manage_user_role'));
        add_action( 'woocommerce_update_options_eh_pricing_discount', array( $this, 'update_settings') );
		add_filter( 'woocommerce_product_data_tabs', array( $this,'add_product_tab') );
		add_action( 'woocommerce_product_data_panels', array($this,'add_price_adjustment_data_fields') );
		add_action( 'woocommerce_product_after_variable_attributes', array($this,'variation_settings_fields'), 10, 3 );
		add_action( 'woocommerce_product_after_variable_attributes_js', array($this,'variation_settings_fields'), 10, 3 );
		add_action( 'woocommerce_process_product_meta', array($this,'woo_add_custom_general_fields_save') );
		add_action( 'woocommerce_product_options_general_product_data', array($this,'add_price_extra_fields') );
		add_action( 'woocommerce_save_product_variation', array($this,'save_variable_fields'), 10, 1 );
		add_action( 'event-category_add_form_fields', array($this,'pricing_category_adjustment_fields'),10 );
		add_filter( 'woocommerce_sections_eh_pricing_discount',      array( $this, 'output_sections' ));
		add_filter( 'woocommerce_settings_eh_pricing_discount',      array( $this, 'output_settings' ));
		add_action('admin_init',array( $this, 'pricing_discount_remove_notices'));
    }
	
	 public function get_sections() {
		$plugin_name = 'pricesbyuserrole';
		include( 'wf_api_manager/html/html-wf-activation-window.php' );
        $sections = array(
            ''						=> __( 'Role Based Pricing','eh-woocommerce-pricing-discount'),
            'manage-user-role'		=> __( 'Manage User Role','eh-woocommerce-pricing-discount'),
        );
        $sections = apply_filters('eh_pricing_discount_sections', $sections );
        return apply_filters( 'woocommerce_get_sections_eh_pricing_discount', $sections );
    }
	
	public function pricing_discount_remove_notices() {
		global $current_section;
		if( $current_section == 'manage-user-role' ) {
			remove_all_actions('admin_notices');
			wf_admin_notice::throw_notices();
		}
	}
	
    public static function add_settings_tab( $settings_tabs ) {
        $settings_tabs[ 'eh_pricing_discount' ] = __( 'Pricing and Discount', 'eh-woocommerce-pricing-discount' );
        return $settings_tabs;
    }

    public function output_settings() {
		global $current_section; 
		if( $current_section == 'manage-user-role' ) {
			remove_all_actions('admin_notices');
			wf_admin_notice::throw_notices();
		}
		if( $current_section == '' ) {
			$settings = $this->get_settings( $current_section );
			WC_Admin_Settings::output_fields( $settings );
		} else if( $current_section == 'manage-user-role' ){
			$settings = $this->get_user_role_settings( $current_section );
			WC_Admin_Settings::output_fields( $settings );
		}
    }
	
	public function get_user_role_settings( $current_section ) {
		$settings = array(
            'section_title'			=> array(
                'name'				=> __( '', 'eh-woocommerce-pricing-discount' ),
                'type'				=> 'title',
                'desc'				=> '',
                'id'				=> 'eh_pricing_discount_add_user_role_section_title',
            ),
			'section_end' => array(
				'type'			=> 'sectionend',
				'id'			=> 'eh_pricing_discount_add_user_role_section_end'
            ),
        );
		return apply_filters( 'eh_pricing_discount_manage_user_role_settings', $settings );
	}
	
	//function to add 
	public function add_manage_role_settings($settings)
	{
		$settings['price_adjustment_options'] = array(
				'type'            	=> 'pricing_discount_manage_user_role',
				'id'				=> 'eh_pricing_discount_manage_user_role',
			);
		return $settings;
	}
	
	//function to generate price adjustment table
	public function pricing_admin_field_pricing_discount_manage_user_role($settings) {
		include( 'html-eh-price-adjustment-manage-user-role.php' );
	}
	
    public function update_settings( $current_section ) {
		global $current_section; 
		if( $current_section == '') {
			$options = $this->get_settings();
			woocommerce_update_options( $options );
			$this->user_adjustment_price = get_option('eh_pricing_discount_price_adjustment_options');
		}
		if( $current_section == 'manage-user-role' ) {
			$user_role_action = $_POST['pricing_discount_manage_user_roles'];
			$manage_role_status = '';
			if( $user_role_action == 'add_user_role' ) {
				$manage_role_status = $this->pricing_discount_add_user_role( $_POST['eh_woocommerce_pricing_discount_user_role_name'] );
			}
			if( ($user_role_action == 'remove_user_role') ) {
				if( isset( $_POST['pricing_discount_remove_user_role'] ) ) {
					$this->pricing_discount_remove_user_role( $_POST['pricing_discount_remove_user_role'] );
				} else {
					$status = __('Please select atleast one role to delete', 'eh-woocommerce-pricing-discount');
					wf_admin_notice::add_notice( $status, 'error' );
				}
			}
		}
    }
	
	//function to create user role
	public function pricing_discount_add_user_role( $user_role_name ) {
		global $wp_roles;
		$user_roles = $wp_roles->role_names;
		$new_user_role = str_replace(' ', '_', $user_role_name);
		try {
			if( ($new_user_role !='' && $user_role_name !='' ) && !( array_key_exists( $new_user_role, $user_roles ) ) ) {
				add_role( $new_user_role, $user_role_name, array( 'read' => true));
				$status = __('User role created successfully', 'eh-woocommerce-pricing-discount');
				wf_admin_notice::add_notice( $status, 'notice' );
			}else {
				$status = __('User role creation failed', 'eh-woocommerce-pricing-discount');
				wf_admin_notice::add_notice( $status, 'error' );
			}
		} catch (Exception $e) {
			wf_admin_notice::add_notice( $e, 'error' );
		}
	}
	
	//function to remove user role
	public function pricing_discount_remove_user_role( $remove_user_role ) {
		foreach ( $remove_user_role as $id => $status ) {
			try {
				remove_role($id);
				$status = __('User role deleted successfully', 'eh-woocommerce-pricing-discount');
				wf_admin_notice::add_notice( $status, 'notice' );
			} catch ( Exception $e ) {
				wf_admin_notice::add_notice( $e, 'error' );
			}
		}
	}
	
    public function get_settings() {
		global $current_section;
		global $wp_roles;
		$pricing_options = array(
			'Discount'      		=> __( 'Discount', 'eh-woocommerce-pricing-discount' ),
			'Makup'      		=> __( 'Markup', 'eh-woocommerce-pricing-discount' ), 
		);
		$price_sale_regular = array( 'Sale' => __( 'Sale Price', 'eh-woocommerce-pricing-discount' ), 
			'regular' => __( 'Regular Price', 'eh_woocommerce_pricing_discount_user_role_name' ));
		$price_suffix_options = array(
			'none'            => 'None',
			'general'         => 'General',
			'role_specific'   => 'Role Specific'
		);
		$user_roles = $wp_roles->role_names;
        $settings = array(
            'general_settings_section_title'			=> array(
                'name'				=> __( 'General Settings:', 'eh-woocommerce-pricing-discount' ),
                'type'				=> 'title',
                'desc'				=> '',
                'id'				=> 'eh_pricing_discount_section_title',
            ),
			'hide_regular_price'		=> array(
				'title'				=> __( 'Hide Regular Price', 'eh-woocommerce-pricing-discount' ),
				'type'				=> 'checkbox',
				'desc'				=> __( 'Enable', 'eh-woocommerce-pricing-discount' ),
				'css'				=> 'width:100%',
				'id'				=> 'eh_pricing_discount_hide_regular_price',
				'desc_tip' 			=> __( 'Check this option to hide product regular price.', 'eh-woocommerce-pricing-discount' ),
			),
			'general_settings_section_title_end' => array(
				'type'			=> 'sectionend',
				'id'			=> 'eh_pricing_discount_section_title'
            ),
			'eh_pricing_discount_unregistered_title' => array(
				'title' => __('Unregistered User Settings:', 'eh-woocommerce-pricing-discount'),
				'type' => 'title',
				'description' => '',
				'id'=> 'eh_pricing_discount_unregistered'
			),
			'cart_unregistered_user'		=> array(
				'title'				=> __( 'Remove Add to Cart', 'eh-woocommerce-pricing-discount' ),
				'type'				=> 'checkbox',
				'desc'				=> __( 'Enable', 'eh-woocommerce-pricing-discount' ),
				'css'				=> 'width:100%',
				'id'				=> 'eh_pricing_discount_cart_unregistered_user',
				'desc_tip' => __( 'Check this option to remove add to cart option for unregistered users.', 'eh-woocommerce-pricing-discount' ),
			),
			'cart_unregistered_user_text'		=> array(
				'title'				=> __( 'Placeholder Text', 'eh-woocommerce-pricing-discount' ),
				'type'				=> 'text',
				'desc'				=> __( "Enter the text you want to show when add to cart option is removed. Leave it empty if you don't want to show any placeholder text.", 'eh-woocommerce-pricing-discount' ),
				'css'				=> 'width:350px',
				'id'				=> 'eh_pricing_discount_cart_unregistered_user_text',
				'desc_tip' => true
			),
			'price_unregistered_user'		=> array(
				'title'				=> __( 'Hide Price', 'eh-woocommerce-pricing-discount' ),
				'type'				=> 'checkbox',
				'desc'				=> __( 'Enable', 'eh-woocommerce-pricing-discount' ),
				'css'				=> 'width:100%',
				'id'				=> 'eh_pricing_discount_price_unregistered_user',
				'desc_tip' => __( 'Check this option to hide product price for unregistered users.', 'eh-woocommerce-pricing-discount' ),
			),
			'price_unregistered_user_text'		=> array(
				'title'				=> __( 'Placeholder Text', 'eh-woocommerce-pricing-discount' ),
				'type'				=> 'text',
				'desc'				=> __( "Enter the text you want to show when price is removed. Leave it empty if you don't want to show any placeholder text.", 'eh-woocommerce-pricing-discount' ),
				'css'				=> 'width:350px',
				'id'				=> 'eh_pricing_discount_price_unregistered_user_text',
				'desc_tip' => true
			),
			'replace_cart_unregistered_user'		=> array(
				'title'				=> __( 'Replace Add to Cart', 'eh-woocommerce-pricing-discount' ),
				'type'				=> 'checkbox',
				'desc'				=> __( 'Enable', 'eh-woocommerce-pricing-discount' ),
				'css'				=> 'width:100%',
				'id'				=> 'eh_pricing_discount_replace_cart_unregistered_user',
				'desc_tip' => __( 'Check this option to replace add to cart option for unregistered users.', 'eh-woocommerce-pricing-discount' ),
			),
			'replace_cart_unregistered_user_text'		=> array(
				'title'				=> __( 'Replace Button Text', 'eh-woocommerce-pricing-discount' ),
				'type'				=> 'text',
				'desc'				=> __( "Enter the replace button text for add to cart.", 'eh-woocommerce-pricing-discount' ),
				'css'				=> 'width:350px',
				'id'				=> 'eh_pricing_discount_replace_cart_unregistered_user_text',
				'desc_tip' => true
			),
			'replace_cart_unregistered_user_url'		=> array(
				'title'				=> __( 'Replace URL', 'eh-woocommerce-pricing-discount' ),
				'type'				=> 'text',
				'desc'				=> __( "Enter the replace URL for add to cart button.", 'eh-woocommerce-pricing-discount' ),
				'css'				=> 'width:350px',
				'id'				=> 'eh_pricing_discount_replace_cart_unregistered_user_url',
				'desc_tip' => true
			),
			'eh_pricing_discount_unregistered_title_end' => array(
				'type'			=> 'sectionend',
				'id'			=> 'eh_pricing_discount_unregistered'
            ),
			'eh_pricing_discount_user_role_title' => array(
				'title' => __('User Role Specific Settings:', 'eh-woocommerce-pricing-discount'),
				'type' => 'title',
				'description' => '',
				'id'=> 'eh_pricing_discount_user_role'
			),
			'cart_user_role'		=> array(
				'title'				=> __( 'Hide Add to Cart', 'eh-woocommerce-pricing-discount' ),
				'type'				=> 'multiselect',
				'desc'				=> __( 'Select the user role for which you want to hide add to cart option.', 'eh-woocommerce-pricing-discount' ),
				'class'				=> 'chosen_select',
				'id'				=> 'eh_pricing_discount_cart_user_role',
				'options'         	=> $user_roles,
				'desc_tip' => true
			),
			'cart_user_role_text'		=> array(
				'title'				=> __( 'Placeholder Text', 'eh-woocommerce-pricing-discount' ),
				'type'				=> 'text',
				'desc'				=> __( "Enter the text you want to show when add to cart is removed. Leave it empty if you don't want to show any placeholder text", 'eh-woocommerce-pricing-discount' ),
				'css'				=> 'width:350px',
				'id'				=> 'eh_pricing_discount_cart_user_role_text',
				'desc_tip' => true
			),
			'price_user_role'		=> array(
				'title'				=> __( 'Hide Price', 'eh-woocommerce-pricing-discount' ),
				'type'				=> 'multiselect',
				'desc'				=> __( 'Select the user role for which you want to hide product price.', 'eh-woocommerce-pricing-discount' ),
				'class'				=> 'chosen_select',
				'id'				=> 'eh_pricing_discount_price_user_role',
				'options'         	=> $user_roles,
				'desc_tip' => true
			),
			'price_user_role_text'		=> array(
				'title'				=> __( 'Placeholder Text', 'eh-woocommerce-pricing-discount' ),
				'type'				=> 'text',
				'desc'				=> __( "Enter the text you want to show when price is removed. Leave it empty if you don't want to show any placeholder text", 'eh-woocommerce-pricing-discount' ),
				'css'				=> 'width:350px',
				'id'				=> 'eh_pricing_discount_price_user_role_text',
				'desc_tip' => true
			),
			'replace_cart_user_role'		=> array(
				'title'				=> __( 'Replace Add to Cart', 'eh-woocommerce-pricing-discount' ),
				'type'				=> 'multiselect',
				'desc'				=> __( 'Select the user role for which you want to replace add to cart.', 'eh-woocommerce-pricing-discount' ),
				'class'				=> 'chosen_select',
				'id'				=> 'eh_pricing_discount_replace_cart_user_role',
				'options'         	=> $user_roles,
				'desc_tip' => true
			),
			'replace_cart_user_role_text'		=> array(
				'title'				=> __( 'Replace Button Text', 'eh-woocommerce-pricing-discount' ),
				'type'				=> 'text',
				'desc'				=> __( "Enter the replace button text for add to cart.", 'eh-woocommerce-pricing-discount' ),
				'css'				=> 'width:350px',
				'id'				=> 'eh_pricing_discount_replace_cart_user_role_text',
				'desc_tip' => true
			),
			'replace_cart_user_role_url'		=> array(
				'title'				=> __( 'Replace URL', 'eh-woocommerce-pricing-discount' ),
				'type'				=> 'text',
				'desc'				=> __( "Enter the replace URL for add to cart button.", 'eh-woocommerce-pricing-discount' ),
				'css'				=> 'width:350px',
				'id'				=> 'eh_pricing_discount_replace_cart_user_role_url',
				'desc_tip' => true
			),
			'product_price_user_role'		=> array(
				'title'				=> __( 'Individual Product Adjustment', 'eh-woocommerce-pricing-discount' ),
				'type'				=> 'multiselect',
				'desc'				=> __( 'Select the user role for which you want to have individual product level price adjustment.', 'eh-woocommerce-pricing-discount' ),
				'class'				=> 'chosen_select',
				'id'				=> 'eh_pricing_discount_product_price_user_role',
				'options'         	=> $user_roles,
				'desc_tip' => true
			),

			'eh_pricing_discount_user_role_title_end' => array(
				'type'			=> 'sectionend',
				'id'			=> 'eh_pricing_discount_user_role'
            ),
			'eh_pricing_discount_adjustment_title' => array(
				'title' => __('Price Adjustment Settings:', 'eh-woocommerce-pricing-discount'),
				'type' => 'title',
				'description' => '',
				'id'=> 'eh_pricing_discount_adjustment'
			),

			'product_price_markup_discount'		=> array(
				'title'				=> __( ' Adjustment Type ', 'eh-woocommerce-pricing-discount' ),
				'type'				=> 'select',
				'desc'				=> __( 'Select the type of adjustment you want to have. This adjustment is applicable to individual product level price adjustment also.', 'eh-woocommerce-pricing-discount' ),
				'default'       	=> 'Discount',
				'id'				=> 'eh_pricing_product_price_markup_discount',
				'options'         	=> $pricing_options,
				'desc_tip' => true
			),
			'product_choose_sale_regular'		=> array(
				'title'				=> __( ' Price Adjustment On ', 'eh-woocommerce-pricing-discount' ),
				'type'				=> 'select',
				'desc'				=> __( 'Select the Price on which discount would be applicable .', 'eh-woocommerce-pricing-discount' ),
				'default'       	=> 'Sale Price',
				'id'				=> 'eh_product_choose_sale_regular',
				'options'         	=> $price_sale_regular,
				'desc_tip' => true
			),
			'wf_decimal_point'		=> array(
				'title'				=> __( 'Number of decimals', 'eh-woocommerce-pricing-discount' ),
				'type'				=> 'number',
				'desc'				=> __( "This sets the number of decimal points shown in displayed prices.", 'eh-woocommerce-pricing-discount' ),
				'id'				=> 'eh_pricing_discount_decimal_points',
				'default'			=> '2',
				'custom_attributes' => array(
					'min'  => 0,
					'step' => 1,
				),
				'desc_tip' => true
			),
			'price_adjustment_options' => array(
			'type'            	=> 'priceadjustmenttable',
			'id'				=> 'eh_pricing_discount_price_adjustment_options',
			),
			'eh_pricing_discount_adjustment_title_end' => array(
				'type'			=> 'sectionend',
				'id'			=> 'eh_pricing_discount_adjustment'
            ),
			'eh_pricing_discount_tax_title' => array(
				'title' => __('Tax Options:', 'eh-woocommerce-pricing-discount'),
				'type' => 'title',
				'description' => '',
				'id'=> 'eh_pricing_discount_tax'
			),
			'enable_tax_options' => array(
				'title'				=> __( 'Enable Tax Options', 'eh-woocommerce-pricing-discount' ),
				'type'            	=> 'checkbox',
				'desc'				=> __( 'Enable', 'eh-woocommerce-pricing-discount' ),
				'css'				=> 'width:100%',
				'id'				=> 'eh_pricing_discount_enable_tax_options',
				'desc_tip' 			=> __( 'Check this option to enable user role specific tax options.', 'eh-woocommerce-pricing-discount' ),
			),
			'price_tax_options' 	=> array(
				'type'            	=> 'taxoptiontable',
				'id'				=> 'eh_pricing_discount_price_tax_options',
			),
			'eh_pricing_discount_tax_title_end' => array(
				'type'			=> 'sectionend',
				'id'			=> 'eh_pricing_discount_tax'
            ),
			'eh_pricing_discount_price_suffix_title' => array(
				'title' => __('Price Suffix Options:', 'eh-woocommerce-pricing-discount'),
				'type' => 'title',
				'description' => '',
				'id'=> 'eh_pricing_discount_price_suffix'
			),
			'price_suffix' 			=> array(
				'title'				=> __( 'Price Suffix', 'eh-woocommerce-pricing-discount' ),
				'type'            	=> 'select',
				'desc'				=> __( 'Select the price suffix option you want to have.', 'eh-woocommerce-pricing-discount' ),
				'id'				=> 'eh_pricing_discount_enable_price_suffix',
				'default'           => 'none',
				'options'           => $price_suffix_options,
				'desc_tip' => true
			),
			'general_price_suffix'		=> array(
				'title'				=> __( 'Suffix Text', 'eh-woocommerce-pricing-discount' ),
				'type'				=> 'text',
				'desc'				=> __( "Enter the text you want to suffix with the price.", 'eh-woocommerce-pricing-discount' ),
				'css'				=> 'width:350px',
				'id'				=> 'eh_pricing_discount_price_general_price_suffix',
				'desc_tip' => true
			),
			'role_price_suffix' 	=> array(
				'type'            	=> 'rolepricesuffix',
				'id'				=> 'eh_pricing_discount_role_price_suffix',
			),
			'eh_pricing_discount_price_suffix_title_end' => array(
				'type'			=> 'sectionend',
				'id'			=> 'eh_pricing_discount_price_suffix'
            ),
        );
        return apply_filters( 'eh_pricing_discount_general_settings', $settings );
    }
	
	//function to generate price adjustment table
	public function pricing_admin_field_priceadjustmenttable($settings) {
		include( 'html-eh-price-adjustment.php' );
	}
	
	//function to generate tax options table
	public function pricing_admin_field_taxoptiontable($settings) {
		include( 'html-eh-tax-options.php' );
	}
	
	//function to generate tax price suffix table
	public function pricing_admin_field_rolepricesuffix($settings) {
		include( 'html-eh-price-suffix.php' );
	}
	
	//function to add a prodcut tab in product page
	public function add_product_tab($product_data_tabs ) {
		$product_data_tabs['product_price_adjustment'] = array(
			'label' => __( 'Role Based Pricing', 'eh-woocommerce-pricing-discount' ),
			'target' => 'product_price_adjustment_data',
			'class' => Array(),
		);
		return $product_data_tabs;
	}
	

	public function add_price_adjustment_data_fields() {
		global $woocommerce, $post;
		$settings = array('hide_regular_price'		=> array(
				'title'				=> __( 'Hide Regular Price', 'eh-woocommerce-pricing-discount' ),
				'type'				=> 'check',
				'desc'				=> __( 'Check this option to hide product regular price', 'eh-woocommerce-pricing-discount' ),
				'css'				=> 'width:100%',
				'id'				=> 'eh_pricing_discount_hide_regular_price',
			)
		);
		?>
		<!-- id below must match target registered in above add_my_custom_product_data_tab function -->
		<div id="product_price_adjustment_data" class="panel woocommerce_options_panel hidden">
			<?php include( 'html-eh-product-price-adjustment.php' ); ?>
		</div>
		<?php
	}
	
	function add_price_extra_fields() {
		global $woocommerce, $post;
		$product = new WC_Product( get_the_ID() );
		$user_roles = get_option('eh_pricing_discount_product_price_user_role');
		if(is_array($user_roles) && !empty($user_roles)) {
			echo '<div id="general_role_based_price" style="padding: 3%; >';
			include( 'html-eh-product-role-based-price.php' );
			echo '</div>';
		}else{
			echo '<div class="clearfix"></div>';
		}
	}
	
	public function woo_add_custom_general_fields_save( $post_id )
	{
		//to update product hide add to cart for unregistered users
		$woocommerce_adjustment_field = (isset($_POST['product_adjustment_hide_addtocart_unregistered']) && ($_POST['product_adjustment_hide_addtocart_unregistered'] == 'on')) ? 'yes' : 'no' ;
		if( !empty( $woocommerce_adjustment_field ) ){
			update_post_meta( $post_id, 'product_adjustment_hide_addtocart_unregistered', $woocommerce_adjustment_field );	
		}
		
		//to update product role based hide price
		update_post_meta( $post_id, 'eh_pricing_adjustment_product_addtocart_user_role', $_POST['eh_pricing_adjustment_product_addtocart_user_role'] );	
		
		//to update product price for unregistered users
		$woocommerce_adjustment_field = (isset($_POST['product_adjustment_hide_price_unregistered']) && ($_POST['product_adjustment_hide_price_unregistered'] == 'on')) ? 'yes' : 'no' ;
		if( !empty( $woocommerce_adjustment_field ) ){
			update_post_meta( $post_id, 'product_adjustment_hide_price_unregistered', $woocommerce_adjustment_field );	
		}
		//to update product based price adjustment
		$woocommerce_adjustment_field = (isset($_POST['product_based_price_adjustment']) && ($_POST['product_based_price_adjustment'] == 'on')) ? 'yes' : 'no' ;
		if( !empty( $woocommerce_adjustment_field ) ){
			update_post_meta( $post_id, 'product_based_price_adjustment', $woocommerce_adjustment_field );	
		}
		//to update product role based hide price
		update_post_meta( $post_id, 'eh_pricing_adjustment_product_price_user_role', $_POST['eh_pricing_adjustment_product_price_user_role'] );
		//to update the product role based adjustment
		$woocommerce_adjustment_field = $_POST['product_price_adjustment'];
		if( !empty( $woocommerce_adjustment_field ) ){
			update_post_meta( $post_id, 'product_price_adjustment', $woocommerce_adjustment_field );	
		}
		//to update the product role based price
		$woocommerce_price_field = $_POST['product_role_based_price'];
		if( !empty( $woocommerce_price_field ) ){
			update_post_meta( $post_id, 'product_role_based_price', $woocommerce_price_field );
		}
	}
	
	//function to generate price adjustment table
	public function pricing_category_adjustment_fields( $tag ) {
		$t_id = $tag->term_id;
    $cat_meta = get_option( "category_$t_id" );
	print_r($cat_meta);
	print_r($t_id);
	print_r($tag);
?>
<tr class="form-field">
    <th scope="row" valign="top"><label for="meta-color"><?php _e('Category Name Background Color'); ?></label></th>
    <td>
        <div id="colorpicker">
            <input type="text" name="cat_meta[catBG]" class="colorpicker" size="3" style="width:20%;" value="<?php echo (isset($cat_meta['catBG'])) ? $cat_meta['catBG'] : '#fff'; ?>" />
        </div>
            <br />
        <span class="description"><?php _e(''); ?></span>
            <br />
        </td>
</tr>
<?php
	}
	
	public function variation_settings_fields( $loop, $variation_data,$variation )
	{
		$user_roles = get_option('eh_pricing_discount_product_price_user_role');
		if(is_array($user_roles) && !empty($user_roles)) {
			include( 'html-eh-variation-product-role-based-price.php' );
		}
	}
	
	public function save_variable_fields() {
		if (isset( $_POST['variable_sku'] ) ) {
			$variable_sku          = $_POST['variable_sku'];
			$variable_post_id      = $_POST['variable_post_id'];
			$role_based_price = $_POST['product_role_based_price'];
			for ( $i = 0; $i <= (sizeof( $variable_sku )); $i++ ) {
				$variation_id = (int) $variable_post_id[$i];
				if ( isset( $role_based_price[$i] ) ) {
					update_post_meta( $variation_id, 'product_role_based_price', $role_based_price[$i] );
				}
			}
		}
	}
}

new Eh_Pricing_Discount_Settings();