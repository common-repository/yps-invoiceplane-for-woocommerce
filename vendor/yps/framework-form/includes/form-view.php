<?php

namespace YPS\Framework\Form\v346_950_484;

use YPS\Framework\Core\v346_950_484\Helper;
use YPS\Framework\Core\v346_950_484\View;

class Form_View extends View {
    
    protected $form_entity_name;
    protected $form_title;
    protected $form_description;

    protected $form;
    
    protected $messages                 = array();

    public function __construct($context, $params = array()){        
        parent::__construct($context, $params);
    }
    
    public function get_messages(){

        if(empty($this->get_form_entity_name())){
            $this->set_form_entity_name("Form");
        }

        if(empty($this->get_form_message("success"))){
            $this->set_form_message("success", sprintf(__('%s has been saved correctly!', 'yps-framework-form'), $this->get_form_entity_name()));
        }

        return $this->messages;
    }

    public function get_form_message($message_key){

        if(!isset($this->messages[$message_key])){
            return null;
        }

        return $this->messages[$message_key];
    }

    public function set_form_message($message_key, $message_content){
        $this->messages[$message_key]      = $message_content;

        return $this;
    }

    public function get_form_title(){
        return $this->form_title;
    }

    public function set_form_title($form_title){
        $this->form_title = $form_title;

        return $this;
    }

    public function get_form_description(){
        return $this->form_description;
    }

    public function set_form_description($form_description){
        $this->form_description = $form_description;

        return $this;
    }

    public function get_toolbar_view(){
        return $this->get_framework_view('Form', 'form/partial/toolbar.php');
    }

    public function get_form_title_view(){
        return $this->get_framework_view('Form', 'form/partial/form-title.php');
    }

    public function get_form_footer_view(){
        
    }

	/**
	 * Get the value of form_entity_name
	 *
	 * @return mixed
	 */
	public function get_form_entity_name(){
		return $this->form_entity_name;
	}

	/**
	 * Set the value of form_entity_name
	 *
	 * @param   mixed  $form_entity_name  
	 *
	 * @return  self
	 */
	public function set_form_entity_name($form_entity_name){
		$this->form_entity_name = $form_entity_name;

		return $this;
	}

	/**
	 * Get the value of form
	 *
	 * @return mixed
	 */
	public function get_form(){
		return $this->form;
	}

	/**
	 * Set the value of form
	 *
	 * @param   mixed  $form  
	 *
	 * @return  self
	 */
	public function set_form($form){
		$this->form = $form;

		return $this;
	}
}