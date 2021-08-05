<?php

/**
 *
 * @since      1.0.0
 * @package    Users_List
 * @subpackage Users_List/includes
 * @author     Parixit Patel
 */

class Users_List {

	/**
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Users_List_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		
		if ( defined( 'USERS_LIST_VERSION' ) ) {
			$this->version = USERS_LIST_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'users-list';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Include required files for internationalization, admin hooks and frontend hooks
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-users-list-loader.php';
		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-users-list-i18n.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-users-list-defaults.php';
		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-users-list-admin.php';
		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-users-list-public.php';

		$this->loader = new Users_List_Loader();

	}

	/**
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Users_List_i18n();
		$plugin_i18n->set_domain( $this->get_plugin_name() );

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Users_List_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_options_page' );
        $this->loader->add_action( 'admin_init', $plugin_admin, 'register_setting' );

	}

	/**
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Users_List_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'init', $plugin_public, 'rewrite' );
		$this->loader->add_filter( 'query_vars', $plugin_public, 'query_vars' );
		$this->loader->add_filter( 'document_title_parts', $plugin_public, 'set_page_title' );
		$this->loader->add_action( 'template_include', $plugin_public, 'add_template' );
		$this->loader->add_action( 'wp_ajax_nopriv_load_user_info', $plugin_public, 'load_user_info' );
    	$this->loader->add_action( 'wp_ajax_load_user_info', $plugin_public,  'load_user_info');


	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 *
	 * @since     1.0.0
	 * @return    Users_List_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
