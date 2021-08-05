<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * @since      1.0.0
 *
 * @package    Users_List
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Delete plugin options
delete_option('users_list_options');

// Clear cached data
$transients = array(
	'ul_users',
	'ul_users_info',
	'ul_current_endpoint',
);

foreach ($transients as $transient) {
	delete_transient($transient);
}
