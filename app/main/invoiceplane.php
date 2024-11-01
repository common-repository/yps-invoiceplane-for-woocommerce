<?php

namespace YPS\WC_Invoice_Plane;

use YPS\WC_Invoice_Plane\Framework\Wordpress\Wordpress_Helper;
use YPS\WC_Invoice_Plane\Framework\Plugin\Plugin;
use YPS\WC_Invoice_Plane\Framework\Core\Helper;
use YPS\WC_Invoice_Plane\Framework\File_System\File_System_Helper;

class Invoice_Plane extends Plugin {

    public function init(){    

        parent::init();

        new WooCommerce_Helper($this->context);
        
        add_action('admin_menu', array( $this, 'admin_menu'), 10);
    }

    public function admin_menu(){

        if(current_user_can('administrator')){
            add_submenu_page(
                "yourplugins",
                "InvoicePlane Settings",
                "InvoicePlane Settings",
                'read',
                $this->context->get_plugin_code(),
                array($this, 'submenu_callback')
            );   
        }

    }
    
    public function submenu_callback() {
        (new Settings_Controller($this->context, $this->params))->request_display();
    }
    
    /**
     * Loading styles and scripts only on wp-admin pages.
     *
     * @param string $hook_suffix
     * @return void
     */
    function admin_enqueue_scripts($hook_suffix){
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
    
    public function get_custom_upgrade_models(){
        return array(
            'YPS\WC_Invoice_Plane\Settings_Model',
        );
    }
   
}
