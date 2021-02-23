<?php
/*

Plugin Name:	Feedback Company
Plugin URI:	https://feedbackcompany.com/
Description:	Integrates Feedback Company widgets, order registrations and product reviews in Wordpress/WooCommerce
Version:	2.4.4
Author:		Feedback Company
Author URI:	https://www.feedbackcompany.com/
License:	GPL2

WC requires at least: 3.4.0
WC tested up to: 4.0.0

This plugin is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

This plugin is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this plugin. If not, see:
https://www.gnu.org/licenses/old-licenses/gpl-2.0.html.

*/


/**
 * This is the main plugin file, which is always loaded by Wordpress
 * as long as the plugin is active
 */

// security - stop if this file isn't accessed via WP
if (!defined('ABSPATH'))
	exit;



/**
 * Function to check if WooCommerce is enabled
 *
 * this function additionally checks if the stone-age Feedback Company Connector plugin is enabled
 * and instructs users to delete it
 */
function feedbackcompany_woocommerce_enabled()
{
	static $ret;
	if ($ret !== null)
		return $ret;

	$active_plugins = apply_filters('active_plugins', get_option('active_plugins'));
	if (in_array('woocommerce/woocommerce.php', $active_plugins))
		$ret = true;
	else
		$ret = false;

	// this check will have to stay active until the old connector plugin no longer works
	// in order to prevent double order registrations
	//
	// the original feedback-company-connector plugin is very outdated.
	// if it is still active in Wordpress, tell user to trash & burn it
	// unfortunately we cannot continue with WooCommerce integration as long as the old plugin is active
	if (in_array('the-feedback-company-connector/feedback.php', $active_plugins)
		|| class_exists('WPfeedback'))
	{
		add_action('admin_notices', 'feedbackcompany_admin_error_legacyplugin');
		$ret = false;
	}

	return $ret;
}

/**
 * Function to check if WordPress Multilanguage plugin is enabled
 */
function feedbackcompany_wordpressmultilanguage_enabled()
{
	static $ret;
	if ($ret !== null)
		return $ret;

	$ret = false;

	// get all active plugins and check if WordPress Multilanguage plugin is in the array
	$plugins = apply_filters('active_plugins', get_option('active_plugins'));
	if (in_array('sitepress-multilingual-cms/sitepress.php', $plugins))
	{
		$ret = true;
	}

	return $ret;
}

/**
 * Function to check if legacy widgets from v1 of this plugin are in use
 *
 * If it is, the legacy widgets portion of this plugin is loaded
 * for backwards compatibility
 */
function feedbackcompany_legacywidgets_enabled()
{
	static $ret;
	if ($ret !== null)
		return $ret;

	$ret = true;

	// if there is no star type configured (legacy widgets require these), then legacy plugin was never active
	$options = get_option('feedbackcompany_options');
	if (!isset($options['star_type']))
		$ret = false;

	return $ret;
}

/**
 * Function to check if legacy Wocommerce from v1 of this plugin are in use
 *
 * If it is, the legacy woocommerce portion of this plugin is loaded
 * for backwards compatibility
 */
function feedbackcompany_legacywoocommerce_enabled()
{
	static $ret;
	if ($ret !== null)
		return $ret;

	$ret = true;

	// if woocommerce isn't enabled, then never load the legacy plugin
	if (!feedbackcompany_woocommerce_enabled())
		$ret = false;

	// if there's no legacy connector code entered, the legacy plugin was never active
	$legacy_options = get_option('fc_options');
	if (!isset($legacy_options['connector']))
		$ret = false;

	return $ret;
}



/**
 * Class which interfaces our Feedback Company API library with Wordpress
 */
class feedbackcompany_api_ext_wp
{
	private $wp_options;

	/**
	 * Constructor checks if legacy plugins are enabled and if automatic migration is possible
	 *
	 * This code can eventually be dropped once the legacy Feedback Company connector is no longer supported
	 */
	function __construct()
	{
		$this->wp_options = get_option('feedbackcompany_options');

		// backwards compatibility - merchant widget renamed between version 2.3.2 and 2.4
		if (get_option('feedbackcompany_merchantreviewswidget_size'))
		{
			error_log('migrated config to 2.4');
			update_option('feedbackcompany_mainwidget_size', get_option('feedbackcompany_merchantreviewswidget_size'));
			delete_option('feedbackcompany_merchantreviewswidget_size');
			update_option('feedbackcompany_mainwidget_amount', get_option('feedbackcompany_merchantreviewswidget_amount'));
			delete_option('feedbackcompany_merchantreviewswidget_amount');
		}

		// backwards compatibility - migrate oauth settings from 1.x to 2.x
		if (isset($this->wp_options['oauth_client_id']))
		{
			update_option('feedbackcompany_oauth_client_id', $this->wp_options['oauth_client_id']);
			update_option('feedbackcompany_oauth_client_secret', $this->wp_options['oauth_client_secret']);

			unset($this->wp_options['oauth_client_secret']);
			unset($this->wp_options['oauth_client_id']);
			update_option('feedbackcompany_options', $this->wp_options);
		}

		// legacy - migrate from legacy woocommece connector to 2.x order registration
		if (feedbackcompany_legacywoocommerce_enabled() && get_option('feedbackcompany_oauth_client_id') && get_option('feedbackcompany_oauth_client_secret'))
		{
			$legacy_options = get_option('fc_options');
			if ($legacy_options['delay'] >= $legacy_options['remember'])
				$legacy_options['remember']++;

			update_option('feedbackcompany_invitation_delay', $legacy_options['delay']);
			update_option('feedbackcompany_invitation_delay_unit', 'days');
			update_option('feedbackcompany_invitation_reminder', $legacy_options['remember']);
			update_option('feedbackcompany_invitation_reminder_unit', 'days');

			if ($legacy_options['trigger'] == 'iedere nieuwe bestelling')
				update_option('feedbackcompany_invitation_orderstatus', 'wc-pending');
			elseif ($legacy_options['trigger'] == 'een bestelling in verwerking')
				update_option('feedbackcompany_invitation_orderstatus', 'wc-processing');
			else
				update_option('feedbackcompany_invitation_orderstatus', 'wc-completed');

			delete_option('fc_options');
		}
	}

