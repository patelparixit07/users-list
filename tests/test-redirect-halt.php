<?php
/**
 * Class Redirect_Halt_Test
 *
 * @package Users_List
 */

class Redirect_Halt_Test extends WP_UnitTestCase {

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

        set_query_var( 'ul_page_id', 'ul-list' );
    }
        
    public function tearDown() {
        parent::tearDown();

    }

    public function test_rewrite_url_redirect_correctly() {        

        $new_template = plugin_dir_path( dirname(__FILE__) ) . 'public/partials/users-list-public-display.php';

        try {

            $updated_template = $this->plugin_public->add_template('dummy/template.php');
           
        } catch ( Exception $e ) {
            $updated_template = json_decode( $e->getMessage(), true );
        }

        $this->assertNotEmpty( $updated_template );
        $this->assertEquals( $updated_template, $new_template );
        
    }

     public function test_set_page_title() {

        try {
            
            $page_title = $this->plugin_public->set_page_title( ['title' => ''] );

           
        } catch ( Exception $e ) {
            $updated_template = json_decode( $e->getMessage(), true );
        }

        $this->assertNotEmpty( $page_title );
        $this->assertEquals( $page_title['title'], __( 'Users List', 'users-list' ) );

    }

}
