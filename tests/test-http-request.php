<?php
/**
 * Class HTTP_Request_Test
 *
 * @package Users_List
 */

class HTTP_Request_Test extends WP_UnitTestCase {

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

    /**
     *
     * @access   protected
     * @var      Users_List_Defaults    $plugin_defaults
     */
    protected $plugin_defaults;

    public function __construct() {
        parent::__construct();

        $this->plugin = new Users_List();
        $this->plugin_public = new Users_List_Public( $this->plugin->get_plugin_name(), $this->plugin->get_version() );
        $this->plugin_defaults = Users_List_Defaults::getInstance();

    }

    public function test_request_correct_users_url() {

        add_filter( 'pre_http_request', 'pre_http_request_halt_request', 1, 3 );
        
        $defaults = $this->plugin_defaults->getDefaults();

        update_option( 'users_list_options', $defaults );

        
        try {

            $ul_data = $this->plugin_public->fetch_from_exterenal_api();

        } catch ( Exception $e ) {
            $ul_data = json_decode( $e->getMessage(), true );
        }

        $this->assertNotEmpty( $ul_data );
        $this->assertEquals( 'https://jsonplaceholder.typicode.com/users', $ul_data['url'] );
        $this->assertEquals( 'GET', $ul_data['method'] );
    }

    public function test_request_correct_user_info_url() {
        
        add_filter( 'pre_http_request', 'pre_http_request_halt_request', 1, 3 );

        $defaults = $this->plugin_defaults->getDefaults();

        update_option( 'users_list_options', $defaults );

        
        try {
            
            $user_id = 1;
            $ul_data = $this->plugin_public->fetch_from_exterenal_api( $user_id );

        } catch ( Exception $e ) {
            $ul_data = json_decode( $e->getMessage(), true );
        }

        $this->assertNotEmpty( $ul_data );
        $this->assertEquals( 'https://jsonplaceholder.typicode.com/users/1', $ul_data['url'] );
        $this->assertEquals( 'GET', $ul_data['method'] );
    }

    public function tearDown() {
        parent::tearDown();

        delete_option( 'users_list_options' );
        remove_filter( 'pre_http_request', 'pre_http_request_halt_request', 1 );
    }

}
