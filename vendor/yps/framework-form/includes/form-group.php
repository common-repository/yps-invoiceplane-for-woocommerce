<?php

namespace YPS\Framework\Form\v346_950_484;

use YPS\Framework\Core\v346_950_484\Base;

class Form_Group extends Base {

    const TYPE_FIELD        = "field";
    const TYPE_CONTROLLER   = "controller";

    protected $name;
    protected $label;
    protected $type;
    protected $wrapper_classes;

    protected $display_if_field_name        = null;
    protected $display_if_field_values      = null;

    /**
     * Get the value of name
     *
     * @return mixed
     */
    public function get_name(){
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @param   mixed  $name  
     *
     * @return  self
     */
    public function set_name($name){
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of label
     *
     * @return mixed
     */
    public function get_label(){
        return $this->label;
    }

    /**
     * Set the value of label
     *
     * @param   mixed  $label  
     *
     * @return  self
     */
    public function set_label($label){
        $this->label = $label;

        return $this;
    }

    /**
     * Get the value of wrapper_classes
     *
     * @return mixed
     */
    public function get_wrapper_classes(){
        return $this->wrapper_classes;
    }

    /**
     * Set the value of wrapper_classes
     *
     * @param   mixed  $wrapper_classes  
     *
     * @return  self
     */
    public function set_wrapper_classes($wrapper_classes){
        $this->wrapper_classes = $wrapper_classes;

        return $this;
    }

    /**
     * Get the value of type
     *
     * @return mixed
     */
    public function get_type(){
        return $this->type;
    }

    /**
     * Set the value of type
     *
     * @param   mixed  $type  
     *
     * @return  self
     */
    public function set_type($type){
        $this->type = $type;

        return $this;
    }
    
    public function get_display_if_field_name(){
        return $this->display_if_field_name;
    }

    public function get_display_if_field_values(){
        return $this->display_if_field_values;
    }
    
	/**
	 * Set the value of display_if
	 *
	 * @param   string  $field_name
     * @param   array   $field_values  
	 *
	 * @return  self
	 */
	public function set_display_if($field_name, $field_values){

		$this->display_if_field_name    = $field_name;
        $this->display_if_field_values  = $field_values;

		return $this;
	}

    public function get_fields(){
        return array();
    }
}
