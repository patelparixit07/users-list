<?php
/**
 * Class Ajax_Handling_Test
 *
 * @package Users_List
 */

class Ajax_Handling_Test extends WP_UnitTestCase {

    /**
     *
     * @access   protected
     * @var      Users_List    $plugin
     */
    protected $plugin;

    /**
     *
     * @access   protected
     * @var      Users_List_Public    $plugin_public
     */
    protected $plugin_public;

    public function __construct() {
        parent::__construct();

        $this->plugin = new Users_List();
        $this->plugin_public = new Users_List_Public( $this->plugin->get_plugin_name(), $this->plugin->get_version() );

    }

    public function setUp() {
        parent::setUp();

        add_filter( 'wp_doing_ajax', '__return_true' );
    }
        
    public function tearDown() {
        parent::tearDown();

        remove_filter( 'wp_doing_ajax', '__return_true' );
        remove_filter( 'wp_die_ajax_handler', 'wp_ajax_halt_handler_filter' );
        remove_filter( 'wp_die_ajax_handler', 'wp_ajax_print_handler_filter' );
        unset( $_GET['id'] );
        unset( $_GET['nonce'] );

    }

    public function test_invalid_nonce_fail() {
        
        add_filter( 'wp_die_ajax_handler', 'wp_ajax_halt_handler_filter' );

        try {
            
            $this->plugin_public->load_user_info();
            $caught_exception = 'No exception caught';

        } catch ( \Exception $e ) {
            $caught_exception = $e->getMessage();
        }

        $this->assertEquals( 'bad_nonce', $caught_exception );
    }

}
