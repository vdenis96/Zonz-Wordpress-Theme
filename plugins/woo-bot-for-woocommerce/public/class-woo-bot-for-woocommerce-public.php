<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://wp1.co
 * @since      1.0.0
 *
 * @package    Woo_Bot_For_Woocommerce
 * @subpackage Woo_Bot_For_Woocommerce/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Woo_Bot_For_Woocommerce
 * @subpackage Woo_Bot_For_Woocommerce/public
 */
class Woo_Bot_For_Woocommerce_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woo_Bot_For_Woocommerce_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woo_Bot_For_Woocommerce_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woo-bot-for-woocommerce-public.css', array(), $this->version, 'all' );

		wp_enqueue_style( 'dashicons' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woo_Bot_For_Woocommerce_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woo_Bot_For_Woocommerce_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name . '-scrollbar', plugin_dir_url( __FILE__ ) . 'js/jquery.scrollbar.js', array( 'jquery' ), $this->version, false );

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woo-bot-for-woocommerce-public.js', array( 'jquery' ), $this->version, false );

	}


	public function woo_bot_ajaxurl() { ?>
		<script type="text/javascript">
		var woobot_ajaxurl = '<?php echo esc_url( admin_url('admin-ajax.php') ); ?>';
		</script>
		<?php
	}


	public function woo_bot_chat_query() {
		$data = array();
		$status = 100;
		if (isset($_POST['woo_bot_nonce']) && ! wp_verify_nonce( sanitize_key($_POST['woo_bot_nonce']), 'Woo Bot') ) {
			$status = 401;
		} else if (! isset($_POST['woo_bot_nonce']) ) {
			$status = 404;
		} else {
			if (! isset($_POST['woo-bot-message-input']) ) {
				$status = 402;
			} else if ( isset($_POST['woo-bot-message-input']) ) {
				
				global $wpdb;

				$msg = isset($_POST['woo-bot-message-input']) ? sanitize_text_field( $_POST['woo-bot-message-input'])  : '';
				$msg_type = ( isset($_POST['woo-bot-answer-type']) && !empty($_POST['woo-bot-answer-type']) ) ? strtolower(sanitize_text_field( $_POST['woo-bot-answer-type']) ) : 'text_input';

				$current_user = wp_get_current_user();
				$user_id = isset( $current_user->ID ) ? $current_user->ID : 0;
				
				woo_bot_update_chat_history($msg, $msg_type, 'user' );

				if ( 'text_user_name' == $msg_type ) {
					if ( ! woo_bot_is_alphabets( trim($msg) ) ) {
						$data['reply'] = 'Name seems to be invalid, enter valid name.';
						$data['answer_type'] = 'text_user_name';
					} else {
						$capture_email = get_option( 'woo_bot_for_woocommerce_enable_capture_email', 'yes' );
						$capture_email_question = wpautop( do_shortcode( get_option( 'woo_bot_for_woocommerce_capture_email_question', 'May I have your email as well [wb_name]?') ) );
		
						if ( 'yes' == $capture_email ) {
							$data['reply'] = $capture_email_question;
							$data['answer_type'] = 'text_user_email';
						} else {
							$thank_you_message = wpautop( do_shortcode( get_option( 'woo_bot_for_woocommerce_capture_thank_you_message', 'Thank you [wb_name] for providing the information. How can I help you?') ) );

							$data['reply'] = $thank_you_message;
							$data['answer_type'] = 'text_input';
						}
					}
				} else if ( 'text_user_email' == $msg_type ) {
					if ( ! woo_bot_is_valid_email( trim($msg) ) ) {
						$fallback_email_message = wpautop( do_shortcode( get_option( 'woo_bot_for_woocommerce_capture_fallback_message', 'No worries, we can still proceed. How can I help you?') ) );

						$data['reply'] = $fallback_email_message;
						$data['answer_type'] = 'text_input';
					} else {
						$thank_you_message = wpautop( do_shortcode( get_option( 'woo_bot_for_woocommerce_capture_thank_you_message', 'Thank you [wb_name] for providing the information. How can I help you?') ) );

						$data['reply'] = $thank_you_message;
						$data['answer_type'] = 'text_input';
					}
				} else if ( 'product_search' == $msg_type ) {
					$products = array();
					$search_query = new WP_Query( array( 's' => $msg, 'post_type' => 'product', 'posts_per_page' => -1 ) );
					wp_reset_postdata();
					if ( isset($search_query->posts) && count($search_query->posts) > 0 ) {
						foreach ( $search_query->posts as $qpost ) {
							$product_image = wc_placeholder_img_src('woocommerce_gallery_thumbnail');
							$prd_thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $qpost->ID ), 'woocommerce_gallery_thumbnail' );
							if ( ! empty( $prd_thumb[0] ) ) {
								$product_image = $prd_thumb[0];
							}
							$products[] = '<div class="woo-bot-product-thumb"><a href="' . get_permalink($qpost->ID) . '" target="_blank"><img src="' . $product_image . '">' . $qpost->post_title . '</a></div>';
						}
						$data['reply'] = '<p>' . count($search_query->posts) . ' product(s) found for <strong>' . $msg . '</strong></p>' . implode('', $products);
						$data['answer_type'] = 'text_input';
					} else {
						$data['reply'] = 'Result not found for <strong>' . $msg . '</strong>. Try another search keyword.';
						$data['answer_type'] = 'product_search';
					}
				} else {
					$row_data = $wpdb->get_row( $wpdb->prepare("SELECT * FROM {$wpdb->prefix}woocommerce_woo_bot WHERE question= %s", $msg), OBJECT );

					if ( !empty($row_data->answer)  ) {
						$data['reply'] = wpautop( do_shortcode( wp_kses_stripslashes( stripslashes_deep($row_data->answer)) ) ) ;
						$data['answer_type'] = $row_data->answer_type;
					} else {
						//SELECT * FROM `wp_woocommerce_woo_bot` WHERE question REGEXP 'send|email'
						$row_data_like = $wpdb->get_row( $wpdb->prepare("SELECT * FROM {$wpdb->prefix}woocommerce_woo_bot WHERE question LIKE %s ORDER BY RAND() LIMIT 1", '%' . $msg . '%' ), OBJECT );

						$msg_words = explode(' ', $msg);
						$regex_values = array();
						for ($n = 0; $n < 10; $n++ ) {
							$regex_values[] = isset( $msg_words[$n] ) ? '%' . trim($msg_words[$n]) . '%' : '%%';
						}
						$row_data_regex = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}woocommerce_woo_bot WHERE answer IS NOT NULL AND question LIKE %s AND question LIKE %s AND question LIKE %s AND question LIKE %s AND question LIKE %s AND question LIKE %s AND question LIKE %s AND question LIKE %s AND question LIKE %s AND question LIKE %s ORDER BY RAND() LIMIT 1", $regex_values ), OBJECT );

						if ( !empty($row_data_regex->answer)  ) {
							$data['reply'] = wpautop( do_shortcode( wp_kses_stripslashes( stripslashes_deep($row_data_regex->answer)) ) ) ;
							$data['answer_type'] = $row_data_regex->answer_type;
						} else if ( ! isset($row_data_like->answer) && !empty($row_data_regex->answer) ) {
							$data['reply'] = wpautop( do_shortcode( wp_kses_stripslashes( stripslashes_deep($row_data_regex->answer)) ) ) ;
							$data['answer_type'] = $row_data_regex->answer_type;
						} else {

							$search_str_array = explode(' ', $msg);
							$search_str = preg_replace('/[^A-Za-z0-9 ]/', '', $search_str_array[ count($search_str_array)-1 ]);
							$search_query = new WP_Query( array( 's' => $search_str, 'post_type' => 'product', 'posts_per_page' => -1 ) );
							wp_reset_postdata();

							if ( isset($search_query->posts) && count($search_query->posts) > 0 ) {
								$msg = $search_str;
								$products = array();
								foreach ( $search_query->posts as $qpost ) {
									$product_image = wc_placeholder_img_src('woocommerce_gallery_thumbnail');
									$prd_thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $qpost->ID ), 'woocommerce_gallery_thumbnail' );
									if ( ! empty( $prd_thumb[0] ) ) {
										$product_image = $prd_thumb[0];
									}
									$products[] = '<div class="woo-bot-product-thumb"><a href="' . get_permalink($qpost->ID) . '" target="_blank"><img src="' . $product_image . '">' . $qpost->post_title . '</a></div>';
								}
								$data['reply'] = '<p>' . count($search_query->posts) . ' product(s) found for <strong>' . $msg . '</strong></p>' . implode('', $products);
								$data['answer_type'] = 'text_input';
							} else {
								$wpdb->insert( 
									$wpdb->prefix . 'woocommerce_woo_bot', 
									array( 
										'question' => $msg,
										'answer' => null,
										'answer_type' => 'text_input',
										'user' => $user_id
									)
								);
								
								$no_answer_message = wpautop( do_shortcode( get_option( 'woo_bot_for_woocommerce_no_answer_message', "Sorry! We didn’t understand.\n\nCan you be more specific\n[wb_option name='Product Search'] [wb_option name='Send Us Email'] [wb_option name='Call Us']") ) );

								$data['reply'] = $no_answer_message;
								$data['answer_type'] = isset($row_data->answer_type) ? $row_data->answer_type : 'text_input';
							
								$notification_alert = get_option( 'woo_bot_for_woocommerce_enable_notification_alert', 'yes' );

								if ( 'yes' == $notification_alert ) {
									$site_name = get_bloginfo('name');
									$admin_email = get_option( 'admin_email' );
									$to_email = get_option( 'woo_bot_for_woocommerce_notification_from_email', $admin_email );
									$from_email = get_option( 'woo_bot_for_woocommerce_notification_from_email', $admin_email );
									$subject = get_option( 'woo_bot_for_woocommerce_notification_email_subject', 'New Chat Bot Question - ' . $site_name );
									$mail_body = get_option( 'woo_bot_for_woocommerce_notification_mail_body', "You have received new chat bot question.Please see below the question:\n\n[woo_bot_notification_body]" );
									$body = wpautop( str_replace('[woo_bot_notification_body]', '<strong>' . $msg . '</strong>', $mail_body) );
									$mail_headers = array();
									$mail_headers[] = 'Content-Type: text/html; charset=UTF-8';
									$mail_headers[] = 'From: ' . $site_name . ' <' . $from_email . '>';
		
									wp_mail( $to_email, $subject, $body, $mail_headers );
								}
							}
	
						}

						
					}
				}

				woo_bot_update_chat_history($data['reply'], $data['answer_type'], 'woo_bot' );

				$status = 200;
			} else {
				$status = 403;
			}
		}
		wp_send_json($data, $status);
		wp_die();
	}


	public function woo_bot_load_in_footer_function() { 

		$chat_bg_image = get_option( 'woo_bot_for_woocommerce_woo_bot_chat_image', '' );
		$chat_icon_color = get_option( 'woo_bot_for_woocommerce_chat_icon_color', '#ffffff' );
		$chat_icon_bg = get_option( 'woo_bot_for_woocommerce_chat_icon_background_color', '#333333' );
		$chat_theme = get_option( 'woo_bot_for_woocommerce_woo_bot_chat_theme', 'light' );
		$chat_icon = get_option( 'woo_bot_for_woocommerce_woo_bot_chat_icon', 'format-chat' );
		$welcome_message = wpautop( get_option( 'woo_bot_for_woocommerce_welcome_message', 'Welcome to <strong>' . esc_attr( get_bloginfo('name') ) . '</strong>!' ) );
		$welcome_message .= wpautop( get_option( 'woo_bot_for_woocommerce_capture_name_question', 'May I have your name, please?' ) );
		
		$chat_exit_intent = get_option( 'woo_bot_for_woocommerce_enable_exit_intent_trigger', 'yes' );
		$chat_exit_intent_message = wpautop( get_option( 'woo_bot_for_woocommerce_exit_intent_message', "We’re always working to make our website better. If you have a moment to spare, would you be willing to send us feedback on <a href='mailto:" . get_option( 'admin_email' ) . "'>" . get_option( 'admin_email' ) . '</a>? We’d greatly appreciate your feedback.' ) );
		?>
		
		<div id="woo-bot-opener" data-chat-icon="<?php echo esc_attr($chat_icon); ?>" style="--bg-color: <?php echo esc_url($chat_icon_bg); ?>; --text-color: <?php echo esc_attr($chat_icon_color); ?>;">
			<?php 
			echo do_shortcode('[dashicon icon="' . $chat_icon . '"]'); 
			?>
		</div>
		<div id="woo-bot-messenger" class="wb-theme-<?php echo esc_attr( $chat_theme ); ?>" style="<?php echo !empty( $chat_bg_image ) ? '--bg-image: url(' . esc_url($chat_bg_image) . ')' : ''; ?>">
			<div id="woo-bot-message-box">
				<div class="wb-scrollbar-inner">
					<div class="woo-bot-chat-to"><?php echo wp_kses_post($welcome_message); ?></div>
				</div>
			</div>
			<div id="woo-bot-message-input-wrapper">
				<form id="woo-bot-message-form" method="post" autocomplete="off">
					<input type="text" name="woo-bot-message-input" id="woo-bot-message-input" placeholder="Send your message" autocomplete="off">
					<?php wp_nonce_field( 'Woo Bot', 'woo_bot_nonce' ); ?>
					<button type="submit" id="woo-bot-message-send"><?php echo do_shortcode('[dashicon icon="arrow-right-alt"]'); ?></button>
					<input type="hidden" name="action" value="woo_bot_chat_query">
					<input type="hidden" name="woo-bot-answer-type" id="woo-bot-answer-type" value="text_user_name">
				</form>
			</div>
		</div>
		<?php
		if ( 'yes' == $chat_exit_intent ) {
			?>
			<div id="woo-bot-exit-intent-hidden-message" style="display:none"><?php echo wp_kses_post($chat_exit_intent_message); ?></div>
			<?php
		}
	}

}
