<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and hooks
 *
 * @package    Users_List
 * @subpackage Users_List/admin
 * @author     Parixit Patel
 */

class Users_List_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The options of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $options    The options of this plugin.
	 */
	private $options;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/users-list-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/users-list-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Add an options page under the Settings submenu
	 *
	 * @since  1.0.0
	 */
    public function add_options_page() {
        
        add_options_page(
            __( 'Users List Settings', 'users-list' ),
            __( 'Users List', 'users-list' ), 
            'manage_options', 
            $this->plugin_name, 
            array( $this, 'display_options_page' )
        );
    }

    /**
	 * Render the options page for plugin
	 *
	 * @since  1.0.0
	 */
    public function display_options_page() {

    	$this->options = get_option( 'users_list_options' );

    	include_once 'partials/users-list-admin-display.php';

    }

    /**
	 * Register settings
	 *
	 * @since  1.0.0
	 */
    public function register_setting() {

    	register_setting( 
			$this->plugin_name, 
			'users_list_options', 
			array( $this, 'ul_sanitize_options' ) 
		);

    	add_settings_section(
			'users_list_configure_endpoint',
			__( 'Configure Endpoint', 'users-list' ),
			array( $this, 'ul_configure_endpoint_cb' ),
			$this->plugin_name
		);

		add_settings_field(
			'custom_endpoint',
			__( 'Endpoint', 'users-list' ),
			array( $this, 'ul_custom_endpoint_cb' ),
			$this->plugin_name,
			'users_list_configure_endpoint',
			array( 'label_for' => 'users_list_options_custom_endpoint' )
		);

		add_settings_section(
			'users_list_configure_url',
			__( 'Configure URLs', 'users-list' ),
			array( $this, 'ul_configure_url_cb' ),
			$this->plugin_name
		);		

		add_settings_field(
			'users_api_url',
			__( 'Users API URL', 'users-list' ),
			array( $this, 'ul_users_api_url_cb' ),
			$this->plugin_name,
			'users_list_configure_url',
			array( 'label_for' => 'users_list_options_users_api_url' )
		);

	}

	/**
	 * Render the text for the Configure Endpoint section
	 *
	 * @since  1.0.0
	 */
	public function ul_configure_endpoint_cb() {

		echo '<p>' . __( 'Update custom page slug to browse users list. Make sure its unique throughout the site.', 'users-list' ) . '</p>';

	}

	/**
	 * Render the text for the Configure URL section
	 *
	 * @since  1.0.0
	 */
	public function ul_configure_url_cb() {

		echo '<p>' . __( 'Please change url from where users list will be fetch.', 'users-list' ) . '</p>';

	}

	/**
	 * Render the Endpoint input option
	 *
	 * @since  1.0.0
	 */
	public function ul_custom_endpoint_cb() {

		printf('
				<code>'.home_url().'/</code>
			');

		printf('
				<input type="text" name="users_list_options[custom_endpoint]" id="users_list_options_custom_endpoint" value="%s" required> ',
				isset( $this->options['custom_endpoint'] ) ? $this->options['custom_endpoint'] : ''
			);

		printf('
				<p class="description"> '.__( '<i>Update custom endpoint slug. For example: users, users-list etc.</i>', 'users-list' ).' </p>
			');

	}

	/**
	 * Render the Users API URL input option
	 *
	 * @since  1.0.0
	 */
	public function ul_users_api_url_cb() {

		printf('
				<input type="text" name="users_list_options[users_api_url]" id="users_list_options_users_api_url" value="%s" required>',
				isset( $this->options['users_api_url'] ) ? esc_url_raw( $this->options['users_api_url']) : ''
			);

		printf('
				 <p class="description"> '.__( '<i>Users List API. For example:<code>https://jsonplaceholder.typicode.com/users</code></i>', 'users-list' ).' </p>
			');

		printf('
				 <p> '.__( '<i><b>NOTE : </b>URL should be configured in way such that single user information can be fetch by passing user id as argument. ( For example: <code>https://jsonplaceholder.typicode.com/users/10</code></i>', 'users-list' ).')</p>
			');
	}

	/**
     * Sanitize setting fields
     *
     * @param array $input Contains all settings fields as array keys
     * @since  1.0.0
     */
    public function ul_sanitize_options( $input ) {

    	$valid = true;

    	$input['users_api_url'] = trim($input['users_api_url']);
    	$input['custom_endpoint'] = trim($input['custom_endpoint']);

    	if(empty($input['users_api_url']) || empty($input['custom_endpoint'])){

    		if(empty($input['users_api_url']))
    		{
				$valid = false;
	    		add_settings_error(
		            'users_api_url',
		            'users_list_options_users_api_url',
		            __( 'Users API URL is required', 'users-list' ),
		            'error'
		        );
    		}

    		if(empty($input['custom_endpoint']))
    		{
				$valid = false;
	    		add_settings_error(
		            'custom_endpoint',
		            'users_list_options_custom_endpoint',
		            __( 'Endpoint is required', 'users-list' ),
		            'error'
		        );
    		}    	
    	}

    	if (!wp_http_validate_url($input['users_api_url'])) {
        	$valid = false;
        	add_settings_error(
	            'users_api_url',
	            'users_list_options_users_api_url',
	            __( 'Users API URL is invalid', 'users-list' ),
	            'error'
	        );
        } 

    	if (!$this->is_slug($input['custom_endpoint'])) {
        	$valid = false;
        	add_settings_error(
	            'custom_endpoint',
	            'users_list_options_custom_endpoint',
	            __( 'Endpoint is invalid', 'users-list' ),
	            'error'
	        );
        }     

        // Ignore the user's changes and use the old database value.
	    if ( ! $valid ) {
	        $input = get_option( 'users_list_options' );
	    }

	    // Delete Cached Data
	    delete_transient( 'ul_users' );
		delete_transient( 'ul_users_info' );

        return $input;
    }

    /**
     * Check input option is slug
     *
     * @param string $str Contains input option value
     * @since  1.0.0
     */
    function is_slug( $str ) {

    	return preg_match('/^[a-z0-9]+(-?[a-z0-9]+)*$/i', $str);

    }

}
