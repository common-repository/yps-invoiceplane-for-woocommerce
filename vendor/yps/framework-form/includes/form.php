<?php

namespace YPS\Framework\Form\v346_950_484;

use YPS\Framework\Core\v346_950_484\Base;
use YPS\Framework\Core\v346_950_484\Helper;

class Form extends Base {

    const ALERT_TYPE_ERROR          = "errors";
    const ALERT_TYPE_WARNING        = "warnings";
    const ALERT_TYPE_NOTICE         = "notices";

    var $fields;
    var $groups;
    
    /** @var bool */
    protected $is_ajax_form;

    protected $id;
    
    protected $helper;

    protected $alerts                = array(
        'warnings'      => array(),
        'notices'       => array(),
        'errors'        => array(),
    );

    public function __construct($context, $params = array()){

        $this->helper       = new Helper($context, $params);

        parent::__construct($context, $params);
    }

    /**
     * Adds a Form_Field to the Form
     * 
     * @param \YPS\Framework\Core\v346_950_484\Form_Field $form_field
     * 
     */
    public function add_field($form_field){
        $field_name                         = $form_field->get_name();

        /* If label is missing, I will try to create a valid one */
        if(!empty($form_field->get_label())){
            $form_field->set_label($this->get_friendly_field_label($form_field->get_label()));
        }

        $this->fields[$field_name]          = $form_field;
    }

    /**
     * Get a friendler label for a Form_Field
     * 
     * @return string
     */
    public function get_friendly_field_label($field_name){
        $label        = ucfirst($field_name);
        $label        = str_replace("_", " ", $label);

        return $label;
    }
    
    public function get_field($field_name){
        return $this->fields[$field_name];
    }

    public function get_property($field_name, $property_name){
        return $this->fields[$field_name][$property_name];
    }
    
    public function get_fields_label(){
        $ret    = array();

        foreach($this->fields as $field){
            $ret[$field->get_name()]    = $field->get_label();
        }

        return $ret;
    }

    /**
     * Get the fields data
     * 
     * @return \YPS\Framework\Core\v346_950_484\Form_Field[]
     */
    public function get_fields(){
        return $this->fields;
    }

    /**
     * Returns a field by its name
     * 
     * @return \YPS\Framework\Core\v346_950_484\Form_Field
     */
    public function get_field_by_name($field_name){

        foreach($this->fields as $field){
            if($field->get_name() == $field_name){
                return $field;
            }
        }

        return null;
    }
    
    /**
     * Set fields data
     * 
     * @return \YPS\Framework\Core\v346_950_484\Form_Field[]
     */
    public function set_fields($fields){
        $this->fields       = $fields;
        
        return $this;
    }

    /**
     * Get request data for Form
     */
    public function get_request_data(){
        $data       = array();

        if(isset($_REQUEST['form_data'])){

            $request            = Helper::get_request("form_data");

            foreach($this->get_fields() as $field_name => $field_label){
                if(isset($request[$field_name])){
                    $value                  = $request[$field_name];
                    $data[$field_name]      = $value;
                }
            }
        }else{
            foreach($this->get_fields() as $field_name => $field_label){
                $value                  = Helper::get_request($field_name);
                $data[$field_name]      = $value;
            }
        }

        
        return $data;
    }

    public function set_field_values_from_request_data(){
        $request_data       = $this->get_request_data();
        $this->set_field_values_from_data($request_data);
    }

    public function set_field_values_from_data($data){
        
        foreach($this->get_fields() as $field_name => $field_value){
            
            if(isset($data[$field_name])){
                $field      = $this->get_field($field_name);
                $field->set_value($data[$field_name]);
            }

        }

    }

    public function set_field_values_from_entity($entity){

        foreach($this->get_fields() as $field_name => $field_value){
                $field      = $this->get_field($field_name);
                $field->set_value($entity->get($field_name));
        }

    }
    
    public function add_alert($alert_type, $alert_name, $message, $field = null){

        $alert_data     = array(
            'message'       => $message
        );

        if($field !== null){
            $alert_data['field']    = $field;
        }

        $this->alerts[$alert_type][$alert_name][]   = $alert_data;
    }

    public function get_alerts(){
        return $this->alerts;
    }

    public function get_alerts_by_type($alert_type = self::ALERT_TYPE_ERROR){
        return $this->alerts[$alert_type];
    }

    public function get_alert($alert_type, $alert_name){
        if(isset($this->alerts[$alert_type][$alert_name])){
            return $this->alerts[$alert_type][$alert_name];
        }

        return null;
    }

