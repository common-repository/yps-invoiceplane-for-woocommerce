<?php

namespace YPS\Framework\Plugin\v346_950_484;

use YPS\Framework\Core\v346_950_484\Application;
use YPS\Framework\Core\v346_950_484\Plugin_Helper;
use YPS\WC_Conditional_Cart_Notices\Framework\Core\Helper;

class Plugin extends Application {
    
    protected $plugin_helper;

    public function __construct($context, $params = array())
    {
        parent::__construct($context, $params);

        $this->plugin_helper        = new Plugin_Helper($context, $params);
    }

    public function init(){
        parent::init();

        add_shortcode('yps-plugin-header', array($this, 'plugin_header'));
        add_shortcode('yps-plugin-footer', array($this, 'plugin_footer'));

        add_action('network_admin_menu', array($this, 'plugin_admin_menu'), 9);
        add_action('admin_menu', array($this, 'plugin_admin_menu'), 9);
        add_action('admin_menu', array($this, 'plugin_sub_admin_menu'), 1000);

    }

    public function plugin_admin_menu(){

        global $admin_page_hooks;

        if(!isset($admin_page_hooks['yourplugins'])){
            if(current_user_can('administrator')) {
                add_menu_page( 
                    'YourPlugins', 
                    'YourPlugins', 
                    'read', 
                    "yourplugins",
                    array($this, 'plugin_submenu_callback'),
                    $this->context->get_framework_url("Plugin", "images/logo-24x24.png"),
                    100
                );
            }
        }

    }

    public function plugin_sub_admin_menu(){

    }

    public function plugin_submenu_callback() {
        (new Plugin_Controller($this->context, $this->params))->request_display();
    }

    function is_current_hook_suffix($hook_suffix){
        if($hook_suffix == "yourplugins_page_{$this->context->get_plugin_code()}"){
            return true;
        }
        
        return false;
    }
    
    /**
     * Loading styles and scripts only on wp-admin pages.
     *
     * @param string $hook_suffix
     * @return void
     */
    function admin_enqueue_scripts($hook_suffix){

        $this->context->enqueue_framework_style("Plugin", "plugin-admin");

        parent::admin_enqueue_scripts($hook_suffix);
    }

    
    /**
     * Loading styles and scripts only on wp front-end.
     *
     * @param string $hook_suffix
     * @return void
     */
    function front_enqueue_scripts($hook_suffix){
        parent::front_enqueue_scripts($hook_suffix);
    }
    
    public function plugin_header(){
        echo $this->get_framework_view("Plugin", "header.php");
    }

    public function plugin_footer(){
        echo $this->get_framework_view("Plugin", "footer.php");
    }

    public static function get_yps_plugin_list($only_active = false){

        if (!function_exists('get_plugins')){
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        $plugins        = \get_plugins();
        $yps_plugins    = array();

        foreach($plugins as $plugin){
            if($plugin['Author'] == 'yourplugins.com'){

                if($only_active == true && is_plugin_active("{$plugin['TextDomain']}/{$plugin['TextDomain']}.php") == false){
                    continue;
                }else{
                    $yps_plugins[]      = $plugin;
                }
                
            }
        }

        return $yps_plugins;
    }

}


