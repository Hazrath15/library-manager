<?php
/**
 * Library Manager Plugin Class
 *
 * @package Library_Manager
 */

if( !class_exists( 'Library_Manager_Plugin' ) ) {
    class Library_Manager_Plugin {
        public static function lm_init() {
            require_once LM_PLUGIN_DIR . 'includes/class-admin-menu.php';
            add_action( 'admin_enqueue_scripts', array( __CLASS__, 'lm_enqueue_scripts' ) );
        }
        
        public static function lm_enqueue_scripts( $hook) {
            if( 'toplevel_page_library-manager' !== $hook ) {
                return;
            }

            // Get the asset file created by wp-scripts
            $asset_file_path = plugin_dir_path(__FILE__) . 'build/index.asset.php';
            if (!file_exists($asset_file_path)) {
                return;
            }
            $asset_file = include $asset_file_path;

            // Enqueue the main JavaScript file
            wp_enqueue_script(
                'library-manager-script',
                LM_PLUGIN_URL . 'build/index.js',
                $asset_file['dependencies'],
                $asset_file['version'],
                true 
            );

            // Enqueue the CSS file
            wp_enqueue_style(
                'library-manager-style',
                LM_PLUGIN_URL . 'build/index.css',
                array(),
                $asset_file['version']
            );
        }
    }
    
}
