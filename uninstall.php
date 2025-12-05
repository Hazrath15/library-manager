<?php
/**
 * Uninstall script for Library Manager Plugin.
 *
 * @package Library_Manager
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

global $wpdb;

$library_manager_table = $wpdb->prefix . 'library_books';

$wpdb->query( "DROP TABLE IF EXISTS " . esc_sql( $library_manager_table ) );

// Delete plugin version option
delete_option( 'library_manager_db_version' );
