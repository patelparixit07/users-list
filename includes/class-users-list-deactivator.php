<?php

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Users_List
 * @subpackage Users_List/includes
 * @author     Parixit Patel
 */

class Users_List_Deactivator {

	/**
	 * Call when plugin deactivated
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		
		// Clear cached data
		$transients = array(
			'ul_users',
			'ul_users_info',
			'ul_current_endpoint',
		);

		foreach ($transients as $transient) {
			delete_transient($transient);
		}

		flush_rewrite_rules();
		
	}

}
