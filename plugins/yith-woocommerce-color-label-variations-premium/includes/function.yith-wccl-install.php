<?php
/**
 * Install function
 *
 * @author Yithemes
 * @package YITH WooCommerce Color and Label Variations Premium
 * @version 1.0.0
 */
if ( ! defined( 'YITH_WCCL' ) ) {
	exit;
} // Exit if accessed directly

global $yith_wccl_db_version;
$yith_wccl_db_version = '1.0.0';

function yith_wccl_install() {
	global $wpdb;
	global $yith_wccl_db_version;

	$installed_ver = get_option( "yith_wccl_db_version" );

	if ( $installed_ver != $yith_wccl_db_version ) {

		$table_name = $wpdb->prefix . 'yith_wccl_meta';

		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
		meta_id bigint(20) NOT NULL AUTO_INCREMENT,
		wc_attribute_tax_id bigint(20) NOT NULL,
		meta_key varchar(255) DEFAULT '',
		meta_value longtext DEFAULT '',
		PRIMARY KEY (meta_id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

		add_option( 'yith_wccl_db_version', $yith_wccl_db_version );
	}
}

function yith_wccl_update_db_check() {
	global $yith_wccl_db_version;

	if ( get_site_option( 'yith_wccl_db_version' ) != $yith_wccl_db_version ) {
		yith_wccl_install();
	}
}