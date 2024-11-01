<?php

namespace YPS\Framework\Database\v346_950_484;

use YPS\Framework\Core\v346_950_484\Base;

class Entity extends Base {

    private $data_row;
    
    /**
     * Get current row data
     * 
     * @param boolean $raw_data false = Entity elaboration from "get", true = No elaboration
     * @return array Row data
     */
    public function get_data($raw_data = false){
        $data       = array();
        
        if($raw_data == true){
            return $this->data_row;
        }
        
        foreach($this->data_row as $field_name => $value){
            $data[$field_name]      = $this->get($field_name);
        }
        
        return $data;
    }
    
    public function set_data($data, $raw_data = false){

        if(empty($data)){
            return null;
        }

        if($raw_data == true){
            foreach($data as $key => $value){
                $this->set_raw($key, $value);
            }
        }else{
            foreach($data as $key => $value){
                $this->set($key, $value);
            }
        }

        return $data;
    }
    
    /**
     * Get the raw value of the field from data_row
     * 
     * @param string $field_name Field name to get
     * @return string Field value
     */
    final function get_raw($field_name){
        
        if(!isset($this->data_row[$field_name])){
            return null;
        }
        
        return $this->data_row[$field_name];
    }
    
    /**
     * Set the raw value of the field value to data_row
     * 
     * @param string $field_name
     * @param string $value
     */
    final function set_raw($field_name, $value){
        $this->data_row[$field_name]        = $value;
    }
    
    /**
     * Get the field from data_row
     * 
     * @param string $field_name Field name to get
     * @return string Field value
     */
    public function get($field_name){
        return $this->get_raw($field_name);
    }
    
    /**
     * Set the field value to data_row
     * 
     * @param string $field_name
     * @param string $value
     */
    public function set($field_name, $value){
        $this->set_raw($field_name, $value);
    }
    
    public function set_data_from_form($form){
        foreach($form->get_fields() as $form_field_name => $form_field){
            $this->set($form_field_name, $form_field->get_value());
        }
    }
    
    public function remove($field_name){
        unset($this->data_row[$field_name]);
    }
}


