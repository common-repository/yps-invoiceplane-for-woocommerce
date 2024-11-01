<?php

namespace YPS\Framework\Settings\v346_950_484;

use YPS\Framework\Core\v346_950_484\Helper;
use YPS\Framework\Core\v346_950_484\Config;

class Settings_Config extends Config {

    public function __construct($context, $params = array()) {
        parent::__construct($context, $params);
        
        if(empty($this->controller_name)){
            throw new \Exception("No controller name has been set");
        }

    }
        
    public function get_settings_url($raw = false, $message = null){
        return Helper::get_admin_url($this->context->get_plugin_code(), array(
            'controller'    => $this->controller_name, 
            'action'        => 'settings',
            'message'       => $message,
            'raw'           => $raw
        ));
    }
    
}