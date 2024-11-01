<?php

namespace YPS\Framework\Database\v346_950_484;

class Session_Model extends Model {

    public $tmp_table_model;

    public function __construct($context, $entity_class) {
        $this->entity_class     = $entity_class;

        $this->tmp_table_model  = new Model($context, $entity_class);
        $this->tmp_table_model->set_table_name($this->get_tmp_table_name());
    }

    public function truncate(){}
    public function create_list_table($list_name){}

    public function get_tmp_table_name(){
        return session_id();
    }

    public function session_to_tmp_table(){
        $session_rows      = $_SESSION[$this->get_table_name()];

        $this->create_table();

        foreach($session_rows as $session_row){
            $this->tmp_table_model->insert($this->get_tmp_table_name(), $session_row);
        }
    }

    public function tmp_table_to_session(){

        $db_rows                                            = $this->tmp_table_model->get_entities();
        $_SESSION[$this->get_table_name()]                  = array();

        foreach($db_rows as $db_row){
            $_SESSION[$this->get_table_name()][]           = $db_row;
        }
    }

    public function dataset_to_session($query, $params = array()){
        $rows       = $this->query($query, $params);

        $this->rows_to_session($rows);
    }

    public function rows_to_session($rows){
        foreach($rows as $row){
            $_SESSION[$this->get_table_name()][]        = $row;
        }
    }

    /**
     * Get the row by id from database
     * 
     * @param type $id Record Id, "id" column
     * @return Entity
     */
    public function get_entity($id){
        $rows      = $_SESSION[$this->get_table_name()];


        foreach($rows as $row){
            if($row['id'] == $id){
                return $this->array_to_entity($row);
            }
        }

        return null;
    }

    /**
     * Get all rows from database table, use only if you know there are only a few rows
     * 
     * @return Entity
     */
    public function get_entities(){
        return $this->array_to_entities($_SESSION[$this->get_table_name()]);
    }

    public function get_entity_by_column($column_name, $column_value){

        $rows      = $_SESSION[$this->get_table_name()];

        foreach($rows as $row){
            if($row[$column_name] == $column_value){
                return $this->array_to_entity($row);
            }
        }

        return null;
    }

    public function get_entities_by_columns($columns, $operator = 'AND'){
        $this->session_to_tmp_table();

        return $this->tmp_table_model->get_entities_by_columns($columns, $operator);
    }

    /**
     * Delete a entity by "id" column
     * 
     * @param integer $id Record ID
     */
    public function delete_entity($entity, $id_field = 'id'){
        $rows      = $_SESSION[$this->get_table_name()];

        foreach($rows as &$row){
            if($row[$id_field] == $entity->get($id_field)){
                unset($row);
            }
        }
    }

    /**
     * Delete a row by "id" column
     * 
     * @param integer $id Record ID
     */
    public function delete_row($id, $id_field = 'id'){
        $rows      = $_SESSION[$this->get_table_name()];

        foreach($rows as &$row){
            if($row[$id_field] == $id){
                unset($row);
            }
        }
    }

    /**
     * Delete a row by "id". It set the column 'deleted' to '1'
     * 
     * @param type $id Record ID
     * @param type $deleteField Default is 'deleted', but you can change the name
     */
    public function delete_entity_virtually($entity, $delete_field = 'deleted', $id_field = 'id'){

        do_action("yps_delete_entity_virtually", $entity);

        $rows      = $_SESSION[$this->get_table_name()];

        foreach($rows as &$row){
            if($row[$id_field] == $entity->get($id_field)){
                $row[$delete_field] = 1;
            }
        }

    }

    /**
     * Delete a row by "id". It set the column 'deleted' to '1'
     * 
     * @param type $id Record ID
     * @param type $deleteField Default is 'deleted', but you can change the name
     */
    public function delete_row_virtually($id, $delete_field = 'deleted', $id_field = 'id'){
        $rows      = $_SESSION[$this->get_table_name()];

        foreach($rows as &$row){
            if($row[$id_field] == $id){
                $row[$delete_field]     = 1;
            }
        }
    }

    /**
     * Count records by "column_name" and "value". Both can be empty
     * 
     * @param type $column_name Column name to search in
     * @param type $value Value to search
     * @return int The resultant count
     */
    public function get_count($column_name = null, $value = null){

        $this->session_to_tmp_table();

        return $this->tmp_table_model->get_count($column_name, $value);
    }

    public function array_to_entities($rows){
        $entities   = array();

        foreach($rows as $key => $row){
            $entity     = new $this->entity_class;
            $entity->set_data($row, true);

            $entities[$key] = $entity;
        }

        return $entities;
    }

    public function array_to_entity($row){
        $entity     = new $this->entity_class;
        $entity->set_data($row, true);

        return $entity;
    }

    /**
     * Insert data to the database.
     *
     * @param string $table_name the table where we want to insert the data.
     * @param array $record the record columns to be filled with data.
     * @return string | false
     */
    public function insert($table_name, $record = array()){

        $last_interset_id           = max(array_column($record, 'id'));

        $table_name                 = $this->replace_table_prefix($table_name);
        $_SESSION[$table_name][]    = $record;

        return $last_interset_id;
    }

    /**
     * Update records from the database.
     *
     * @param string $table_name the table we want to update.
     * @param array $record the record columns to be updated.
     * @param array $where the clause in base the data will be modified.
     * @return void
     */
    public function update($table_name, $record = array(), $where = array()){

        $table_name                 = $this->replace_table_prefix($table_name);

        foreach($_SESSION[$table_name] as &$row){

            $found      = true;
            foreach($where as $key => $value){
                if($row[$key] != $value){
                    $found  = false;
                }
            }

            if($found == true){
                $row        = $record;
            }
        }

    }
}


