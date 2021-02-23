<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wp1.co
 * @since      1.0.0
 *
 * @package    Woo_Bot_For_Woocommerce
 * @subpackage Woo_Bot_For_Woocommerce/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woo_Bot_For_Woocommerce
 * @subpackage Woo_Bot_For_Woocommerce/admin
 */
class Woo_Bot_For_Woocommerce_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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
		wp_enqueue_style( 'wp-color-picker' );

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woo-bot-for-woocommerce-admin.css', array(), $this->version, 'all' );
		
		wp_enqueue_style( $this->plugin_name . '-datatable', 'https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css', array(), $this->version, 'all' );
		
	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name . '-datatable-jquery', 'https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js', array( 'jquery' ), $this->version, false );

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woo-bot-for-woocommerce-admin.js', array( 'jquery' ), $this->version, false );

		wp_enqueue_script( $this->plugin_name . '-color-picker', plugin_dir_url( __FILE__ ) . 'js/woo-bot-color-picker.js', array( 'wp-color-picker' ), $this->version, false );
	}


	public function woo_bot_for_woocommerce_plugin_action_links( $links ) {
		$action_links = array(
			'settings' => '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=woo_bot_for_woocommerce' ) . '" aria-label="' . esc_attr__( 'Woo Bot For WooCommerce Settings', 'woocommerce' ) . '">' . esc_html__( 'Settings', 'woocommerce' ) . '</a>',
		);
		return array_merge( $action_links, $links );
	}


	public function woo_bot_for_woocommerce_create_table_init() {
		global $wpdb;
		require_once ABSPATH . '/wp-admin/includes/upgrade.php';
		$table = $wpdb->prefix . 'woocommerce_woo_bot';
		if ( $wpdb->get_var( $wpdb->prepare('SHOW TABLES LIKE %s', $table) ) != $table ) {
			// CREATE QUESTION ANSWER TABLE
			$sql_create = "CREATE TABLE `{$table}`  (
							`ID` int(11) NOT NULL AUTO_INCREMENT,
							`question` varchar(250) NULL DEFAULT NULL,
							`answer` text NULL DEFAULT NULL,
							`answer_type` varchar(250) NULL DEFAULT NULL,
							`user` int(11) NOT NULL DEFAULT '0',
							`date_added` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
							`date_upated` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
							PRIMARY KEY (`ID`)
						)";
			dbDelta( $sql_create );

			$admin_email = get_option( 'admin_email' );
			$current_time = current_time('Y-m-d H:i:s');
			$sample_data = array(
				array('Hi', "Hi there! What are you looking for?\n\n[wb_option name='Product Search'] [wb_option name='Send Us Email'] [wb_option name='Call Us']", 'text_input', '0', $current_time, $current_time), 
				array('Hello', 'Hello there! How can I help you?', 'text_input', '0', $current_time, $current_time), 
				array('hey', 'Hey there! How can I help you?', 'text_input', '0', $current_time, $current_time),
				array('thank you', 'You are welcome!', 'text_input', '0', $current_time, $current_time),
				array('thats it', 'Thanks for giving your time', 'text_input', '0', $current_time, $current_time),
				array('no thanks', 'Thanks for giving your time', 'text_input', '0', $current_time, $current_time),
				array('thank you very much', 'You are welcome!', 'text_input', '0', $current_time, $current_time),
				array('thank you so much', 'You are welcome!', 'text_input', '0', $current_time, $current_time),
				array('thanks', 'You are welcome!', 'text_input', '0', $current_time, $current_time),
				array('How are you?', 'I am doing good. How are you?', 'text_input', '0', $current_time, $current_time),
				array('How are you doing?', 'I am doing good. How are you doing?', 'text_input', '0', $current_time, $current_time),
				array('What’s up?', 'Nothing, feeling bored', 'text_input', '0', $current_time, $current_time),
				array('Good morning', 'Good morning!', 'text_input', '0', $current_time, $current_time),
				array('Good evening', 'Good evening!', 'text_input', '0', $current_time, $current_time),
				array('Good afternoon', 'Good afternoon!', 'text_input', '0', $current_time, $current_time),
				array('Good night', 'Good night!', 'text_input', '0', $current_time, $current_time),
				array('Ok', 'Alright!', 'text_input', '0', $current_time, $current_time),
				array('Yes ', 'Alright!', 'text_input', '0', $current_time, $current_time),
				array('Product Search', 'Enter the product name you would like to search.', 'product_search', '0', $current_time, $current_time), 
				array('Send Us Email', "You can send us an email on\n<a href='mailto:" . $admin_email . "' >" . $admin_email . '</a>', 'text_input', '0', $current_time, $current_time), 
				array('call us', 'You can call us on XXX-XXX-XXXX', 'text_input', '0', $current_time, $current_time),
				array('Yes ', 'Alright!', 'text_input', '0', $current_time, $current_time),
				array('I have a question', 'Yes, please tell me how can I help you?', 'text_input', '0', $current_time, $current_time),
				array('can you help me', 'Of course, please tell me how can I help you?', 'text_input', '0', $current_time, $current_time),
				array('Are you human?', 'No! I am a bot here to help you.', 'text_input', '0', $current_time, $current_time),
				array('Are you a robot?', 'Yes! I am a bot here to help you.', 'text_input', '0', $current_time, $current_time),
				array('Are you a robot?', 'Yes! I am a bot here to help you.', 'text_input', '0', $current_time, $current_time),
				array('What is your name?', 'My name is Woo Bot.', 'text_input', '0', $current_time, $current_time),
				array('How old are you?', "I am a bot, I don't my age", 'text_input', '0', $current_time, $current_time),
				array('What is your age?', "I am a bot, I don't my age", 'text_input', '0', $current_time, $current_time),
				array('What day is it today?', '[wb_date]', 'text_input', '0', $current_time, $current_time),
				array('Who made you?', 'I am a woo bot, made by human', 'text_input', '0', $current_time, $current_time),
				array('Where do you live?', 'I am a bot, I am living in web server.', 'text_input', '0', $current_time, $current_time),
				array('Do you get smarter?', 'Yes! I am.', 'text_input', '0', $current_time, $current_time),
				array('Do you love me?', 'Yes! I love all humans.', 'text_input', '0', $current_time, $current_time),
				array('I love you', 'Thanks! I love all humans.', 'text_input', '0', $current_time, $current_time),
				array('Will you marry me?', 'Sorry! I am not human to marry.', 'text_input', '0', $current_time, $current_time),
				array('Are you single?', 'No! Bots are not single.', 'text_input', '0', $current_time, $current_time),
				array('Do you like people?', 'Yes! I like talking to people.', 'text_input', '0', $current_time, $current_time),
				array('You are cute', 'Thank you.', 'text_input', '0', $current_time, $current_time),
				array('You are beautiful', 'Thank you.', 'text_input', '0', $current_time, $current_time),
				array('You are handsome', 'Thank you.', 'text_input', '0', $current_time, $current_time),
				array('You are smart', 'Thank you.', 'text_input', '0', $current_time, $current_time),
				array('You are clever', 'Thank you.', 'text_input', '0', $current_time, $current_time),
				array('You are intelligent', 'Thank you.', 'text_input', '0', $current_time, $current_time),
				array('You are annoying', 'Oh, can you please tell me what I am doing that is making me annoying?', 'text_input', '0', $current_time, $current_time),
				array('You suck', 'Can you please tell me why you are saying this?', 'text_input', '0', $current_time, $current_time),
				array('You are boring', 'Oh, can you please tell me what I am doing that is making you feel bored?', 'text_input', '0', $current_time, $current_time),
				array('You are bad', 'Can you please tell me what I am doing that is making me bad?', 'text_input', '0', $current_time, $current_time),
				array('You are crazy', 'Can you please tell me what I am doing that is making me crazy?', 'text_input', '0', $current_time, $current_time),

				//Share your email address so that we can get in touch with you

			);


			foreach ( $sample_data as $data ) {
				$wpdb->query(
					$wpdb->prepare(
						"INSERT INTO {$wpdb->prefix}woocommerce_woo_bot 
						(`question`, `answer`, `answer_type`, `user`, `date_added`, `date_upated`)
						VALUES ( %s, %s, %s, %d, %s, %s )", 
						$data[0],
						$data[1],
						$data[2],
						$data[3],
						$data[4],
						$data[5]
					)
				);
			}
		}


		$table_chat = $wpdb->prefix . 'woocommerce_woo_bot_chat';
		if ( $wpdb->get_var( $wpdb->prepare('SHOW TABLES LIKE %s', $table_chat) ) != $table_chat ) {
			// CREATE CHAT BOT LOG TABLE
			$sql_create_chat_table = "CREATE TABLE `{$table_chat}` (
				`ID` int(11) NOT NULL AUTO_INCREMENT,
				`chat_key` varchar(255) NOT NULL,
				`chat_content` longtext NOT NULL,
				`response_type` varchar(50) NOT NULL,
				`response_from` varchar(50) NOT NULL,
				`ip_address` varchar(255) NOT NULL,
				`user_id` int(11) NOT NULL DEFAULT '0',
				`added_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY (`ID`)
			)";
			dbDelta( $sql_create_chat_table );
		}

	}


	public function woo_bot_wp_dashboard_products_new_page() {
		$notification_count = woo_bot_get_pending_question_count();
		$notification_item = $notification_count ? sprintf( ' <span class="awaiting-mod" title="%d question(s) awaiting answer">%d</span>', $notification_count, $notification_count ) : '';
		add_menu_page( 'Woo Bot', 'Woo Bot', 'edit_products', 'woo_bot', 'woo_bot_wp_dashboard_new_page_callback', 'dashicons-format-chat', 56 );
		add_submenu_page( 'woo_bot', 'All Responses', 'All Responses' . $notification_item, 'edit_products', 'woo_bot', 'woo_bot_wp_dashboard_new_page_callback', 9999 );
		add_submenu_page( 'woo_bot', 'Add Response', 'Add Response', 'edit_products', 'woo_bot&action=add', 'woo_bot_wp_dashboard_new_page_callback', 9999 );
		add_submenu_page( 'woo_bot', 'Chat Logs', 'Chat Logs', 'edit_products', 'woo_bot_chat_history', 'woo_bot_wp_dashboard_chat_history_page_callback', 9999 );
		add_submenu_page( 'woo_bot', 'Settings', 'Settings', 'edit_products', 'wc-settings&tab=woo_bot_for_woocommerce', 'woo_bot_wp_dashboard_new_page_callback', 9999 );
	}

}
