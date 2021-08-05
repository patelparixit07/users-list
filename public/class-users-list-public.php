<?php

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Users_List
 * @subpackage Users_List/public
 * @author     Parixit Patel
 */

class Users_List_Public {

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
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/users-list-public.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'bootstrap', plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css', array() );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/users-list-public.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 'settings', array(
		    'ajaxurl'    => admin_url( 'admin-ajax.php' ),
		    'nonce' => wp_create_nonce('ajax-nonce'),
		    'send_label' => __( 'Users List', 'users_list' )
		) );

	}

	/**
	 * Rewrite rule for Custom Endpoint.
	 *
	 * @since    1.0.0
	 */
	public function rewrite() {

		$options = get_option('users_list_options');

		add_rewrite_rule( '^'.$options['custom_endpoint'].'$', 'index.php?ul_page_id=ul-list', 'top' );

		if(get_transient( 'ul_flush_rewrite' ) || empty(get_transient( 'ul_current_endpoint' )) || get_transient( 'ul_current_endpoint' ) !== $options['custom_endpoint']) {
			
			set_transient( 'ul_current_endpoint', $options['custom_endpoint'], 60*60 );
			delete_transient( 'ul_flush_rewrite' );
			flush_rewrite_rules();

		}

	}

	/**
	 * Append custom query variables
	 *
	 * @param array $vars Default variables
	 * @since    1.0.0
	 */
	public function query_vars( $vars ) {

		$vars[] = 'ul_page_id';
		$vars[] = 'ul_user_info';

	    return $vars;

	}

	/**
	 * Set title for Custom Endpoint
	 *
	 * @param array $titleArr Default title and tag
	 * @since    1.0.0
	 */
	public function set_page_title( $titleArr ) {

		if ( 'ul-list' === get_query_var('ul_page_id') ) {
			$titleArr['title'] = __( 'Users List', 'users-list' );
		}

		return $titleArr;
	}

	/**
	 * Add template when Custom Endpoint browse
	 *
	 * @param string $template Default template
	 * @since    1.0.0
	 */
	public function add_template( $template ) {	

		if( get_query_var( 'ul_page_id', false) !== false && get_query_var( 'ul_page_id') == 'ul-list' ) {

			$new_template = plugin_dir_path( __FILE__ ) . 'partials/users-list-public-display.php';
			if( file_exists( $new_template ) ){
				return $new_template;
			}

		}

		return $template;

	}

	/**
	 * Load users list from external REST API Service
	 *
	 * @since    1.0.0
	 */
	public static function load_users_list() {

		$users_list = get_transient('ul_users');
		if(false === $users_list) {
			$response = self::fetch_from_exterenal_api();

			if ( is_wp_error( $response ) ) {
			    return ['success' => false,'message' => wp_strip_all_tags( $response->get_error_message() ),'data' => []];
			} else {
				try {
					$json = json_decode( $response );
					set_transient('ul_users', $json, 60*60);
				} catch ( Exception $ex ) {
					$json = [];
				}			    
			    return ['success' => true, 'message' => __( 'Users List Fetched!', 'users-list' ), 'data' => $json];
			}
		}

		return ['success' => true, 'message' => __( 'Users List Fetched!', 'users-list' ), 'data' => $users_list];;

	}

	/**
	 * Load specific users information from external REST API Service
	 *
	 * @since    1.0.0
	 */
	public function load_user_info() {

		// Check for nonce security   
		if(!isset($_GET['nonce']))
			throw new \Exception('bad_nonce');

	     if ( ! wp_verify_nonce( $_GET['nonce'], 'ajax-nonce' ) ) {
	         wp_send_json_error( ['success' => false] );
	     }

	    $id = $_GET['id'];

		$users_info = get_transient('ul_users_info');
		
		if(!isset($users_info[$id])) {

			$response = self::fetch_from_exterenal_api($id);

			if ( is_wp_error( $response ) ) {
			    wp_send_json_error( $response );
			} else {
				try {
					$json = json_decode( $response );
					$user_info = $json;
					$users_info[$id] = $user_info;
					set_transient('ul_users_info', $users_info, 60*60);
				} catch ( Exception $ex ) {
					wp_send_json_error( $response );
				}
			}
		}

		set_query_var( 'ul_user_info', $users_info[$id] );
		load_template(plugin_dir_path( __FILE__ ) . '/partials/user-info-public-display.php');
		die;
	}

	/**
	 * Call external REST API Service, Fetch Data and Check for errors
	 *
	 * @param int $id Contains user id @default 0
	 * @since    1.0.0
	 */
	public static function fetch_from_exterenal_api( $id = 0 ) {

		$options = get_option('users_list_options');
		
		$url = ($id == 0) ? esc_url_raw($options['users_api_url']) : esc_url_raw($options['users_api_url'].'/'.$id);

		$response = wp_remote_get($url);
		$responseCode = wp_remote_retrieve_response_code($response);
		$responseMessage = wp_remote_retrieve_response_message( $response );

		if ( 200 != $responseCode && !empty( $responseMessage ) ) {
	        return new WP_Error( $responseCode, $responseMessage );
	    } elseif ( 200 != $responseCode ) {
	        return new WP_Error( $responseCode, __( 'Unknown error occurred', 'users-list' ) );
	    } else {
        	return wp_remote_retrieve_body( $response );
        }

	}

}
