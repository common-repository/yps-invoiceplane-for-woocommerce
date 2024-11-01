<?php

namespace YPS\Framework\Record\v346_950_484;

use YPS\Framework\Database\v346_950_484\Session_Model;

class Record_Session_Model extends Session_Model {
    
    public $record_form;
    public $record_dataset_query;
    
    public function __construct($entity_namespace, $params = array()) {

        parent::__construct($entity_namespace, $params);
    }
    
    public function set_record_form($record_form){
        $this->record_form              = $record_form;
    }
    
    public function set_record_dataset_query($record_dataset_query){
        $this->record_dataset_query     = $record_dataset_query;
    }

    public function create_table() {

        $fields         = $this->record_form->get_fields();
        
        $sql_fields     = array();
        $sql_keys       = array();
        
        foreach($fields as $field){
            
            if(isset($field['primary_key'])){
                if($field['primary_key'] == true){
                    $sql_keys[]     = "`{$field['name']}`";
                }
            }
            
            if(isset($field['sql_column'])){
                $sql_fields[]   = "`{$field['name']}` {$field['sql_column']}";
            }

        }

        $sql_implode_fields         = implode(",", $sql_fields);
        $sql_implode_keys           = implode(",", $sql_keys);
        
        $sql = "CREATE TEMPORARY TABLE {$this->get_tmp_table_name()} (
                {$sql_implode_fields}, 
                PRIMARY KEY ({$sql_implode_keys})
                ){$this->get_charset_collate()};";
                
        //Wordpress_Helper::write_log($sql);
        
        $this->db_delta($sql);

    }
}