	/**
	 * Function gets a Wordpress option from the wp_options table
	 *
	 * @param string $key - the specific key to fetch
	 */
	function get_option($key)
	{
		if (get_option($key))
			return get_option($key);

		// legacy options
		if (isset($this->wp_options[$key]))
			return $this->wp_options[$key];

		// backwards compatibility for 1.0 to 1.0.1
		// this can be removed as soon as support for legacy widgets is dropped
		if ($key == 'title_score')
			return 'Klantbeoordelingen';
		if ($key == 'title_reviews')
			return 'Recente beoordelingen';
		if ($key == 'title_testimonial')
			return 'Beoordeling van';
		// end backwards compatibility

		return false;
	}

	/**
	 * legacy function (for rich snippets)
	 *
	 * this code can be removed when support for legacy widgets is
	 */
	function get_options($prefix)
	{
		$ret = array();

		foreach ($this->wp_options as $key => $value)
			if (substr($key, 0, strlen($prefix)) == $prefix)
				$ret[substr($key, strlen($prefix))] = $value;

		return $ret;
	}

	/**
	 * wrapper function for Wordpress update_option
	 *
	 * https://codex.wordpress.org/Function_Reference/update_option
	 */
	function update_option($key, $value)
	{
		update_option($key, $value);
	}

	/**
	 * wrapper function for Wordpress get_transient
	 *
	 * this function exists so 'feedbackcompany_' is automatically appended to every transient
	 *
	 * https://codex.wordpress.org/Function_Reference/get_transient
	 */
	function get_cache($key)
	{
		return get_transient('feedbackcompany_'.$key);
	}

	/**
	 * wrapper function for Wordpress set_transient
	 *
	 * this function exists so 'feedbackcompany_' is automatically appended to every transient
	 *
	 * https://codex.wordpress.org/Function_Reference/set_transient
	 */
	function set_cache($key, $value, $expiration)
	{
		set_transient('feedbackcompany_'.$key, $value, $expiration);
	}

	/**
	 * wrapper function for Wordpress delete_transient
	 *
	 * this function exists so 'feedbackcompany_' is automatically appended to every transient
	 *
	 * https://codex.wordpress.org/Function_Reference/delete_transient
	 */
	function delete_cache($key)
	{
		delete_transient('feedbackcompany_'.$key);
	}

	/**
	 * wrapper function for Wordpress plugins_url
	 *
	 * this returns the URL where the plugin directory lives
	 *
	 * https://codex.wordpress.org/Function_Reference/plugins_url
	 */
	function get_url()
	{
		return plugins_url('', __FILE__);
	}

	/**
	 * Function for logging API errors
	 *
	 * called from the Feedback Company API library
	 * this function creates the relevant database table if it does not exist yet
	 * api errors can be downloaded from the admin dashboard
	 *
	 * @param string $url - the URL called for the API request
	 * @param string $call - the data sent for the API request
	 * @param string $response - the data returned for the API request
	 */
	function log_apierror($url, $call, $response)
	{
		// create table if not exists
		// this is done here, rather than on installation/activation, because
		// API errors should never happen, thus there is no need to have this table
		// on every installation.  it is only for case of emergencies
		global $wpdb;
		$table_name = $wpdb->prefix.'feedbackcompany_errorlog';
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE IF NOT EXISTS $table_name (
			  `timestamp` timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL,
			  `url` text NOT NULL,
			  `call` text NOT NULL,
			  `response` text NOT NULL
			) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

		$wpdb->insert($table_name, array('url' => $url, 'call' => $call, 'response' => $response));
	}
}



/**
 * Function to interface with the library API
 *
 * Calling this function returns an instance of the API library
 * This function is created to prevent the API library from being initiated multiple times
 */
