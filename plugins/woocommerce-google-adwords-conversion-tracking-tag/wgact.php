<?php
/**
 * Plugin Name:  WooCommerce Google Ads Conversion Tracking
 * Description:  Google Ads dynamic conversion value tracking for WooCommerce.
 * Author:       Wolf+Bär Agency
 * Plugin URI:   https://wordpress.org/plugins/woocommerce-google-adwords-conversion-tracking-tag/
 * Author URI:   https://wolfundbaer.ch
 * Version:      1.6.4
 * License:      GPLv2 or later
 * Text Domain:  woocommerce-google-adwords-conversion-tracking-tag
 * WC requires at least: 2.6
 * WC tested up to: 4.0
 **/

// TODO Try to use jQuery validation in the form.
// TODO in case Google starts to use alphabetic characters in the conversion ID, output the conversion ID with ''
// TODO give users choice to use content or footer based code insertion
// TODO only run if WooCommerce is active
// TODO fix json_encode for order total with only two decimals https://stackoverflow.com/questions/42981409/php7-1-json-encode-float-issue
// TODO also json_encode might not return the correct format under certain locales http://php.net/manual/en/function.json-encode.php#91434
// TODO reporting conversions with cart data: https://support.google.com/google-ads/answer/9028254?hl=en




if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


class WGACT {

	private $conversion_id;
	private $conversion_label;
	private $add_cart_data;
	private $product_identifier;
	private $gtag_deactivation;

	public function __construct() {

		// preparing the DB check and upgrade routine
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-db-upgrade.php';

		// running the DB updater
		$db_upgrade = new WGACT_DB_Upgrade();
		$db_upgrade->run_options_db_upgrade();

		// startup all functions
		$this->init();
	}

	// startup all functions
	public function init() {

		// load the options
		$this->wgact_options_init();

		// add the admin options page
		add_action( 'admin_menu', array( $this, 'wgact_plugin_admin_add_page' ), 99 );

		// install a settings page in the admin console
		add_action( 'admin_init', array( $this, 'wgact_plugin_admin_init' ) );

		// add a settings link on the plugins page
		add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array( $this, 'wgact_settings_link' ) );

		// Load textdomain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// ask for a rating in a plugin notice
		add_action( 'admin_head', array( $this, 'ask_for_rating_js' ) );
		add_action( 'wp_ajax_wgact_dismissed_notice_handler', array( $this, 'ajax_rating_notice_handler' ) );
		add_action( 'admin_notices', array( $this, 'ask_for_rating_notices_if_not_asked_before' ) );

		// add the Google Ads tag to the thankyou part of the page within the body tags
		add_action( 'wp_head', array( $this, 'GoogleAdWordsTag' ) );

