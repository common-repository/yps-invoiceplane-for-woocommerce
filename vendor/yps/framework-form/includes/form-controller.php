<?php

namespace YPS\Framework\Form\v346_950_484;

use YPS\Framework\Core\v346_950_484\Controller;
use YPS\Framework\Core\v346_950_484\Helper;
use YPS\Framework\Core\v346_950_484\View;

use YPS\Framework\Wordpress\v346_950_484\Wordpress_Helper;

class Form_Controller extends Controller {

    protected $form_config;
    protected $form_model;
    protected $form_entity;
    protected $form;
    protected $form_view;
    protected $form_id;

    protected $raw                      = false;

    public function __construct($context, $params = array()) {        
        parent::__construct($context, $params);
    }
    
    public function custom_load(){

        if(Helper::get_request("raw") == true){
            $this->form_id             = (isset($_REQUEST['form_data']))?intval($_REQUEST['form_data']['id']):null;
        }else{
            $this->form_id             = Helper::get_request("id");
        }

        if(!empty($this->form_id)){
            $this->form_entity                 = $this->get_custom_entity($this->form_model->get_entity($this->form_id));
        }else{
            $this->form_entity                 = $this->get_custom_entity(new $this->form_entity($this->context, $this->params));
        }

        if(empty($this->form_view)){
            $this->form_view                   = new Form_View($this->context, $this->params);
        }
        
        $this->clone                           = Helper::get_request("clone");
        $this->raw                             = Helper::get_request("raw");
    }


    public function get_form_view_json_data($json_data){
        return $json_data;
    }

    public function get_form_view_params($params){
        return $params;
    }
    
    public function get_custom_entity($entity){
        return $entity;
    }

    public function form(){
        
        $this->before_form();

        if(Helper::get_request("form_task") == "save" || Helper::get_request("ajax_action") == "submit"){

            $data               = $this->form->get_request_data();
            $data['id']         = $this->form_id;
            $data               = $this->get_custom_action_data(__FUNCTION__, $data);

            $data               = $this->custom_change_data_on_save($data);

            Wordpress_Helper::write_log($this->context, $data);

            $this->form->validate($data, array(
                'form_id'                 => $this->form_id,
                'form_entity'             => $this->form_entity,
            ));

            $this->form->set_field_values_from_request_data();

            if($this->form->has_alerts(Form::ALERT_TYPE_ERROR) === false){

                $this->form_entity->set_data_from_form($this->form);

                $this->form_id    = $this->form_model->save($this->form_entity);
                
                $this->form_entity->set('id', $this->form_id);

                $this->custom_after_form_save();

                if($this->raw == false){
                    $this->redirect($this->get_form_url("success", $this->raw));
                }
                
            }
        }else if(Helper::get_request("ajax_action") == "reload"){
            $data               = $this->form->get_request_data();
            $data['id']         = $this->form_id;

            $this->form_entity->set_form_data($this->form, $data);
        }else{
            $this->form->set_field_values_from_entity($this->form_entity);
        }
        
        if($this->clone == true){
            $this->form_entity->set('id', null);
        }

        $this->form_view->set_form($this->form);

        $this->form_view->set_framework_template("Form", "form/form.php");

        $this->form_view->set_view_params($this->get_form_view_params(array(
            'entity'					=> $this->form_entity,

            'ajax_url'                  => $this->get_form_url(null, true),
            'current_message'           => Helper::get_request("message"),
            'messages'                  => $this->form_view->get_messages(),
        )));

        $this->form_view->set_view_json_data($this->get_form_view_json_data(array(
            'status'    => ($this->form->has_alerts(Form::ALERT_TYPE_ERROR) === false)?true:false,
            'type'      => 'ajax-form',
            'form_id' => $this->form_id
        )));

        if($this->raw == false){
            $this->form_view->set_output_mode(View::VIEW_OUTPUT_MODE_HTML);
        }else{
            $this->form_view->set_output_mode(View::VIEW_OUTPUT_MODE_HTML_AND_JSON);
        }

        return $this->form_view;
    }
        
    public function before_form(){}

    public function get_custom_action_data($action, $data){
        return $data;
    }
        
    public function get_form_entity(){
        return $this->form_entity;
    }

    public function set_form_entity($form_entity){
        $this->form_entity        = $form_entity;

        return $this;
    }
    
    public function get_form_config(){
        return $this->form_config;
    }

    public function set_form_config($form_config){
        $this->form_config        = $form_config;

        return $this;
    }
    
    public function get_form_model(){
        return $this->form_model;
    }

    public function set_form_model($form_model){
        $this->form_model         = $form_model;

        return $this;
    }
    
    public function get_form(){
        return $this->form;
    }

    public function set_form($form){
        $this->form          = $form;

        return $this;
    }
            
	/**
	 * Get the value of form_view
	 *
	 * @return mixed
	 */
	public function get_form_view(){
		return $this->form_view;
	}

	/**
	 * Set the value of form_view
	 *
	 * @param   mixed  $form_view  
	 *
	 * @return  self
	 */
	public function set_form_view($form_view){
		$this->form_view = $form_view;

		return $this;
	}

    /**
     * Executed after the record has been saved in edit view (edit_action)
     */
    public function custom_after_form_save(){}
    
    /**
     * Change requested data on save
     */
    public function custom_change_data_on_save($data){
        return $data;
    }

        /**
     * Get the value of form_url
     */ 
    public function get_form_url($message = null, $raw = false)
    {
        throw new \Exception("get_form_url must be implemented!");
    }

	/**
	 * Get the value of form_id
	 *
	 * @return mixed
	 */
	public function get_form_id(){
		return $this->form_id;
	}

	/**
	 * Set the value of form_id
	 *
	 * @param   mixed  $form_id  
	 *
	 * @return  self
	 */
	public function set_form_id($form_id){
		$this->form_id = $form_id;

		return $this;
	}

	/**
	 * Get the value of raw
	 *
	 * @return mixed
	 */
	public function get_raw(){
		return $this->raw;
	}

	/**
	 * Set the value of raw
	 *
	 * @param   mixed  $raw  
	 *
	 * @return  self
	 */
	public function set_raw($raw){
		$this->raw = $raw;

		return $this;
	}
}
