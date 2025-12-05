<?php
/**
 * Uninstall script for Library Manager Plugin.
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

global $wpdb;

// Table name
$table = $wpdb->prefix . 'library_books';

$wpdb->query( "DROP TABLE IF EXISTS {$table}" );

delete_option( 'lm_db_version' );