		// fix WordPress CDATA filter as per ticket https://core.trac.wordpress.org/ticket/3670
		add_action( 'template_redirect', array( $this, 'cdata_template_redirect' ), -1 );

	}

    // start cdata template markupfix
	function cdata_template_redirect( $content ) {
		ob_start( array( $this, 'cdata_markupfix' ) );
	}

	// execute str_replace on content to fix the CDATA comments
	function cdata_markupfix( $content ) {
		$content = str_replace("]]&gt;", "]]>", $content);

		return $content;
	}

	// client side ajax js handler for the admin rating notice
	public function ask_for_rating_js() {

		?>
        <script type="text/javascript">
            jQuery(document).on('click', '.notice-success.wgact-rating-success-notice, wgact-rating-link, .wgact-rating-support', function ($) {

                var data = {
                    'action': 'wgact_dismissed_notice_handler',
                };

                jQuery.post(ajaxurl, data);
                jQuery('.wgact-rating-success-notice').remove();

            });
        </script> <?php

	}

	// server side php ajax handler for the admin rating notice
	public function ajax_rating_notice_handler() {

		// prepare the data that needs to be written into the user meta
		$wgdr_admin_notice_user_meta = array(
			'date-dismissed' => date( 'Y-m-d' ),
		);

		// update the user meta
		update_user_meta( get_current_user_id(), 'wgact_admin_notice_user_meta', $wgdr_admin_notice_user_meta );

		wp_die(); // this is required to terminate immediately and return a proper response
	}


	// only ask for rating if not asked before or longer than a year
	public function ask_for_rating_notices_if_not_asked_before() {

		// get user meta data for this plugin
		$user_meta = get_user_meta( get_current_user_id(), 'wgact_admin_notice_user_meta' );

		// check if there is already a saved value in the user meta
		if ( isset( $user_meta[0]['date-dismissed'] ) ) {

			$date_1 = date_create( $user_meta[0]['date-dismissed'] );
			$date_2 = date_create( date( 'Y-m-d' ) );

			// calculate day difference between the dates
			$interval = date_diff( $date_1, $date_2 );

			// check if the date difference is more than 360 days
			if ( 360 < $interval->format( '%a' ) ) {
				$this->ask_for_rating_notices();
			}

		} else {

			$this->ask_for_rating_notices();
		}
	}

	// show an admin notice to ask for a plugin rating
	public function ask_for_rating_notices() {

		$current_user = wp_get_current_user();

		?>
        <div class="notice notice-success is-dismissible wgact-rating-success-notice">
            <p>
                <span><?php _e( 'Hi ', 'woocommerce-google-adwords-conversion-tracking-tag' ); ?></span>
                <span><?php echo( $current_user->user_firstname ? $current_user->user_firstname : $current_user->nickname ); ?></span>
                <span><?php _e( '! ', 'woocommerce-google-adwords-conversion-tracking-tag' ); ?></span>
                <span><?php _e( 'You\'ve been using the ', 'woocommerce-google-adwords-conversion-tracking-tag' ); ?></span>
                <span><b><?php _e( 'WGACT Google Ads Conversion Tracking Plugin', 'woocommerce-google-adwords-conversion-tracking-tag' ); ?></b></span>
                <span><?php _e( ' for a while now. If you like the plugin please support our development by leaving a ★★★★★ rating: ', 'woocommerce-google-adwords-conversion-tracking-tag' ); ?></span>
                <span class="wgact-rating-link">
                    <a href="https://wordpress.org/support/view/plugin-reviews/woocommerce-google-adwords-conversion-tracking-tag?rate=5#postform"
                       target="_blank"><?php _e( 'Rate it!', 'woocommerce-google-adwords-conversion-tracking-tag' ); ?></a>
                </span>
            </p>
            <p>
                <span><?php _e( 'Or else, please leave us a support question in the forum. We\'ll be happy to assist you: ', 'woocommerce-google-adwords-conversion-tracking-tag' ); ?></span>
                <span class="wgact-rating-support">
                    <a href="https://wordpress.org/support/plugin/woocommerce-google-adwords-conversion-tracking-tag"
                       target="_blank"><?php _e( 'Get support', 'woocommerce-google-adwords-conversion-tracking-tag' ); ?></a>
                </span>
            </p>
        </div>
		<?php

	}


	// initialise the options
	public function wgact_options_init() {

		// set options equal to defaults
		global $wgact_plugin_options;
		$wgact_plugin_options = get_option( 'wgact_plugin_options' );

		if ( false === $wgact_plugin_options ) {

			$wgact_plugin_options = $this->wgact_get_default_options();
			update_option( 'wgact_plugin_options', $wgact_plugin_options );

		} else {  // Check if each single option has been set. If not, set them. That is necessary when new options are introduced.

			// get default plugins options
			$wgact_default_plugin_options = $this->wgact_get_default_options();

			// go through all default options an find out if the key has been set in the current options already
			foreach ( $wgact_default_plugin_options as $key => $value ) {

				// Test if the key has been set in the options already
				if ( ! array_key_exists( $key, $wgact_plugin_options ) ) {

					// set the default key and value in the options table
					$wgact_plugin_options[ $key ] = $value;

					// update the options table with the new key
					update_option( 'wgact_plugin_options', $wgact_plugin_options );

				}
			}
		}
	}

	// get the default options
	public function wgact_get_default_options() {
		// default options settings
		$options = array(
			'conversion_id'     => '',
			'conversion_label'  => '',
			'order_total_logic' => 0,
			'gtag_deactivation' => 0,
			'add_cart_data'     => 0,
			'aw_merchant_id'    => '',
            'product_identifier' => 0,
		);

		return $options;
	}


	// Load text domain function
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'woocommerce-google-adwords-conversion-tracking-tag', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}


	// adds a link on the plugins page for the wgdr settings
	function wgact_settings_link( $links ) {

		$mylinks = array(
			'<a href="' . admin_url( 'admin.php?page=wgact' ) . '">Settings</a>',
		);
		return array_merge( $links, $mylinks );
	}

	// add the admin options page
	function wgact_plugin_admin_add_page() {
		//add_options_page('WGACT Plugin Page', 'WGACT Plugin Menu', 'manage_options', 'wgact', array($this, 'wgact_plugin_options_page'));
		add_submenu_page( 'woocommerce', esc_html__( 'Google Ads Conversion Tracking', 'woocommerce-google-adwords-conversion-tracking-tag' ), esc_html__( 'Google Ads Conversion Tracking', 'woocommerce-google-adwords-conversion-tracking-tag' ), 'manage_options', 'wgact', array(
			$this,
			'wgact_plugin_options_page',
		) );
	}

	// display the admin options page
	function wgact_plugin_options_page() {

		?>

        <br>
        <div style="width:980px; float: left; margin: 5px">
            <div style="float:left; margin: 5px; margin-right:20px; width:750px">
                <div style="background: #0073aa; padding: 10px; font-weight: bold; color: white; border-radius: 2px">
					<?php esc_html_e( 'Google Ads Conversion Tracking Settings', 'woocommerce-google-adwords-conversion-tracking-tag' ) ?>
                </div>
                <form action="options.php" method="post">
					<?php settings_fields( 'wgact_plugin_options_settings_fields' ); ?>
					<?php do_settings_sections( 'wgact' ); ?>
                    <br>
                    <table class="form-table" style="margin: 10px">
                        <tr>
                            <th scope="row" style="white-space: nowrap">
                                <input name="Submit" type="submit" value="<?php esc_attr_e( 'Save Changes' ); ?>"
                                       class="button button-primary"/>
                            </th>
                        </tr>
                    </table>
                </form>

                <br>
                <div
                        style="background: #0073aa; padding: 10px; font-weight: bold; color: white; margin-bottom: 20px; border-radius: 2px">
					<span>
						<?php esc_html_e( 'Profit Driven Marketing by Wolf+Bär', 'woocommerce-google-adwords-conversion-tracking-tag' ) ?>
					</span>
                    <span style="float: right;">
						<a href="https://wolfundbaer.ch/"
                           target="_blank" style="color: white">
							<?php esc_html_e( 'Visit us here: https://wolfundbaer.ch', 'woocommerce-google-adwords-conversion-tracking-tag' ) ?>
						</a>
					</span>
                </div>
            </div>
            <div style="float: left; margin: 5px">
                <a href="https://wordpress.org/plugins/woocommerce-google-dynamic-retargeting-tag/" target="_blank">
                    <img src="<?php echo( plugins_url( 'images/wgdr-icon-256x256.png', __FILE__ ) ) ?>" width="150px"
                         height="150px">
                </a>
            </div>
            <div style="float: left; margin: 5px">
                <a href="https://wordpress.org/plugins/woocommerce-google-adwords-conversion-tracking-tag/"
                   target="_blank">
                    <img src="<?php echo( plugins_url( 'images/wgact-icon-256x256.png', __FILE__ ) ) ?>" width="150px"
                         height="150px">
                </a>
            </div>
        </div>
		<?php
	}


	// add the admin settings and such
	function wgact_plugin_admin_init() {

		//register_setting( 'wgact_plugin_options_settings_fields', 'wgact_plugin_options', 'wgact_plugin_options_validate');
		register_setting( 'wgact_plugin_options_settings_fields', 'wgact_plugin_options', array( $this, 'wgact_plugin_options_validate') );
		//error_log('after register setting');

		add_settings_section( 'wgact_plugin_main', esc_html__( 'Main Settings', 'woocommerce-google-adwords-conversion-tracking-tag' ), array(
			$this,
			'wgact_plugin_section_main',
		), 'wgact' );

		// add the field for the conversion id
		add_settings_field( 'wgact_plugin_conversion_id', esc_html__( 'Conversion ID', 'woocommerce-google-adwords-conversion-tracking-tag' ), array(
			$this,
			'wgact_plugin_setting_conversion_id',
		), 'wgact', 'wgact_plugin_main' );

		// ad the field for the conversion label
		add_settings_field(
			'wgact_plugin_conversion_label',
			esc_html__(
				'Conversion Label',
				'woocommerce-google-adwords-conversion-tracking-tag' ),
			array(
				$this,
				'wgact_plugin_setting_conversion_label',
			),
			'wgact',
			'wgact_plugin_main'
		);

		// add fields for the order total logic
		add_settings_field(
			'wgact_plugin_order_total_logic',
			esc_html__(
				'Order Total Logic',
				'woocommerce-google-adwords-conversion-tracking-tag'
			),
			array(
				$this,
				'wgact_plugin_setting_order_total_logic',
			),
			'wgact',
			'wgact_plugin_main'
		);

		// add fields for the gtag insertion
		add_settings_field(
			'wgact_plugin_gtag',
			esc_html__(
				'gtag Deactivation',
				'woocommerce-google-adwords-conversion-tracking-tag'
			),
			array(
				$this,
				'wgact_plugin_setting_gtag_deactivation',
			),
			'wgact',
			'wgact_plugin_main'
		);

		// add new section for cart data
		add_settings_section(
            'wgact_plugin_add_cart_data',
            esc_html__(
                    'Add Cart Data',
                    'woocommerce-google-adwords-conversion-tracking-tag' ) . ' (<span style="color:red">beta</span>)',
            array(
                $this,
                'wgact_plugin_section_add_cart_data',
                ),
            'wgact'
        );

		// add fields for cart data
		add_settings_field(
			'wgact_plugin_add_cart_data',
			esc_html__(
				'Activation',
				'woocommerce-google-adwords-conversion-tracking-tag'
			),
			array(
				$this,
				'wgact_plugin_setting_add_cart_data',
			),
			'wgact',
			'wgact_plugin_add_cart_data'
		);

		// add the field for the aw_merchant_id
		add_settings_field( 'wgact_plugin_aw_merchant_id', esc_html__( 'aw_merchant_id', 'woocommerce-google-adwords-conversion-tracking-tag' ), array(
			$this,
			'wgact_plugin_setting_aw_merchant_id',
		), 'wgact', 'wgact_plugin_add_cart_data' );

		// add the field for the aw_feed_country
		add_settings_field( 'wgact_plugin_aw_feed_country', esc_html__( 'aw_feed_country', 'woocommerce-google-adwords-conversion-tracking-tag' ), array(
			$this,
			'wgact_plugin_setting_aw_feed_country',
		), 'wgact', 'wgact_plugin_add_cart_data' );

		// add the field for the aw_feed_language
		add_settings_field( 'wgact_plugin_aw_feed_language', esc_html__( 'aw_feed_language', 'woocommerce-google-adwords-conversion-tracking-tag' ), array(
			$this,
			'wgact_plugin_setting_aw_feed_language',
		), 'wgact', 'wgact_plugin_add_cart_data' );

		// add fields for the product identifier
		add_settings_field(
			'wgact_plugin_option_product_identifier',
			esc_html__(
				'Product Identifier',
				'woocommerce-google-adwords-conversion-tracking-tag'
			),
			array(
				$this,
				'wgact_plugin_option_product_identifier',
			),
			'wgact',
			'wgact_plugin_add_cart_data'
		);
	}

	function wgact_plugin_section_main() {
        // do nothing
	}

	function wgact_plugin_section_add_cart_data() {

		_e( 'Find out more about this wonderful new feature: ', 'woocommerce-google-adwords-conversion-tracking-tag');
		echo '<a href="https://support.google.com/google-ads/answer/9028254" target="_blank">https://support.google.com/google-ads/answer/9028254</a><br>';
		_e( 'At the moment we are testing this feature. It might go into a PRO version of this plugin in the future.', 'woocommerce-google-adwords-conversion-tracking-tag');
	}

	function wgact_plugin_setting_conversion_id() {
		$options = get_option( 'wgact_plugin_options' );
		echo "<input id='wgact_plugin_conversion_id' name='wgact_plugin_options[conversion_id]' size='40' type='text' value='{$options['conversion_id']}' />";
		echo '<br><br>';
        _e('The conversion ID looks similar to this:', 'woocommerce-google-adwords-conversion-tracking-tag');
        echo '&nbsp;<i>123456789</i>';
		echo '<p>';
        _e('Watch a video that explains how to find the conversion ID: ', 'woocommerce-google-adwords-conversion-tracking-tag');
        echo '<a href="https://www.youtube.com/watch?v=p9gY3JSrNHU" target="_blank">https://www.youtube.com/watch?v=p9gY3JSrNHU</a>';
	}

	function wgact_plugin_setting_conversion_label() {
		$options = get_option( 'wgact_plugin_options' );
		echo "<input id='wgact_plugin_conversion_label' name='wgact_plugin_options[conversion_label]' size='40' type='text' value='{$options['conversion_label']}' />";
		echo '<br><br>';
		_e('The conversion Label looks similar to this:', 'woocommerce-google-adwords-conversion-tracking-tag');
		echo '&nbsp;<i>Xt19CO3axGAX0vg6X3gM</i>';
		echo '<p>';
		_e('Watch a video that explains how to find the conversion ID: ', 'woocommerce-google-adwords-conversion-tracking-tag');
		echo '<a href="https://www.youtube.com/watch?v=p9gY3JSrNHU" target="_blank">https://www.youtube.com/watch?v=p9gY3JSrNHU</a>';
	}

	function wgact_plugin_setting_order_total_logic() {
		$options = get_option( 'wgact_plugin_options' );
		?>
        <input type='radio' id='wgact_plugin_option_product_identifier_0' name='wgact_plugin_options[order_total_logic]'
               value='0'  <?php echo( checked( 0, $options['order_total_logic'], false ) ) ?> ><?php _e( 'Use order_subtotal: Doesn\'t include tax and shipping (default)', 'woocommerce-google-adwords-conversion-tracking-tag' ) ?>
        <br>
        <input type='radio' id='wgact_plugin_option_product_identifier_1' name='wgact_plugin_options[order_total_logic]'
               value='1'  <?php echo( checked( 1, $options['order_total_logic'], false ) ) ?> ><?php _e( 'Use order_total: Includes tax and shipping', 'woocommerce-google-adwords-conversion-tracking-tag' ) ?>
        <br><br>
		<?php _e( 'This is the order total amount reported back to Google Ads', 'woocommerce-google-adwords-conversion-tracking-tag' ) ?>
		<?php
	}

	public function wgact_plugin_setting_gtag_deactivation() {
		$options = get_option( 'wgact_plugin_options' );
		?>
        <input type='checkbox' id='wgact_plugin_option_gtag_deactivation' name='wgact_plugin_options[gtag_deactivation]'
               value='1' <?php checked( $options['gtag_deactivation'] ); ?> />
		<?php
		echo( esc_html__( 'Disable gtag.js insertion if another plugin is inserting it already.', 'woocommerce-google-adwords-conversion-tracking-tag' ) );
	}

	function wgact_plugin_setting_add_cart_data() {
		$options = get_option( 'wgact_plugin_options' );
		?>
		<input type='checkbox' id='wgact_plugin_add_cart_data' name='wgact_plugin_options[add_cart_data]' size='40' value='1' <?php echo( checked( 1, $options['add_cart_data'], true ) ) ?> >
		<?php
		_e( 'Add the cart data to the conversion event', 'woocommerce-google-adwords-conversion-tracking-tag' );
	}

	function wgact_plugin_setting_aw_merchant_id() {
		$options = get_option( 'wgact_plugin_options' );
		echo "<input type='text' id='wgact_plugin_aw_merchant_id' name='wgact_plugin_options[aw_merchant_id]' size='40' value='{$options['aw_merchant_id']}' />";
		echo '<br><br>Enter the ID of your Google Merchant Center account.';
	}

	function wgact_plugin_setting_aw_feed_country() {

		echo '<b>' . $this->get_visitor_country() . '</b>&nbsp;';
//		echo '<br>' . 'get_external_ip_address: ' . WC_Geolocation::get_external_ip_address();
//		echo '<br>' . 'get_ip_address: ' . WC_Geolocation::get_ip_address();
//		echo '<p>' . 'geolocate_ip: ' . '<br>';
//		echo print_r(WC_Geolocation::geolocate_ip());
//		echo '<p>' . 'WC_Geolocation::geolocate_ip(WC_Geolocation::get_external_ip_address()): ' . '<br>';
//		echo print_r(WC_Geolocation::geolocate_ip(WC_Geolocation::get_external_ip_address()));
		echo '<br><br>Currently the plugin automatically detects the location of the visitor for this setting. In most, if not all, cases this will work fine. Please let us know if you have a use case where you need another output: <a href="mailto:support@wolfundbaer.ch">support@wolfundbaer.ch</a>';
	}

	function wgact_plugin_setting_aw_feed_language() {
		echo '<b>' . $this->get_gmc_language() . '</b>';
		echo "<br><br>The plugin will use the WordPress default language for this setting. If the shop uses translations, in theory we could also use the visitors locale. But, if that language is  not set up in the Google Merchant Center we might run into issues. If you need more options here let us know:  <a href=\"mailto:support@wolfundbaer.ch\">support@wolfundbaer.ch</a>";
	}

	public function wgact_plugin_option_product_identifier() {
		$options = get_option( 'wgact_plugin_options' );
		?>
        <input type='radio' id='wgact_plugin_option_product_identifier_0' name='wgact_plugin_options[product_identifier]'
               value='0' <?php echo( checked( 0, $options['product_identifier'], false ) ) ?>/><?php _e( 'post id (default)', 'woocommerce-google-adwords-conversion-tracking-tag' ) ?><br>

        <input type='radio' id='wgact_plugin_option_product_identifier_1' name='wgact_plugin_options[product_identifier]'
               value='1' <?php echo( checked( 1, $options['product_identifier'], false ) ) ?>/><?php _e( 'post id with woocommerce_gpf_ prefix *', 'woocommerce-google-adwords-conversion-tracking-tag' ) ?><br>

        <input type='radio' id='wgact_plugin_option_product_identifier_1' name='wgact_plugin_options[product_identifier]'
               value='2' <?php echo( checked( 2, $options['product_identifier'], false ) ) ?>/><?php _e( 'SKU', 'woocommerce-google-adwords-conversion-tracking-tag' ) ?><br><br>

		<?php echo( esc_html__( 'Choose a product identifier.', 'woocommerce-google-adwords-conversion-tracking-tag' ) ); ?><br><br>
		<?php _e( '* This is for users of the <a href="https://woocommerce.com/products/google-product-feed/" target="_blank">WooCommerce Google Product Feed Plugin</a>', 'woocommerce-google-adwords-conversion-tracking-tag' ); ?>


		<?php
	}



	// validate our options
	function wgact_plugin_options_validate( $input ) {

		// Create our array for storing the validated options
		$output = $input;

		// validate and sanitize conversion_id

		$needles_cid = array( 'AW-', '"',);
		$replacements_cid = array( '', '');

		// clean
		$output['conversion_id'] = wp_strip_all_tags( str_ireplace( $needles_cid, $replacements_cid, $input['conversion_id'] ) );

		// validate and sanitize conversion_label

		$needles_cl = array( '"' );
		$replacements_cl = array( '' );

		$output['conversion_label'] = wp_strip_all_tags( str_ireplace( $needles_cl, $replacements_cl, $input['conversion_label'] ) );

		// Return the array processing any additional functions filtered by this action
		// return apply_filters( 'sandbox_theme_validate_input_examples', $output, $input );
        return $output;
	}

	function woocommerce_3_and_above(){
        global $woocommerce;
        if( version_compare( $woocommerce->version, 3.0, ">=" ) ) {
            return true;
        } else {
            return false;
        }
    }

	public function GoogleAdWordsTag() {

		$this->conversion_id      = $this->get_conversion_id();
		$this->conversion_label   = $this->get_conversion_label();
		$this->add_cart_data      = $this->get_add_cart_data();
		$this->product_identifier = $this->get_product_identifier();
		$this->gtag_deactivation  = $this->get_gtag_deactivation();


	    if($this->gtag_deactivation == 0) {
		    ?>
            <!--noptimize--><!--noptimize-->
            <!-- Global site tag (gtag.js) - Google Ads: <?php echo esc_html( $this->conversion_id ) ?> -->
            <script async
                    src="https://www.googletagmanager.com/gtag/js?id=AW-<?php echo esc_html( $this->conversion_id ) ?>"></script>
            <script>
                window.dataLayer = window.dataLayer || [];

                function gtag() {
                    dataLayer.push(arguments);
                }

                gtag('js', new Date());

                gtag('config', 'AW-<?php echo esc_html( $this->conversion_id ) ?>');
            </script>
            <!--/noptimize-->

		    <?php

	    }


        if ( is_order_received_page() ) {

	        // get order from URL and evaluate order total
	        $order_key      = $_GET['key'];
	        $order          = new WC_Order( wc_get_order_id_by_order_key( $order_key ) );

	        $options             = get_option( 'wgact_plugin_options' );
	        $order_total_setting = $options['order_total_logic'];
	        $order_total         = 0 == $order_total_setting ? $order->get_subtotal() - $order->get_total_discount(): $order->get_total();

	        // use the right function to get the currency depending on the WooCommerce version
	        $order_currency = $this->woocommerce_3_and_above() ? $order->get_currency() : $order->get_order_currency();


	        // filter to adjust the order value
	        $order_total_filtered = apply_filters( 'wgact_conversion_value_filter', $order_total, $order );


	        ?>

            <!--noptimize-->
            <!-- Global site tag (gtag.js) - Google Ads: <?php echo esc_html( $this->conversion_id ) ?> -->
	        <?php

	        // Only run conversion script if the payment has not failed. (has_status('completed') is too restrictive)
	        // Also don't run the pixel if an admin or shop manager is logged in.
	        if ( ! $order->has_status( 'failed' ) && ! current_user_can( 'edit_others_pages' ) && $this->add_cart_data == 0 ) {
//           if ( ! $order->has_status( 'failed' ) ) {
		        ?>

                <!-- Event tag for WooCommerce Checkout conversion page -->
                <script>
                    gtag('event', 'conversion', {
                        'send_to': 'AW-<?php echo esc_html( $this->conversion_id ) ?>/<?php echo esc_html( $this->conversion_label ) ?>',
                        'value': <?php echo $order_total_filtered; ?>,
                        'currency': '<?php echo $order_currency; ?>',
                        'transaction_id': '<?php echo $order->get_order_number(); ?>',
                    });
                </script>
		        <?php

	        } elseif ( ! $order->has_status( 'failed' ) && ! current_user_can( 'edit_others_pages' ) && $this->add_cart_data == 1 ){
		        ?>

		        <!-- Event tag for WooCommerce Checkout conversion page -->
		        <script>
                    gtag('event', 'purchase', {
                        'send_to': 'AW-<?php echo esc_html( $this->conversion_id ) ?>/<?php echo esc_html( $this->conversion_label ) ?>',
                        'transaction_id': '<?php echo $order->get_order_number(); ?>',
                        'value': <?php echo $order_total_filtered; ?>,
                        'currency': '<?php echo $order_currency; ?>',
	                    'discount': <?php echo $order->get_total_discount(); ?>,
	                    'aw_merchant_id': '<?php echo $options['aw_merchant_id']; ?>',
	                    'aw_feed_country': '<?php echo $this->get_visitor_country(); ?>',
	                    'aw_feed_language': '<?php echo $this->get_gmc_language(); ?>',
	                    'items': <?php echo json_encode( $this->get_order_items($order) ); ?>
                    });
		        </script>
		        <?php

	        } else {

		        ?>

                <!-- The Google Ads pixel has not been inserted. Possible reasons: -->
                <!--    You are logged into WooCommerce as admin or shop manager. -->
                <!--    The order payment has failed. -->
                <!--    The pixel has already been fired. To prevent double counting the pixel is not being fired again. -->

		        <?php
	        } // end if order status

	        ?>

            <!-- END Google Code for Sales (Google Ads) Conversion Page -->
            <!--/noptimize-->
	        <?php
        }
	}

	private function get_conversion_id() {
		$opt = get_option( 'wgact_plugin_options' );

		return $opt['conversion_id'];
	}

	private function get_conversion_label() {
		$opt = get_option( 'wgact_plugin_options' );

		return $opt['conversion_label'];
	}

	private function get_gtag_deactivation() {
		$opt = get_option( 'wgact_plugin_options' );

		return $opt['gtag_deactivation'];
	}

	private function get_add_cart_data() {
		$opt = get_option( 'wgact_plugin_options' );

		if ($opt == ''){
			return 0;
		} else {
			return $opt['add_cart_data'];
		}
	}

	private function get_product_identifier() {
		$opt = get_option( 'wgact_plugin_options' );

		return $opt['product_identifier'];
	}

	function get_visitor_country(){

	    $ip;

	    if ( $this->isLocalhost() ){
//	        error_log('check external ip');
		    $ip = WC_Geolocation::get_external_ip_address();
        } else {
//		    error_log('check regular ip');
		    $ip = WC_Geolocation::get_ip_address();
        }

		$location = WC_Geolocation::geolocate_ip($ip);

//	    error_log ('ip: ' . $ip);
//	    error_log ('country: ' . $location['country']);
		return $location['country'];
	}

	function isLocalhost() {
		return in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1']);
	}

	function get_gmc_language(){
		return strtoupper(substr( get_locale(), 0, 2 ));
	}

	private function get_order_items($order){

		$order_items       = $order->get_items();
		$order_items_array = array();

		foreach ( (array) $order_items as $item ) {

			$product = wc_get_product( $item['product_id'] );

			$item_details_array = array();

			// depending on setting use product IDs or SKUs
			if ( 0 == $this->product_identifier ) {

                $item_details_array['id'] = (string)$item['product_id'];

			} elseif ( 1 == $this->product_identifier ) {

				$item_details_array['id'] = 'woocommerce_gpf_' . $item['product_id'];

			} else {

				// fill the array with all product SKUs
				$item_details_array['id'] = (string)$product->get_sku();

			}

			$item_details_array['quantity'] = (int)$item['quantity'];
			$item_details_array['price']    = (int)$product->get_price();

            array_push($order_items_array, $item_details_array);

		}

		// apply filter to the $order_items_array array
		$order_items_array = apply_filters( 'wgdr_filter', $order_items_array, 'order_items_array' );

		return $order_items_array;

	}

}

$wgact = new WGACT();