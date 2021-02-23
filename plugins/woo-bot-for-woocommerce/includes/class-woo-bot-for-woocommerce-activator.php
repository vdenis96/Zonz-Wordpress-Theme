<?php

/**
 * Fired during plugin activation
 *
 * @link       https://wp1.co
 * @since      1.0.0
 *
 * @package    Woo_Bot_For_Woocommerce
 * @subpackage Woo_Bot_For_Woocommerce/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Woo_Bot_For_Woocommerce
 * @subpackage Woo_Bot_For_Woocommerce/includes
 */
class Woo_Bot_For_Woocommerce_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		if ( ! get_option( 'woo_bot_for_woocommerce_woo_bot_chat_icon' ) ) {
			update_option( 'woo_bot_for_woocommerce_woo_bot_chat_icon', 'format-chat' );
		}
		if ( ! get_option( 'woo_bot_for_woocommerce_chat_icon_color' ) ) {
			update_option( 'woo_bot_for_woocommerce_chat_icon_color', '#ffffff' );
		}
		if ( ! get_option( 'woo_bot_for_woocommerce_chat_icon_background_color' ) ) {
			update_option( 'woo_bot_for_woocommerce_chat_icon_background_color', '#333333' );
		}
		if ( ! get_option( 'woo_bot_for_woocommerce_woo_bot_chat_theme' ) ) {
			update_option( 'woo_bot_for_woocommerce_woo_bot_chat_theme', 'light' );
		}
		if ( ! get_option( 'woo_bot_for_woocommerce_woo_bot_chat_image' ) ) {
			update_option( 'woo_bot_for_woocommerce_woo_bot_chat_image', '' );
		}
		if ( ! get_option( 'woo_bot_for_woocommerce_welcome_message' ) ) {
			update_option( 'woo_bot_for_woocommerce_welcome_message', __( 'Welcome to <strong>' . esc_attr( get_bloginfo('name') ) . '</strong>!', 'woo-bot-for-woocommerce' ) );
		}
		if ( ! get_option( 'woo_bot_for_woocommerce_no_answer_message' ) ) {
			update_option( 'woo_bot_for_woocommerce_no_answer_message', __( "Sorry! We didn’t understand.\n\nCan you be more specific\n[wb_option name='Product Search'] [wb_option name='Send Us Email'] [wb_option name='Call Us']", 'woo-bot-for-woocommerce' ) );
		}
		if ( ! get_option( 'woo_bot_for_woocommerce_capture_name_question' ) ) {
			update_option( 'woo_bot_for_woocommerce_capture_name_question', __( 'May I have your name, please?', 'woo-bot-for-woocommerce' ) );
		}
		if ( ! get_option( 'woo_bot_for_woocommerce_enable_capture_email' ) ) {
			update_option( 'woo_bot_for_woocommerce_enable_capture_email', 'yes' );
		}
		if ( ! get_option( 'woo_bot_for_woocommerce_capture_email_question' ) ) {
			update_option( 'woo_bot_for_woocommerce_capture_email_question', __( 'May I have your email as well [wb_name]?', 'woo-bot-for-woocommerce' ) );
		}
		if ( ! get_option( 'woo_bot_for_woocommerce_capture_thank_you_message' ) ) {
			update_option( 'woo_bot_for_woocommerce_capture_thank_you_message', __( 'Thank you [wb_name] for providing the information. How can I help you?', 'woo-bot-for-woocommerce' ) );
		}
		if ( ! get_option( 'woo_bot_for_woocommerce_capture_fallback_message' ) ) {
			update_option( 'woo_bot_for_woocommerce_capture_fallback_message', __( 'No worries, we can still proceed. How can I help you?', 'woo-bot-for-woocommerce' ) );
		}
		if ( ! get_option( 'woo_bot_for_woocommerce_enable_exit_intent_trigger' ) ) {
			update_option( 'woo_bot_for_woocommerce_enable_exit_intent_trigger', 'yes' );
		}
		if ( ! get_option( 'woo_bot_for_woocommerce_exit_intent_message' ) ) {
			update_option( 'woo_bot_for_woocommerce_exit_intent_message', __( "We’re always working to make our website better. If you have a moment to spare, would you be willing to send us feedback on <a href='mailto:" . get_option( 'admin_email' ) . "'>" . get_option( 'admin_email' ) . '</a>? We’d greatly appreciate your feedback.', 'woo-bot-for-woocommerce' ) );
		}
		if ( ! get_option( 'woo_bot_for_woocommerce_enable_notification_alert' ) ) {
			update_option( 'woo_bot_for_woocommerce_enable_notification_alert', 'yes' );
		}
		if ( ! get_option( 'woo_bot_for_woocommerce_notification_email_subject' ) ) {
			update_option( 'woo_bot_for_woocommerce_notification_email_subject', __( 'New Chat Bot Question - ' . get_bloginfo('name'), 'woo-bot-for-woocommerce' ) );
		}
		if ( ! get_option( 'woo_bot_for_woocommerce_notification_from_email' ) ) {
			update_option( 'woo_bot_for_woocommerce_notification_from_email', get_option( 'admin_email' ) );
		}
		if ( ! get_option( 'woo_bot_for_woocommerce_notification_to_email' ) ) {
			update_option( 'woo_bot_for_woocommerce_notification_to_email', get_option( 'admin_email' ) );
		}
		if ( ! get_option( 'woo_bot_for_woocommerce_notification_mail_body' ) ) {
			update_option( 'woo_bot_for_woocommerce_notification_mail_body', __( 'You have received new chat bot question. Please see below the question:', 'woo-bot-for-woocommerce' ) . "\n\n[woo_bot_notification_body]" );
		}
		


	}

}
