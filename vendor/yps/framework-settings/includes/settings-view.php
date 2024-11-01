<?php

namespace YPS\Framework\Settings\v346_950_484;

use YPS\Framework\Form\v346_950_484\Form_View;

class Settings_Form_View extends Form_View {

    public function __construct($context, $params = array()){        
        parent::__construct($context, $params);
    }
    
    public function get_messages(){

        if(empty($this->get_form_entity_name())){
            $this->set_form_entity_name("Settings");
        }

        return parent::get_messages();
    }

}