<?php

namespace YPS\Framework\Record\v346_950_484;

use YPS\Framework\Core\v346_950_484\Base;
use YPS\Framework\Core\v346_950_484\Helper;
use YPS\Framework\Core\v346_950_484\Config;

use YPS\Framework\Wordpress\v346_950_484\Wordpress_Helper;

class Record_Config extends Config {
    
    public function __construct($context, $params = array()){
        parent::__construct($context, $params);

        if(empty($this->controller_name)){
            throw new \Exception("No controller name has been set");
        }

    }
    
    public function get_list_url($raw = false){
        return Helper::get_admin_url($this->get_controller_page(), array(
            'controller'    => $this->controller_name, 
            'raw'           => $raw
        ));
    }
    
    public function get_list_ajax_url(){
        return Helper::get_ajax_url($this->context->get_plugin_code(), array(
            "controller"    => $this->controller_name,
            "action_name"   => "list-ajax",
            "http_params"   => Helper::get_request(),
        ));
    }
    
    public function get_edit_url($id = null, $raw = false, $message = null){
        return Helper::get_admin_url($this->get_controller_page(), array(
            'controller'    => $this->controller_name, 
            'action'        => 'edit',
            'id'            => $id,
            'message'       => $message,
            'raw'           => $raw
        ));
    }
    
    public function get_delete_url($id = null, $raw = false){
        return Helper::get_admin_url($this->get_controller_page(), array(
            'controller'    => $this->controller_name, 
            'action'        => 'delete',
            'id'            => $id,
            'raw'           => $raw
        ));
    }
        
}