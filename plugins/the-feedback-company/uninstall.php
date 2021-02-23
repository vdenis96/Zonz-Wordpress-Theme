<?php

/**
 * This file cleans up when the plugin is uninstalled from Wordpress
 */

// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN'))
	die;

delete_option('feedbackcompany_options');
delete_option('fc_options');

delete_option('feedbackcompany_wordpressmultilanguage');
delete_option('feedbackcompany_oauth_client_id');
delete_option('feedbackcompany_oauth_client_secret');
delete_option('feedbackcompany_access_token');
delete_option('feedbackcompany_invitation_enabled');
delete_option('feedbackcompany_invitation_orderstatus');
delete_option('feedbackcompany_invitation_delay');
delete_option('feedbackcompany_invitation_delay_unit');
delete_option('feedbackcompany_invitation_reminder_enabled');
delete_option('feedbackcompany_invitation_reminder');
delete_option('feedbackcompany_invitation_reminder_unit');
delete_option('feedbackcompany_mainwidget_size');
delete_option('feedbackcompany_mainwidget_amount');
delete_option('feedbackcompany_stickywidget_enabled');
delete_option('feedbackcompany_productreviewsextendedwidget_displaytype');
delete_option('feedbackcompany_productreviewsextendedwidget_toggle_element');
delete_option('feedbackcompany_productreviews_enabled');

foreach (['main', 'bar', 'product-summary', 'product-extended'] as $widget_type) {
    delete_option('feedbackcompany_widget_uuid_'.$widget_type);
    delete_option('feedbackcompany_widget_id_'.$widget_type);
}

// for site options in Multisite
delete_site_option('feedbackcompany_options');
delete_site_option('fc_options');

// drop our error log table
global $wpdb;
$table_name = $wpdb->prefix.'feedbackcompany_errorlog';
$sql = "DROP TABLE IF EXISTS $table_name;";
$wpdb->query($sql);

