<?php

namespace YPS\Framework\Record\v346_950_484;

use YPS\Framework\Database\v346_950_484\Model;

class Record_Model extends Model {

    /**
     * @var \YPS\Framework\Record\v346_950_484\Record_Form $record_form
     */
    public $record_form;

    public function __construct($context, $entity_namespace, $params = array()) {

        if(empty($this->record_form)){
            throw new \Exception("Form must be set for 'Record_Model', please use the function 'set_record_form'");
        }

        parent::__construct($context, $entity_namespace, $params);
    }
    
    public function set_record_form($record_form){
        $this->record_form     = $record_form;
    }
    
    /**
     * Create/Alter table using SQL
     */
    public function create_table() {

        $fields         = $this->record_form->get_fields();

        $sql_fields     = array();
        $sql_keys       = array();
        
        $sql_index_keys = array();
        
        $sql            = "";

        foreach($fields as $field){

            if($field->get_is_primary_key() == true){
                $sql_keys[]     = "`{$field->get_name()}`";
            }

            if($field->get_is_index_key() == true){
                $sql_index_keys[]   = "KEY `{$field->get_name()}` (`{$field->get_name()}`)";
            }
            
            
            if($field->get_sql_column()){
                $sql_fields[]   = "`{$field->get_name()}` {$field->get_sql_column()}";
            }

        }

        $sql_implode_fields             = implode(",\n", $sql_fields);
        $sql_implode_keys               = implode(",\n", $sql_keys);

        $sql_implode_index_keys         = implode(",\n", $sql_index_keys);
        $sql_implode_custom_index_keys  = implode(",\n", $this->get_custom_create_table_index_keys());


        $sql .= "CREATE TABLE {$this->table} (\n";
        $sql .= "{$sql_implode_fields}, \n";
        $sql .= "PRIMARY KEY ({$sql_implode_keys})\n";

        if(count($sql_index_keys) != 0){
            $sql .= ", {$sql_implode_index_keys}\n";
        }

        if(count($this->get_custom_create_table_index_keys()) != 0){
            $sql .= ", {$sql_implode_custom_index_keys}\n";
        }

        $sql .= "){$this->get_charset_collate()};";

        //Wordpress_Helper::write_log($sql);
        
        $this->db_delta($sql);

    }

    /**
     * @return array
     */
    public function get_custom_create_table_index_keys(){
        return array();
    }

    public function save($entity, $update_columns = array()){
    
        $fields         = $this->record_form->get_fields();
        
        foreach($fields as $field){
            if($field->get_is_to_be_saved() == false){
                $entity->remove($field->get_name());
            }
        }
        
        return parent::save($entity, $update_columns);
    }
}
