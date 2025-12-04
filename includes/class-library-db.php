<?php
/**
 * Database Class
 *
 * @package Library_Manager
 */

if( !class_exists( 'Library_Manager_Database' ) ) {
    class Library_Manager_Database {
        public static function create_table() {
            global $wpdb;

            $table_name      = $wpdb->prefix . 'library_books';
            $charset_collate = $wpdb->get_charset_collate();

            // Table schema
            $sql = "CREATE TABLE $table_name (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                title VARCHAR(255) NOT NULL,
                description LONGTEXT NULL,
                author VARCHAR(255) NULL,
                publication_year INT NULL,
                status ENUM('available', 'borrowed', 'unavailable') DEFAULT 'available',
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY  (id)
            ) $charset_collate;";

            // Load dbDelta
            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
            dbDelta($sql);

            add_option('lm_db_version', '1.0.0');
        }
    }
}