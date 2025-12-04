<?php
/**
 * Admin Menu Class
 *
 * @package Library_Manager
 */

if( !class_exists( 'Admin_Menu' ) ) {
    class Admin_Menu {
        public function __construct() {
            add_action( 'admin_menu', array( $this, 'lm_register_menu' ) );
        }
        public function lm_register_menu() {
            add_menu_page(
                'Library Manager',
                'Library Manager',
                'manage_options',
                'library-manager',
                array( $this, 'lm_render_dashboard' ),
                'dashicons-book',
                36
            );
        }   
        public function lm_render_dashboard() {
            echo '<div id="library-manager-dashboard"></div>';
        }
    }
}
new Admin_Menu();
