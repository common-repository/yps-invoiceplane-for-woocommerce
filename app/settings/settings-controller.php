<?php

namespace YPS\WC_Invoice_Plane;

use YPS\WC_Invoice_Plane\Framework\Settings\Settings_Controller as Framework_Settings_Controller;

class Settings_Controller extends Framework_Settings_Controller {
    
    public function __construct($context, $params = array()) {

        $this->set_is_default_controller(true);

        $this->set_form_entity(new Settings_Entity($context, $params));
        $this->set_form(new Settings_Form($context, $params));
        $this->set_form_model(new Settings_Model($context, $params));
        $this->set_form_config(new Settings_Config($context, $params));
        
        parent::__construct($context, $params);
    }

}