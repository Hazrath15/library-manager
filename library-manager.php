<?php
/**
 * Plugin Name: Library Manager
 * Plugin URI:  https://github.com/Hazrath15/library-manager
 * Description: A WordPress plugin to manage a library system using a custom database table, REST API, and a React-based admin interface.
 * Version:     1.0.0
 * Author:      Hazrath Ali
 * Author URI:  https://github.com/Hazrath15
 * Text Domain: library-manager
 * Domain Path: /languages
 * License:     GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

define( 'LM_VERSION', '1.0.0' );
define( 'LM_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'LM_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once LM_PLUGIN_DIR . 'plugin.php';
Library_Manager_Plugin::lm_init();