    /**
     * If the form doesn't have critical errors, returns true, otherwhise false
     */
    public function is_valid(){
        if($this->has_alerts() === true){
            return false;
        }

        return true;
    }

    public function has_alerts($alert_type = self::ALERT_TYPE_ERROR){

        if(!isset($this->alerts[$alert_type])){
            return false;
        }else{
            if(count($this->alerts[$alert_type]) != 0){
                return true;
            }else{
                return false;
            }
        }

        return false;
    }

    public function validate($data, $optional_parameters = array()){
        $fields     = $this->get_fields();

        foreach($fields as $field_name => $field){

            if($field->get_allow_empty() === false && (!isset($data[$field_name]) || $data[$field_name] === "")){
                $this->add_alert(self::ALERT_TYPE_ERROR, $field_name, __(sprintf("%s can't be empty", $field->get_label_or_name()), 'yps-framework-core'), $field);
            }

            if($field->get_type() == 'email' && Email_Helper::check_email_address($data[$field_name]) == false && $data[$field_name] != ""){
                $this->add_alert(self::ALERT_TYPE_ERROR, $field_name, __(sprintf("%s is not a valid email", $field->get_label_or_name()), 'yps-framework-core'), $field);
            }
        }

        /* Leave for compatibility with old usage */
        return $this->alerts;
    }
    
    /**
     * Add a Form_Group to the Form
     * 
     * @param \YPS\Framework\Core\v346_950_484\Form_Group $form_group
     */
    public function add_group($form_group){
        $form_group_name                    = $form_group->get_name();
        $this->groups[$form_group_name]     = $form_group;

        foreach($form_group->get_fields() as $form_field){
            $this->add_field($form_field);
        }
    }
    
    public function get_group($group_name){
        return $this->groups[$group_name];
    }

    public function get_groups(){
        return $this->groups;
    }
    
    public function get_fields_by_group($group_name){

        $ret_fields        = array();

        foreach($this->get_fields() as $field){
            if($field->get_group_name() == $group_name){
                $ret_fields[$field->get_name()]      = $field;
            }
        }

        return $ret_fields;
    }
    
    public function get_rows_by_group($group_name){

        $rows        = null;

        foreach($this->get_fields() as $field){
            if($field->get_group_name() == $group_name){
                $row        = $field->get_group_row_number();

                if($row > $rows){
                    $rows   = $row;
                }
            }
        }

        return $rows;
    }
    
    public function get_fields_by_row($group_name, $row_number){
        $ret_fields        = array();

        foreach($this->get_fields() as $field){
            if(
                $field->get_group_name() == $group_name &&
                $field->get_group_row_number() == $row_number
                ){
                $ret_fields[$field->get_name()]      = $field;
            }
        }

        return $ret_fields;
    }

    public function get_field_property($field_name, $property_key){
        foreach($this->fields as $field){
            if($field_name == $field->get_name()){
                return $field->__get($property_key);
            }
        }
    }

    public function get_field_attributes($field_name, $attribute_key = 'attributes'){

        foreach($this->fields as $field){
            if($field_name == $field->get_name()){
                $attributes     = $field->__get($attribute_key);
                $ret_attributes = array();
        
                if($attributes === null){
                    return "";
                }
        
                foreach($attributes as $attribute_name => $properties){
                    $implode_properties     = implode(" ", $properties);
                    $ret_attributes[]       = "{$attribute_name} = \"{$implode_properties}\"";
                }
            }

        }


        return implode(" ", $ret_attributes);
    }

    public function get_group_attributes($group_name, $attribute_key = 'group_attributes'){

        $attributes     = $this->get_group_property($group_name, $attribute_key);
        $ret_attributes = array();

        if($attributes === null){
            return "";
        }

        foreach($attributes as $attribute_name => $properties){
            $implode_properties     = implode(" ", $properties);
            $ret_attributes[]       = "{$attribute_name} = \"{$implode_properties}\"";
        }

        return implode(" ", $ret_attributes);
    }

    public function get_group_property($group_name, $property_name){

        $function_name      = "get_{$property_name}";
        return $this->groups[$group_name]->$function_name();
    }

    /**
     * Get the value of is_ajax_form
     *
     * @return bool
     */
    public function get_is_ajax_form(){
        return $this->is_ajax_form;
    }

    /**
     * Set the value of is_ajax_form
     *
     * @param   bool  $is_ajax_form  
     *
     * @return  Record_Form
     */
    public function set_is_ajax_form($is_ajax_form){
        $this->is_ajax_form = $is_ajax_form;

        return $this;
    }

    /**
     * Get the value of id
     */ 
    public function get_id()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function set_id($id)
    {
        $this->id = $id;

        return $this;
    }
}

