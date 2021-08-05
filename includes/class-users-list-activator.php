<?php

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Users_List
 * @subpackage Users_List/includes
 * @author     Parixit Patel
 */

class Users_List_Activator {

	/**
	 * Call when plugin activated
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		$plugin_defaults = Users_List_Defaults::getInstance();
		$defaults = $plugin_defaults->getDefaults();

		set_transient( 'ul_flush_rewrite', 1, 60*60 );
		set_transient( 'ul_current_endpoint', $defaults['custom_endpoint'], 60*60 );

		// Add default plugin options		
	    update_option( 'users_list_options', $defaults );
	    
	}

}
