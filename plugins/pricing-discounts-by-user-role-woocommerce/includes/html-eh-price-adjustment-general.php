<?php 
	global $current_section;
		global $wp_roles;
		$pricing_options = array(
			'Discount'      		=> __( 'Discount', 'eh-woocommerce-pricing-discount' ),
			'Makup'      		=> __( 'Markup', 'eh-woocommerce-pricing-discount' ), 
		);
		$user_roles = array('none' => 'None') + $wp_roles->role_names;
	$settings = array(
		'section_title'			=> array(
			'name'				=> __( 'Role Based Pricing and Discount Settings', 'eh-woocommerce-pricing-discount' ),
			'type'				=> 'title',
			'desc'				=> '',
			'id'				=> 'eh_pricing_discount_eh_pricing_discount_section_title',
		),
		'cart_unregistered_user'		=> array(
			'title'				=> __( 'Remove add to cart button for unregistered users', 'eh-woocommerce-pricing-discount' ),
			'type'				=> 'checkbox',
			'desc'				=> __( 'Check this option to remove add to cart button for unregistered users', 'eh-woocommerce-pricing-discount' ),
			'css'				=> 'width:100%',
			'id'				=> 'eh_pricing_discount_cart_unregistered_user',
		),
		'cart_unregistered_user_text'		=> array(
			'title'				=> __( 'Hide text for add to cart button for unregistered users', 'eh-woocommerce-pricing-discount' ),
			'type'				=> 'text',
			'desc'				=> __( 'Enter the text you want to show when add to cart is removed. Leave empty for no text', 'eh-woocommerce-pricing-discount' ),
			'css'				=> 'width:100%',
			'id'				=> 'eh_pricing_discount_cart_unregistered_user_text',
		),
		'cart_user_role'		=> array(
			'title'				=> __( 'Hide add to cart button based on user role', 'eh-woocommerce-pricing-discount' ),
			'type'				=> 'multiselect',
			'desc'				=> __( 'Select the user role for which you want to hide add to cart button', 'eh-woocommerce-pricing-discount' ),
			'css'				=> 'width:100%',
			'default'       	=> 'none',
			'id'				=> 'eh_pricing_discount_cart_user_role',
			'options'         	=> $user_roles
		),
		'cart_user_role_text'		=> array(
			'title'				=> __( 'Hide text for add to cart button based on user role', 'eh-woocommerce-pricing-discount' ),
			'type'				=> 'text',
			'desc'				=> __( 'Enter the text you want to show when add to cart is removed. Leave empty for no text', 'eh-woocommerce-pricing-discount' ),
			'css'				=> 'width:100%',
			'id'				=> 'eh_pricing_discount_cart_user_role_text',
		),
		'price_unregistered_user'		=> array(
			'title'				=> __( 'Hide price for unregistered users', 'eh-woocommerce-pricing-discount' ),
			'type'				=> 'checkbox',
			'desc'				=> __( 'Check this option to hide product price for unregistered users', 'eh-woocommerce-pricing-discount' ),
			'css'				=> 'width:100%',
			'id'				=> 'eh_pricing_discount_price_unregistered_user',
		),
		'price_unregistered_user_text'		=> array(
			'title'				=> __( 'Hide text for price for unregistered users', 'eh-woocommerce-pricing-discount' ),
			'type'				=> 'text',
			'desc'				=> __( 'Enter the text you want to show when price is removed. Leave empty for no text', 'eh-woocommerce-pricing-discount' ),
			'css'				=> 'width:100%',
			'id'				=> 'eh_pricing_discount_price_unregistered_user_text',
		),
		'price_user_role'		=> array(
			'title'				=> __( ' Hide product price based on user role', 'eh-woocommerce-pricing-discount' ),
			'type'				=> 'multiselect',
			'desc'				=> __( 'Select the user role for which you want to hide product price.', 'eh-woocommerce-pricing-discount' ),
			'css'				=> 'width:100%',
			'default'       	=> 'none',
			'id'				=> 'eh_pricing_discount_price_user_role',
			'options'         	=> $user_roles
		),
		'price_user_role_text'		=> array(
			'title'				=> __( 'Hide text for product price based on user role', 'eh-woocommerce-pricing-discount' ),
			'type'				=> 'text',
			'desc'				=> __( 'Enter the text you want to show when price is removed. Leave empty for no text', 'eh-woocommerce-pricing-discount' ),
			'css'				=> 'width:100%',
			'id'				=> 'eh_pricing_discount_price_user_role_text',
		),
		'product_price_user_role'		=> array(
			'title'				=> __( ' Enable price adjustment for individual product', 'eh-woocommerce-pricing-discount' ),
			'type'				=> 'multiselect',
			'desc'				=> __( 'Select the user role for which you want to have product price based on user role.', 'eh-woocommerce-pricing-discount' ),
			'css'				=> 'width:100%',
			'default'       	=> 'none',
			'id'				=> 'eh_pricing_discount_product_price_user_role',
			'options'         	=> $user_roles
		),
		'hide_regular_price'		=> array(
			'title'				=> __( 'Hide Regular Price', 'eh-woocommerce-pricing-discount' ),
			'type'				=> 'checkbox',
			'desc'				=> __( 'Check this option to hide product regular price', 'eh-woocommerce-pricing-discount' ),
			'css'				=> 'width:100%',
			'id'				=> 'eh_pricing_discount_hide_regular_price',
		),
		'product_price_markup_discount'		=> array(
			'title'				=> __( ' Adjustment type ', 'eh-woocommerce-pricing-discount' ),
			'type'				=> 'select',
			'desc'				=> __( 'Select the type of adjustment you want to have.', 'eh-woocommerce-pricing-discount' ),
			'default'       	=> 'Discount',
			'id'				=> 'eh_pricing_product_price_markup_discount',
			'options'         	=> $pricing_options
		),
		'section_end' => array(
			'type'			=> 'sectionend',
			'id'			=> 'eh_pricing_discount_eh_pricing_discount_section_end'
		),
		'section_title'			=> array(
			'name'				=> __( 'Role Based Pricing and Discount Settings', 'eh-woocommerce-pricing-discount' ),
			'type'				=> 'title',
			'desc'				=> '',
			'id'				=> 'eh_pricing_discount_user_role_section_title',
		),
		'section_end' => array(
			'type'			=> 'sectionend',
			'id'			=> 'eh_pricing_discount_user_role_section_end'
		),
	);
?>