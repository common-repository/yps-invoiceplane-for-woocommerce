<?php
/*
 * Questo file è soggetto alla licenza presente nel file "LICENSE.txt"
 */

namespace YPS\Framework\Settings\v346_950_484;

use YPS\Framework\Database\v346_950_484\Model;

class Settings_Model extends Model {

    public function create_table(){

        $charsetCollate         = $this->get_charset_collate();

        $sql = "CREATE TABLE {$this->table} (
                `s_key` VARCHAR(100) NOT NULL,
                `s_value` MEDIUMTEXT NOT NULL,
                PRIMARY KEY (`s_key`)
                ){$charsetCollate};";

        $this->db_delta($sql);
    }

    /**
     * Get all the settings from DB
     * 
     * @return Entity
     */
    public function get_settings_entity(){
        return $this->array_to_entity($this->get_values());
    }

    /**
     * Return all setting values
     *
     * @return array
     */
    public function get_values(){
        $ret    = array();

        $rows   = $this->get_query_rows("SELECT * FROM {$this->table}");

        if(empty($rows) || count($rows) == 0){
            $rows       = array();
        }

        foreach($rows as $row){
            $ret[$row->s_key]     = $row->s_value;
        }

        return $ret;
    }

    /**
     * Return a single setting value
     *
     * @param string $key
     * @param mixed $default_value
     * 
     * @return string | null
     */
    public function get_value($key, $default_value = null){

        $ret    = $this->get_query_row("SELECT * FROM {$this->table} WHERE s_key = :key", array(
            'key'   => $key,
        ));

        if(empty($ret)){
            return null;
        }

        if(empty($ret->s_value)){
            return $default_value;
        }

        return $ret->s_value;
    }

    /**
     * Save a value in the settings table
     *
     * @param string $key
     * @param string $value
     * @return string
     */
    public function set_value($key, $value){
        $record = array(
            's_key'         => $key,
            's_value'       => $value,
        );

        if($this->is_value($key) == false){
            return $this->insert($this->table, $record);
        }else{
            $this->update($this->table, $record, array(
                    's_key' => $key
            ));
        }
    }

    /**
     * Check to see if a key exists
     *
     * @param string $key
     * @return bool
     */
    public function is_value($key){
        $rows           = $this->get_query_rows(
                "SELECT * FROM {$this->table} WHERE s_key = :s_key", array(
                            's_key'     => $key,
                ));

        if(count($rows) == 0 || empty($rows)){
            return false;
        }

        return true;
    }

    /**
     * Set a value only if that key doesn't exists
     *
     * @param string $key
     * @return bool
     */
    public function set_value_if_not_exists($key, $value){
        if($this->is_value($key) == false){
            $this->set_value($key, $value);
        }
    }

    public function get_form_data($form){
        $fields     = $form->get_fields();
        $data       = array();

        foreach($fields as $field_name => $field){
            $data[$field_name]      = $this->get_value($field_name);

            if(empty($data[$field_name])){
                $data[$field_name]  = $field->get_default_value();
            }
        }
        
        return $data;
    }

    /**
     * Save a record to database
     * 
     * @param \YPS\Framework\Core\v346_950_484\Entity $entity Entity to save
     * @param array $update_columns The data to save
     * 
     * @return int|null If inserted/updated the record ID is returned. Otherwise "null"
     */
    public function save($entity, $update_columns = array()){

        do_action("yps_save", $entity, $update_columns);

        $entity_data   = $entity->get_data(true);

        if(count($update_columns) != 0){
            $data       = array_intersect_key($entity_data, $update_columns);
        }else{
            $data       = $entity_data;
        }

        foreach($data as $key => $value){
            $this->set_value($key, $value);
        }
        
        return null;
    }
}
