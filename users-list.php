<?php

/**
 * @since             1.0.0
 * @package           Users_List
 *
 * @wordpress-plugin
 * Plugin Name:       Users List
 * Description:       List Users from external REST API Service.
 * Version:           1.0.0
 * Author:            Parixit Patel
 * Author URI:        http://github.com/patelparixit07
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       users-list
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( is_readable( __DIR__ . '/vendor/autoload.php' ) ) {
    require __DIR__ . '/vendor/autoload.php';
}

/**
 * Currently plugin version.
 */
define( 'USERS_LIST_VERSION', '1.0.0' );
define( 'USERS_LIST_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * Plugin Activated
 */
function activate_users_list() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-users-list-activator.php';
	Users_List_Activator::activate();
}

/**
 * Plugin Deactivated
 */
function deactivate_users_list() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-users-list-deactivator.php';
	Users_List_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_users_list' );
register_deactivation_hook( __FILE__, 'deactivate_users_list' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-users-list.php';

/**
 * Begins execution of the plugin.
 *
 * @since    1.0.0
 */
function run_users_list() {

	$plugin = new Users_List();
	$plugin->run();

}
run_users_list();