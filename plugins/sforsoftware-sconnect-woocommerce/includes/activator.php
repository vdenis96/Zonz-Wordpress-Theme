<?php

class Sforsoftware_Sconnect_Woocommerce_Activator {

    public static function activate(){
        $uploads_dir = wp_upload_dir();
        if(!is_dir($uploads_dir['basedir'] . '/import/')){
            wp_mkdir_p($uploads_dir['basedir'] . '/import/');
        }
    }

    public static function create_db_table(){
        global $wpdb;

        $table_name = $wpdb->prefix . 'sforsoftware_sconnect_export_order';

        $sql = "CREATE TABLE $table_name (
         id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
         order_id bigint(20) NOT NULL,
         created_at bigint(20) NOT NULL,
         exported_at bigint(20),
         PRIMARY KEY  (id)
         );";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }
}