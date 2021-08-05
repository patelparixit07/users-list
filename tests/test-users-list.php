<?php
/**
 * Class Users_List_Test
 *
 * @package Users_List
 */

class Users_List_Test extends WP_UnitTestCase {

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
    protected $plugin_admin;

    public function __construct() {
        parent::__construct();

        $this->plugin = new Users_List();
        $this->plugin_admin = new Users_List_Admin( $this->plugin->get_plugin_name(), $this->plugin->get_version() );

    }

	public function setUp() {
        parent::setUp();        
    }
        
    public function tearDown() {
        parent::tearDown();
    }

    public function test_load_dependencies() {

        $plugin_directory = plugin_dir_path( dirname( __FILE__ ) );        
                
        //admin
        $this->assertFileExists($plugin_directory.'admin/class-users-list-admin.php');
        
        //admin/css
        $this->assertFileExists($plugin_directory.'admin/css/users-list-admin.css');
        
        //admin/js
        $this->assertFileExists($plugin_directory.'admin/js/users-list-admin.js');
        
        //admin/partials
        $this->assertFileExists($plugin_directory.'admin/partials/users-list-admin-display.php');

        //public
        $this->assertFileExists($plugin_directory.'public/class-users-list-public.php');
        
        //public/css
        $this->assertFileExists($plugin_directory.'public/css/users-list-public.css');
        $this->assertFileExists($plugin_directory.'public/css/bootstrap.min.css');
        
        //public/js
        $this->assertFileExists($plugin_directory.'public/js/users-list-public.js');

        //public/images
        $this->assertFileExists($plugin_directory.'public/images/loader.gif');
        $this->assertFileExists($plugin_directory.'public/images/user_avtar.png');
        
        //public/partials
        $this->assertFileExists($plugin_directory.'public/partials/users-list-public-display.php');
        $this->assertFileExists($plugin_directory.'public/partials/user-info-public-display.php');
        
        //includes        
        $this->assertFileExists($plugin_directory.'includes/class-users-list-activator.php');
        $this->assertFileExists($plugin_directory.'includes/class-users-list-deactivator.php');
        $this->assertFileExists($plugin_directory.'includes/class-users-list-i18n.php');
        $this->assertFileExists($plugin_directory.'includes/class-users-list-loader.php');
        $this->assertFileExists($plugin_directory.'includes/class-users-list.php');
        
        
        // $this->assertTrue( is_plugin_active( 'users-list/users-list.php'));
               
    }   
    
    public function test_define_admin_hooks() {  

        $this->assertTrue( has_action( 'admin_enqueue_scripts') );
        $this->assertTrue( has_action( 'admin_menu' ) );
        $this->assertTrue( has_action( 'admin_init' ) );
                
    }

    public function test_define_public_hooks() {  
    	
        $this->assertTrue( has_action( 'wp_enqueue_scripts') );
        $this->assertTrue( has_action( 'init' ) );
        $this->assertTrue( has_filter( 'query_vars' ) );
        $this->assertTrue( has_filter( 'document_title_parts' ) );
        $this->assertTrue( has_action( 'template_include') );
        $this->assertTrue( has_action( 'wp_ajax_nopriv_load_user_info') );
        $this->assertTrue( has_action( 'wp_ajax_load_user_info') );
                
    }

    public function test_scripts_registered() {

		$this->plugin_admin->enqueue_scripts();

		$scripts = wp_scripts();
		$script  = $scripts->registered[$this->plugin->get_plugin_name()];

		$this->assertEquals( USERS_LIST_PLUGIN_URL . 'admin/js/users-list-admin.js', $script->src );
		$this->assertContains( 'jquery', $script->deps );
		$this->assertEquals( '1.0.0', $script->ver );
	}

	public function test_styles_registered() {

		$this->plugin_admin->enqueue_styles();

		$styles = wp_styles();
		$style  = $styles->registered[$this->plugin->get_plugin_name()];
		
		$this->assertEquals( USERS_LIST_PLUGIN_URL . 'admin/css/users-list-admin.css', $style->src );
		$this->assertEquals( '1.0.0', $style->ver );
	}

}
