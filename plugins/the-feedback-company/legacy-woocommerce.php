<?php
	if (!defined('ABSPATH'))
		exit();

	class feedbackcompany_woocommerce
	{
		static $instance = false;

		private function __construct()
		{
			if (is_admin())
			{
/*	menu is disabled for legacy plugin, as there's a link from the main config menu

				add_action('admin_menu', array(
					$this,
					'menu_settings'
				));
*/
				add_action('admin_init', array(
					$this,
					'reg_settings'
				));
			}

			add_action('init', array(
				$this,
				'feedback'
			));
		}

		public static function getInstance()
		{
			if (!self::$instance)
				self::$instance = new self;
			return self::$instance;
		}

		public function menu_settings()
		{
			add_submenu_page('options-general.php', 'Feedback Company WooCommerce', 'Feedback Company WooCommerce', 'manage_options', 'fc-settings', array(
				$this,
				'fc_settings_display'
			));
		}

		public function reg_settings()
		{
			register_setting('fc_options', 'fc_options');
		}

		// trigger script loads based on settings
		public function feedback()
		{
			$fc_options = get_option('fc_options');

			// no values have been entered. bail.
			if (empty($fc_options['connector']))
				add_action('admin_notices', array(
					$this,
					'my_admin_error_notice_connector'
				));
			// fire if the order is set to pending
			elseif ($fc_options['trigger'] == 'iedere nieuwe bestelling')
				add_action('woocommerce_thankyou', array(
					$this,
					'wc_feedback'
				), 10, 1);
			// fire if the order is set to proces
			elseif ($fc_options['trigger'] == 'een bestelling in verwerking')
				add_action('woocommerce_order_status_processing', array(
					$this,
					'wc_feedback'
				), 10, 1);
			// fire if the order is set to completed
			elseif ($fc_options['trigger'] == 'een voltooide bestelling')
				add_action('woocommerce_order_status_completed', array(
					$this,
					'wc_feedback'
				), 10, 1);
		}

		// error notice if no connector code is entered
		function my_admin_error_notice_connector()
		{
			echo '<div id="message" class="error"><p>Om de WooCommerce plugin van Feedback Company te activeren moet je een connector code invullen.  Deze tref je aan bij het Feedback Company account.</p></div>';
		}

		// woocommerce transaction
		public function wc_feedback($order_id)
		{
			$fc_options = get_option('fc_options');
			// a few conditionals
			$double     = $fc_options['double_feedback'];

			if ($double == 'Nee')
				$fc_sdp = 0;
			else
				$fc_sdp = 1;

			$tone_voice = $fc_options['tone'];

			if ($tone_voice == 'Informeel')
				$fc_fct = 0;
			else
				$fc_fct = 1;

			// get the WooCommerce details
			global $woocommerce;

			if (!$order_id)
				return;

			$order = new WC_Order($order_id);

			if ($order->has_status('failed'))
				return;

			// sets delay days
			$delay_time          = $fc_options['delay'];
			// sets delay remember
			$delay_remember      = $fc_options['remember'];
			// sets double invitation or not
			$send_double         = $fc_sdp;
			// sets tone of voice
			$tone_title          = $fc_fct;
			// connector code
			$cc_code = $fc_options['connector'];
			// woocommerce billing e-mailaddress
			$user_mail           = $order->get_billing_email();
			// woocommerce order number
			$order_number        = $order->get_order_number();
			// user first name
			$order_first_name    = $order->get_billing_first_name();
			// user last name
			$order_last_name     = $order->get_billing_last_name();
			// informal salutation, combine first and last name
			$order_name_inf_comb = $order_first_name . ' ' . $order_last_name;
			// informal salutation, replace space with %20
			$order_name_inf      = str_replace(' ', '%20', $order_name_inf_comb);
			// formal salutation, last name
			$order_name_for_comb = 'heer/mevrouw%20' . $order_last_name;
			// formal salutation, replace space with %20
			$order_name_for      = str_replace(' ', '%20', $order_name_for_comb);
			// get order items details en set items counter
			$itms_counter        = 1; // setup counter
			$itm_txt = '';
			$itm_url = '';
			$itm_sku = '';
			$itm_img = '';
			$order_itms          = $order->get_items();

			// loop through items in order
			foreach ($order_itms as $order_itm)
			{
				$product_variation_id = $order_itm['variation_id']; // check if product has variation
				$product_id = $order_itm['product_id']; // ID
				$shop_product = $order->get_product_from_item($order_itm);

				$itm_sku_single       = $shop_product->get_sku(); // get SKU

				if (!empty($itm_sku_single))
				{ // filter out items without SKU
					$product_name         = $order_itm['name']; // product name
					$product_name_encoded = rawurlencode($product_name); // encode product name
					$itm_txt .= "&product_text%5B$itms_counter%5D=" . $product_name_encoded; // construct product names with counter
					$product_id = $order_itm['product_id']; // product id
					$product_lnk = get_permalink($product_id); // product permalink
					$product_lnk_encoded = rawurlencode($product_lnk); // encode product url
					$itm_url .= "&product_url%5B$itms_counter%5D=" . $product_lnk_encoded; // construct link with counter
					$itm_sku_encoded = rawurlencode($itm_sku_single); // encode sku
					$itm_sku .= "&product_ids%5B$itms_counter%5D=SKU=" . $itm_sku_encoded; // construct SKU's with counter
					$itm_img_link         = wp_get_attachment_url(get_post_thumbnail_id($shop_product->id)); // get product image url
					$itm_img_link_encoded = rawurlencode($itm_img_link); // encode
					if (!empty($itm_img_link)) // check if item has image
						$itm_img .= "&product_photo%5B$itms_counter%5D=" . $itm_img_link_encoded; // construct image link with counter

					$itms_counter++; // start counting items in order
				}
			}

			// set email address to lower case
			$str_mailaddress = strtolower($user_mail);
			// remove white space(s) and/or space(s) from connector code
			$cc_code         = str_replace(' ', '', $cc_code);
			$cc_code         = preg_replace('/\s+/', '', $cc_code);
			// do the Chksum
			$chsum           = $str_mailaddress;
			$arr1 = str_split($chsum);
			$sum = 0;
			foreach ($arr1 as $item)
				$sum += ord($item);

			// check salutation
			if ($fc_fct == 0)
				$order_name = $order_name_inf;
			else
				$order_name = $order_name_for;

			// construct the url
			$fc_url = 'https://connect.feedbackcompany.nl/feedback/?action=sendInvitation&connector='
				. $cc_code . '&user=' . $str_mailaddress . '&delay=' . $delay_time . '&remindDelay=' . $delay_remember
				. '&resendIfDouble=' . $send_double . '&orderNumber=' . $order_number
				. '&aanhef=' . $order_name . $itm_txt . $itm_url . $itm_sku . $itm_img
				. '&chksum=' . $sum;

			// for debugging, write url to error log
			// error_log($fc_url);

			// call the url
			wp_remote_get($fc_url);
		}

		// display user settings
		public function fc_settings_display()
		{
?>
        <div class="wrap">

<div class="options">
  <div class="fb_form_options">
    <form method="post" action="options.php">
    <?php
        settings_fields('fc_options');
        $fc_options         = get_option('fc_options');
        $fc_trigger         = $fc_options['trigger'];
        $fc_double_feedback = $fc_options['double_feedback'];
        $fc_tone= $fc_options['tone'];
?>

    <table class="form-table fc-table">
    <tbody>
        <tr>
<th><label for="fc_options[connector]"><?php
        _e('Uw connector code:');
?></label></th>
<td>
    <input type="text"  placeholder="Voer hier je code in" style="width: 150px;" class="small-text" value="<?php
        if (isset($fc_options['connector']))
echo $fc_options['connector'];
?>" id="connector" name="fc_options[connector]">
    <span class="description"><?php
        _e('Vier hier de unieke connector code in van Feedback Company.');
?></span>
</td>
        </tr>

        <tr>
<th><label for="fc_options[delay]"><?php
        _e('Vertraging:');
?></label></th>
<td>
    <input type="text" style="width: 35px;" class="small-text" value="<?php
        if (isset($fc_options['delay']))
echo $fc_options['delay'];
?><?php
        if (!isset($fc_options['delay']) || $fc_options['delay'] == null)
echo '7';
?>" id="delay" name="fc_options[delay]">
    <span class="description"><?php
        _e('Het aantal dagen dat het verzenden van de beoordeling vertraagd moet worden. 0 is direct versturen. Standaardwaarde is 7.');
?></span>
</td>
        </tr>

        <tr>
<th><label for="fc_options[remember]"><?php
        _e('Herinneringsvertraging:');
?></label></th>
<td>
    <input type="text" style="width: 35px;" class="small-text" value="<?php
        if (isset($fc_options['remember']))
echo $fc_options['remember'];
?><?php
        if (!isset($fc_options['remember']) || $fc_options['remember'] == null)
echo '14';
?>" id="remember" name="fc_options[remember]">
    <span class="description"><?php
        _e('Het aantal dagen waarna een herinnering moet worden verstuurd. Standaardwaarde is 14. 0 is geen herinnering versturen.');
?></span>
</td>
        </tr>

					<tr>
					    <th><label for="fc_options[double_feedback]"><?php
        _e('Verstuur dubbele beoordelingen:');
?></label></th>
					    <td>
					   				<select id="fc_double_feedback" name="fc_options[double_feedback]">
					   					<option <?php
        if ('Nee' == $fc_double_feedback)
echo 'selected="selected"';
?>>Nee</option>
					   					<option <?php
        if ('Ja' == $fc_double_feedback)
echo 'selected="selected"';
?>>Ja</option>
					   				</select>
					   				<span class="description"><?php
        _e('Gebruik deze optie om aan te geven of een klant meerdere beoordelingsverzoeken per order mag ontvangen.');
?></span>
					   				</td>
					</tr>

					<tr>
					    <th><label for="fc_options[tone]"><?php
        _e('Aanhef beoordelingsverzoek:');
?></label></th>
					    <td>
					   				<select id="fc_tone" name="fc_options[tone]">
					   					<option <?php
        if ('Informeel' == $fc_tone)
echo 'selected="selected"';
?>>Informeel</option>
					   					<option <?php
        if ('Formeel' == $fc_tone)
echo 'selected="selected"';
?>>Formeel</option>
					   				</select>
					   				<span class="description"><?php
        _e('Informeel is "Beste Theo Testpersoon". Formeel is "Geachte heer/mevrouw Testpersoon".');
?></span>
					   				</td>
					</tr>

					<tr>
					    <th><label for="fc_options[trigger]"><?php
        _e('Verstuur het beoordelingsverzoek bij:');
?></label></th>
					    <td>
					   				<select id="fc_trigger" name="fc_options[trigger]">
					   			     	<option <?php
        if ('een voltooide bestelling' == $fc_trigger)
echo 'selected="selected"';
?>>een voltooide bestelling</option>
					   			     	<option <?php
        if ('een bestelling in verwerking' == $fc_trigger)
echo 'selected="selected"';
?>>een bestelling in verwerking</option>
					   					<option <?php
        if ('iedere nieuwe bestelling' == $fc_trigger)
echo 'selected="selected"';
?>>iedere nieuwe bestelling</option>

					   				</select>
					   				<span class="description"><?php
        _e('Kies hier het moment waarop het beoordelingsverzoek naar Feedback Company wordt verstuurd.');
?></span>
					   				</td>
					</tr>

    </tbody>
    </table>

  				<p><input type="submit" class="button-primary" value="<?php
        _e('Save Changes');
?>" /></p>
    </form>
    </div>
</div>
        </div>
    <?php
		}
	}

	$feedbackcompany_woocommerce = feedbackcompany_woocommerce::getInstance();
