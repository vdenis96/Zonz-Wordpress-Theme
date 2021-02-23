<?php
/**
 * GENERAL ARRAY OPTIONS
 */

$general = array(

	'general'  => array(

		array(
			'title' => __( 'General Options', 'ywcl' ),
			'type' => 'title',
			'desc' => '',
			'id' => 'yith-wccl-general-options'
		),

		array(
			'title'    => __( 'Attribute behavior', 'yith-wccl' ),
			'desc'     => __( 'Choose attribute style after selection.', 'yith-wccl' ),
			'id'       => 'yith-wccl-attributes-style',
			'default'  => 'hide',
			'type'     => 'radio',
			'options'  => array(
				'hide'  => __( 'Hide attributes', 'yith-wccl' ),
				'grey'  => __( 'Blur attributes', 'yith-wccl' )
			),
			'desc_tip' =>  true
		),

		array(
			'id'        => 'yith-wccl-enable-tooltip',
			'title'     => __( 'Enable Tooltip', 'ywcl' ),
			'type'      => 'checkbox',
			'desc'      => __( 'Enable tooltip for attributes', 'ywcl' ),
			'default'   => 'yes'
		),

		array(
			'id'        => 'yith-wccl-tooltip-position',
			'title'     => __( 'Tooltip position', 'ywcl' ),
			'desc'      => __( 'Select tooltip position', 'ywcl' ),
			'type'      => 'select',
			'options'   => array(
				'top'       => __( 'Top', 'ywcl' ),
				'bottom'    => __( 'Bottom', 'ywcl' )
			),
			'default'   => 'top'
		),

		array(
			'id'        => 'yith-wccl-tooltip-animation',
			'title'     => __( 'Tooltip animation', 'ywcl' ),
			'desc'      => __( 'Select tooltip animation', 'ywcl' ),
			'type'      => 'select',
			'options'   => array(
				'fade'     => __( 'Fade in', 'ywcl' ),
				'slide'    => __( 'Slide in', 'ywcl' )
			),
			'default'   => 'fade'
		),

		array(
			'id'        => 'yith-wccl-tooltip-background',
			'title'     => __( 'Tooltip background', 'ywcl' ),
			'desc'      => __( 'Select tooltip background', 'ywcl' ),
			'type'      => 'color',
			'default'   => '#222222'
		),

		array(
			'id'        => 'yith-wccl-tooltip-text-color',
			'title'     => __( 'Tooltip text color', 'ywcl' ),
			'desc'      => __( 'Select tooltip text color', 'ywcl' ),
			'type'      => 'color',
			'default'   => '#ffffff'
		),

		array(
			'id'        => 'yith-wccl-enable-description',
			'title'     => __( 'Show Attribute Description', 'ywcl' ),
			'type'      => 'checkbox',
			'desc'      => __( 'Choose to show description below each attribute in single product page', 'ywcl' ),
			'default'   => 'yes'
		),

		array(
			'id'        => 'yith-wccl-enable-in-loop',
			'title'     => __( 'Enable plugin in archive page', 'ywcl' ),
			'type'      => 'checkbox',
			'desc'      => __( 'Choose to show attribute selection in archive shop page', 'ywcl' ),
			'default'   => 'yes'
		),

		array(
			'id'        => 'yith-wccl-position-in-loop',
			'title'     => __( 'Form Position', 'ywcl' ),
			'desc'      => __( 'Choose the form position in archive shop page', 'ywcl' ),
			'type'      => 'select',
			'options'   => array(
				'before'    => __( 'Before add to cart button', 'ywcl' ),
				'after'    => __( 'After add to cart button', 'ywcl' )
			),
			'default'   => 'after'
		),

		array(
			'id'        => 'yith-wccl-add-to-cart-label',
			'title'     => __( 'Label for \'Add to cart\' button', 'ywcl' ),
			'type'      => 'text',
			'desc'      => __( 'Label for \'Add to cart\' button when a variation is selected from archive page', 'ywcl' ),
			'default'   => __( 'Add to cart', 'ywcl' )
		),

		array(
			'type'      => 'sectionend',
			'id'        => 'yith-wccl-general-options'
		)
	)
);

return apply_filters( 'yith_wccl_panel_general_options', $general );