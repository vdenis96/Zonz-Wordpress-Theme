<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
class Woo_Bot_For_Woocommerce_Settings {
	public static function init() {
		add_filter( 'woocommerce_settings_tabs_array', __CLASS__ . '::add_settings_tab', 50 );
		add_action( 'woocommerce_settings_tabs_woo_bot_for_woocommerce', __CLASS__ . '::settings_tab' );
		add_action( 'woocommerce_update_options_woo_bot_for_woocommerce', __CLASS__ . '::update_settings' );
	}

	public static function add_settings_tab( $settings_tabs ) {
		$settings_tabs['woo_bot_for_woocommerce'] = __( 'Woo Bot', 'woo-bot-for-woocommerce' );
		return $settings_tabs;
	}

	public static function settings_tab() {
		woocommerce_admin_fields( self::get_settings() );
	}

	public static function update_settings() {
		woocommerce_update_options( self::get_settings() );
	}

	public static function get_settings() {

		$settings = array(
			'section_title' => array(
				'name'     => __( 'Woo Bot Settings', 'woo-bot-for-woocommerce' ),
				'type'     => 'title',
				'desc'     => 'Woo Bot Configuration.',
				'id'       => 'woo_bot_for_woocommerce_section_title',
			),
			'woo_bot_chat_icon' => array(
				'name' => __( 'Chat Icon', 'woo-bot-for-woocommerce' ),
				'type' => 'select',
				'desc' => __( 'Select icon you want to display as woo bot popup. Refer <a href="https://developer.wordpress.org/resource/dashicons/">dashicon</a> for complete icon list', 'woo-bot-for-woocommerce' ),
				'desc_tip' => true,
				'id'   => 'woo_bot_for_woocommerce_woo_bot_chat_icon',
				'options' => woo_bot_dashicons(),
				'default' => 'format-chat'
			),
			'chat_icon_color' => array(
				'name' => __( 'Chat Icon Color', 'woo-bot-for-woocommerce' ),
				'type' => 'text',
				'desc' => __( 'Select chat icon color. It applies to chat icon.', 'woo-bot-for-woocommerce' ),
				'desc_tip' => true,
				'class' => 'wp-colorpicker',
				'id'   => 'woo_bot_for_woocommerce_chat_icon_color',
				'default' => '#ffffff',
			),
			'chat_icon_background_color' => array(
				'name' => __( 'Chat Icon Background', 'woo-bot-for-woocommerce' ),
				'type' => 'text',
				'desc' => __( 'Select chat icon background color. It applies to chat icon background.', 'woo-bot-for-woocommerce' ),
				'desc_tip' => true,
				'class' => 'wp-colorpicker',
				'id'   => 'woo_bot_for_woocommerce_chat_icon_background_color',
				'default' => '#333333',
			),
			'woo_bot_chat_theme' => array(
				'name' => __( 'Chat Theme', 'woo-bot-for-woocommerce' ),
				'type' => 'radio',
				'desc' => __( '<strong>Light</strong> - chat popup with light background.<br><br><strong>Dark</strong> - chat popup with dark background.<br><br>Default: Light.', 'woo-bot-for-woocommerce' ),
				'id'   => 'woo_bot_for_woocommerce_woo_bot_chat_theme',
				'desc_tip' => true,
				'options' => array(
					'light' => __( 'Light', 'woo-bot-for-woocommerce' ),
					'dark' => __( 'Dark', 'woo-bot-for-woocommerce' ),
				),
				'default' => 'light',
			),
			'woo_bot_chat_image' => array(
				'name' => __( 'Chat Background Image', 'woo-bot-for-woocommerce' ),
				'type' => 'text',
				'desc' => __( '<small>Select background image for popup chat.</small>', 'woo-bot-for-woocommerce' ),
				'desc_tip' => true,
				'placeholder' => 'Click here to upload image',
				'id'   => 'woo_bot_for_woocommerce_woo_bot_chat_image',
				'default' => '' ,
			),
			'welcome_message' => array(
				'name' => __( 'Welcome Message', 'woo-bot-for-woocommerce' ),
				'type' => 'textarea',
				'css' => 'height:100px;',
				'desc' => __( '<small>Notification welcome message.</small>', 'woo-bot-for-woocommerce' ),
				'desc_tip' => true,
				'id'   => 'woo_bot_for_woocommerce_welcome_message',
				'default' => 'Welcome to <strong>' . esc_attr( get_bloginfo('name') ) . '</strong>!',
			),
			'no_answer_message' => array(
				'name' => __( 'Fallback Message', 'woo-bot-for-woocommerce' ),
				'type' => 'textarea',
				'css' => 'height:100px;',
				'desc' => __( '<small>The message that appears when a user types something to the chatbot and answer is not available to respond to.</small>', 'woo-bot-for-woocommerce' ),
				'desc_tip' => true,
				'id'   => 'woo_bot_for_woocommerce_no_answer_message',
				'default' => "Sorry! We didn’t understand.\n\nCan you be more specific\n[wb_option name='Product Search'] [wb_option name='Send Us Email'] [wb_option name='Call Us']",
			),
			'section_configuration_end' => array(
				'type' => 'sectionend',
				'id' => 'woo_bot_for_woocommerce_section_configuration_end',
			),
			// fields for exit intent popup
			'section_capture_visitor_information' => array(
				'name'     => __( 'Capture Visitor Information', 'woo-bot-for-woocommerce' ),
				'type'     => 'title',
				'desc'     => 'Questions to capture visitor information from chat bot.',
				'id'       => 'woo_bot_for_woocommerce_section_capture_visitor_information',
			),
			'capture_name_question' => array(
				'name' => __( 'Question to Capture Visitor Name', 'woo-bot-for-woocommerce' ),
				'type' => 'text',
				'desc' => __( '<small>Question to ask visitor name.</small>', 'woo-bot-for-woocommerce' ),
				'desc_tip' => true,
				'id'   => 'woo_bot_for_woocommerce_capture_name_question',
				'default' => 'May I have your name, please?',
			),
			'enable_capture_email' => array(
				'name' => __( 'Capture Visitor Email', 'woo-bot-for-woocommerce' ),
				'type' => 'checkbox',
				'desc' => __( '<small>Check this box to ask visitor about his/her email on chatbot.</small>', 'woo-bot-for-woocommerce' ),
				'desc_tip' => false,
				'id' => 'woo_bot_for_woocommerce_enable_capture_email',
				'default' => 'yes',
			),
			'capture_email_question' => array(
				'name' => __( 'Question to Capture Visitor Email', 'woo-bot-for-woocommerce' ),
				'type' => 'text',
				'desc' => __( '<small>Question to ask visitor email. [wb_name] will be replaced with visitor name.</small>', 'woo-bot-for-woocommerce' ),
				'desc_tip' => true,
				'id'   => 'woo_bot_for_woocommerce_capture_email_question',
				'default' => 'May I have your email as well [wb_name]?',
			),
			'capture_thank_you_message' => array(
				'name' => __( 'Thank you message after capturing the information', 'woo-bot-for-woocommerce' ),
				'type' => 'text',
				'desc' => __( '<small>This message will be shown after capturing the information. [wb_name] will be replaced with visitor name.</small>', 'woo-bot-for-woocommerce' ),
				'desc_tip' => true,
				'id'   => 'woo_bot_for_woocommerce_capture_thank_you_message',
				'default' => 'Thank you [wb_name] for providing the information. How can I help you?',
			),
			'capture_fallback_message' => array(
				'name' => __( 'Fallback message if invalid information provided by visitor', 'woo-bot-for-woocommerce' ),
				'type' => 'text',
				'desc' => __( '<small>This message will be shown when visitor not provide valid email address.</small>', 'woo-bot-for-woocommerce' ),
				'desc_tip' => true,
				'id'   => 'woo_bot_for_woocommerce_capture_fallback_message',
				'default' => 'No worries, we can still proceed. How can I help you?',
			),
			'section_capture_visitor_information_end' => array(
				'type' => 'sectionend',
				'id' => 'woo_bot_for_woocommerce_section_capture_visitor_information_end',
			),
			// fields for exit intent popup
			'section_exit_intent_trigger' => array(
				'name'     => __( 'Exit Intent Trigger', 'woo-bot-for-woocommerce' ),
				'type'     => 'title',
				'desc'     => 'You can offer coupon code or ask for feedback as soon as visitor intend to leave the site.',
				'id'       => 'woo_bot_for_woocommerce_section_exit_intent_trigger',
			),
			'enable_exit_intent_trigger' => array(
				'name' => __( 'Open Chat on Exit Intent', 'woo-bot-for-woocommerce' ),
				'type' => 'checkbox',
				'desc' => __( '<small>Check this box to open chat bot as soon visitor try to close browser tab / window to leave your website.</small>', 'woo-bot-for-woocommerce' ),
				'desc_tip' => false,
				'id' => 'woo_bot_for_woocommerce_enable_exit_intent_trigger',
				'default' => 'yes',
			),
			'exit_intent_message' => array(
				'name' => __( 'Exit Intent Message', 'woo-bot-for-woocommerce' ),
				'type' => 'textarea',
				'css' => 'height:150px;',
				'desc' => __( '<small>Here you can offer coupon code or ask for feedback before visitor leave the site.</small>', 'woo-bot-for-woocommerce' ),
				'desc_tip' => true,
				'id'   => 'woo_bot_for_woocommerce_exit_intent_message',
				'default' => "We’re always working to make our website better. If you have a moment to spare, would you be willing to send us feedback on <a href='mailto:" . get_option( 'admin_email' ) . "'>" . get_option( 'admin_email' ) . '</a>? We’d greatly appreciate your feedback.',
			),
			'section_exit_intent_trigger_end' => array(
				'type' => 'sectionend',
				'id' => 'woo_bot_for_woocommerce_section_exit_intent_trigger_end',
			),
			// fields for notification alerts
			'section_enable_notification' => array(
				'name'     => __( 'Notification Email', 'woo-bot-for-woocommerce' ),
				'type'     => 'title',
				'desc'     => 'New question notification email alert configuration.',
				'id'       => 'woo_bot_for_woocommerce_section_enable_notification',
			),
			'enable_notification_alert' => array(
				'name' => __( 'Enable Notification Alert', 'woo-bot-for-woocommerce' ),
				'type' => 'checkbox',
				'desc' => __( '<small>Check this box to enable notification alert for new questions.</small>', 'woo-bot-for-woocommerce' ),
				'desc_tip' => false,
				'id' => 'woo_bot_for_woocommerce_enable_notification_alert',
				'default' => 'yes',
			),
			'notification_email_subject' => array(
				'name' => __( 'Email Subject', 'woo-bot-for-woocommerce' ),
				'type' => 'text',
				'desc' => __( '<small>Email subject line for the new question notification alert.</small>', 'woo-bot-for-woocommerce' ),
				'desc_tip' => true,
				'id'   => 'woo_bot_for_woocommerce_notification_email_subject',
				'default' => 'New Chat Bot Question - ' . get_bloginfo('name') ,
			),
			'notification_from_email' => array(
				'name' => __( 'From Email', 'woo-bot-for-woocommerce' ),
				'type' => 'text',
				'desc' => __( '<small>Notification mail sent from email address.</small>', 'woo-bot-for-woocommerce' ),
				'desc_tip' => true,
				'id'   => 'woo_bot_for_woocommerce_notification_from_email',
				'default' => get_option( 'admin_email' ) ,
			),
			'notification_to_email' => array(
				'name' => __( 'To Email', 'woo-bot-for-woocommerce' ),
				'type' => 'email',
				'desc' => __( '<small>Notification mail sent to email address.</small>', 'woo-bot-for-woocommerce' ),
				'desc_tip' => true,
				'id'   => 'woo_bot_for_woocommerce_notification_to_email',
				'default' => get_option( 'admin_email' ) ,
			),
			'notification_mail_body' => array(
				'name' => __( 'Email Body', 'woo-bot-for-woocommerce' ),
				'type' => 'textarea',
				'css' => 'height:100px;',
				'desc' => __( '<small>Notification email body. [woo_bot_notification_body] will be replaced with the user message.</small>', 'woo-bot-for-woocommerce' ),
				'desc_tip' => true,
				'id'   => 'woo_bot_for_woocommerce_notification_mail_body',
				'default' => "You have received new chat bot question. Please see below the question:\n\n[woo_bot_notification_body]" ,
			),
			'section_woo_bot_notification_end' => array(
				'type' => 'sectionend',
				'id' => 'woo_bot_for_woocommerce_section_woo_bot_notification_end',
			),
		);
		return apply_filters( 'wc_woo_bot_for_woocommerce_settings', $settings );
	}

}
Woo_Bot_For_Woocommerce_Settings::init();

