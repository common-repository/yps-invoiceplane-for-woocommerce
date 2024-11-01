<?php

namespace YPS\Framework\Settings\v346_950_484;

use YPS\Framework\Form\v346_950_484\Form_Controller;
use YPS\Framework\Settings\v346_950_484\Settings_Form_View;

class Settings_Controller extends Form_Controller {
    
	public function __construct($context, $params = array()){
        parent::__construct($context, $params);
    }

    public function custom_load(){

        if(empty($this->form_view)){
            $this->form_view                   = new Settings_Form_View($this->context, $this->params);
        }
        
        parent::custom_load();
    }

    public function get_form_url($message = null, $raw = false) {
        return $this->get_form_config()->get_settings_url($this->raw, $message);
    }

    public function index_action(){
        return $this->form();
    }

    public function settings_action(){
		return $this->form();
    }


	public function get_custom_entity($entity){
		$entity->set_data($this->form_model->get_values());

		return $entity;
	}

    public function before_form(){

        parent::before_form();

        /* Create rows with default values */
        foreach($this->get_form()->get_fields() as $field_name => $field){
            if($this->get_form_model()->is_value($field_name) === false){
                $default_value  = $field->get_default_value();

                $this->get_form_entity()->set($field_name, $default_value);
            }
        }

    }

}