function feedbackcompany_api_wp()
{
	static $fbcapi;
	if ($fbcapi !== null)
		return $fbcapi;

	// include feedback company php api
	require_once plugin_dir_path(__FILE__).'lib/feedbackcompany_api.php';
	$fbcapisettings = new feedbackcompany_api_ext_wp();
	$fbcapi = new feedbackcompany_api($fbcapisettings);

	return $fbcapi;
}

/**
 * call this function once, to initialize a possible migration
 * this code be removed one legacy goes out of style
 */
$feedbackcompany_api = feedbackcompany_api_wp();



/**
 * Callback functions for Wordpress shortcode output
 *
 * Functions render the appropriate Feedback Company widget
 *
 * https://codex.wordpress.org/Shortcode_API
 */
function feedbackcompany_shortcode_widget_main($atts, $content = "")
{
	return feedbackcompany_api_wp()->get_widget_main();
}
add_shortcode('feedbackcompany_badge', 'feedbackcompany_shortcode_widget_main');
// legacy shortcode
add_shortcode('feedback_company_merchant_reviews_widget', 'feedbackcompany_shortcode_widget_main');

function feedbackcompany_shortcode_widget_bar($atts, $content = "")
{
	return feedbackcompany_api_wp()->get_widget_bar();
}
add_shortcode('feedbackcompany_bar', 'feedbackcompany_shortcode_widget_bar');



/**
 * Callback classes for Wordpress widgets
 *
 * Class renders the appropriate Feedback Company widget and settings form
 *
 * https://codex.wordpress.org/Widgets_API
 */
class feedbackcompany_widget_main extends WP_Widget
{
	function __construct()
	{
		parent::__construct(false, 'Feedback Company Badge Widget');
	}

	function widget($args, $instance)
	{
		echo $args['before_widget']
			. feedbackcompany_api_wp()->get_widget_main()
			. $args['after_widget'];
	}
	function form($instance)
	{
		echo '<p>Please use the menu option under \'Settings\' to change appearance of this widget.</p>';
	}
}
class feedbackcompany_widget_bar extends WP_Widget
{
	function __construct()
	{
		parent::__construct(false, 'Feedback Company Bar Widget');
	}

	function widget($args, $instance)
	{
		echo $args['before_widget']
			. feedbackcompany_api_wp()->get_widget_bar()
			. $args['after_widget'];
	}
	function form($instance)
	{
		echo '<p>This widget has no configuration.</p>';
	}
}

/**
 * register the widget classes for the appropriate widgets with Wordpress
 */
function feedbackcompany_register_widgets()
{
	register_widget('feedbackcompany_widget_main');
	register_widget('feedbackcompany_widget_bar');
}
add_action('widgets_init', 'feedbackcompany_register_widgets');



/**
 * if the sticky widget is enabled, hook it's output into the footer
 */
if (get_option('feedbackcompany_stickywidget_enabled'))
	add_action('wp_footer', array(feedbackcompany_api_wp(), 'output_widget_sticky'));



/**
 * dynamic loading
 *
 * following parts of the file are to dynamically load portions of this plugin
 * which aren't required per say
 */

/** admin settings portion of this plugin is only loaded if we're on the admin dashboard */
if (is_admin())
{
	require_once plugin_dir_path(__FILE__).'admin.php';
}

/** load the legacy widgets portion of this plugin only if the legacy widgets are still in use */
if (feedbackcompany_legacywidgets_enabled())
{
	require_once plugin_dir_path(__FILE__).'legacy-widgets.php';
}

/** load the WooCommerce portion of this plugin only if WooCommerce is active */
if (feedbackcompany_woocommerce_enabled())
{
	// load the legacy WooCommerce portion of this plugin if legacy is still enabled
	if (feedbackcompany_legacywoocommerce_enabled())
		require_once plugin_dir_path(__FILE__).'legacy-woocommerce.php';
	// else load the current WooCommerce portion
	else
		require_once plugin_dir_path(__FILE__).'woocommerce.php';
}



/**
 * admin notice function for legacy woocommerce connector check
 *
 * this displays an error if the user has the legacy FBC plugin enabled
 * because the plugin is outdated and should be dropped
 *
 * can be dropped as soon as the old connector interface is no longer supported
 */
function feedbackcompany_admin_error_legacyplugin()
{
	echo '<div id="message" class="error">'
		. '<p><img align="right" src="'.plugins_url('/images/feedbackcompany-logo-162x46.png', __FILE__).'">'
		. '<strong>Warning!</strong>  The "Feedback Company WooCommerce Connector" plugin is active in this Wordpress '
		. 'installation.  This plugin is outdated and will soon no longer work.  The functionality of this plugin has been '
		. 'integrated into the regular Feedback Company plugin, which is also active. '
		. 'In order to use the new plugin, all you have to do is deactivate & delete the old one.'
		. '<br><br>'
		. '<strong>Please <a href="plugins.php">deactivate and delete</a> the "Feedback Company WooCommerce Connector" plugin. '
		. '</strong></p></div>';
}

