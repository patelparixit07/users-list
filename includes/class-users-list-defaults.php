<?php

/**
 *
 * @since      1.0.0
 * @package    Users_List
 * @subpackage Users_List/includes
 * @author     Parixit Patel
 */

class Users_List_Defaults
{
    
    /**
     *
     * @since    1.0.0
     * @access   protected
     * @var      array    $instances   Users_List_Defaults's instance
     */
    private static $instances = [];

    /**
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $users_api_url   default users list api
     */
    protected $users_api_url = 'https://jsonplaceholder.typicode.com/users';

    /**
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $custom_endpoint   default endpoint
     */
    protected $custom_endpoint = 'users';

    /**
     *
     * @since    1.0.0
     */
    protected function __construct() { }

    /**
     * Static method that controls the access to the singleton
     * instance
     *
     * @since    1.0.0
     * @access   private
     */
    public static function getInstance(): Users_List_Defaults
    {
        $cls = static::class;
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new static();
        }

        return self::$instances[$cls];
    }

    /**
     *
     * @since     1.0.0
     * @return    array    The default options of the plugin.
     */
    public function getDefaults()
    {
        return array(
            'users_api_url' => $this->users_api_url,
            'custom_endpoint' => $this->custom_endpoint,
        );
    }
}