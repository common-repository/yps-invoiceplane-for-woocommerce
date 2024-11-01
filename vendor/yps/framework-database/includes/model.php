<?php

namespace YPS\Framework\Database\v346_950_484;

use YPS\Framework\Wordpress\v346_950_484\Wordpress_Helper;

class Model extends Database_Helper{

    var $table;
    protected $entity_class;
    
    public $id_column           = 'id';
    
    protected $context;
    protected $params;

    protected $save_auto_mode   = false;

    public function __construct($context, $params = array()) {
        $this->context           = $context;
        $this->params            = $params;
    }

    public function create_table(){

    }
    
    public function truncate(){
        $this->query("TRUNCATE TABLE {$this->table}");
    }
    
    public function create_list_table($list_name) {

        $sql = "CREATE TABLE {$this->get_list_items_table_name($list_name)} (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `label` varchar(255) DEFAULT NULL,
                PRIMARY KEY (`id`)
                ){$this->get_charset_collate()};";

        $this->db_delta($sql);

    }
    
    public function set_id_column($id_column){
        $this->id_column        = $id_column;
    }
    
    public function current_table_exists(){
        parent::table_exists($this->table);
    }

    /**
     * Get the value of entity_class
     *
     * @return mixed
     */
    public function get_entity_class(){
        return $this->entity_class;
    }

    /**
     * Set the value of entity_class
     *
     * @param   mixed  $entity_class  
     *
     * @return  self
     */
    public function set_entity_class($entity_class){
        $this->entity_class = $entity_class;

        return $this;
    }

    /**
     * Get the row by id from database
     * 
     * @param type $id Record Id, "id" column
     * @return Entity
     */
    public function get_entity($id){

        $row        = $this->get_query_row("SELECT * FROM {$this->table} WHERE {$this->id_column} = :id", array(
                        'id' => $id 
        ), ARRAY_A);

        return $this->array_to_entity($row);
    }

    /**
     * Get all rows from database table, use only if you know there are only a few rows
     * 
     * @return Entity
     */
    public function get_entities(){
        $rows   = $this->get_query_rows("SELECT * FROM {$this->table}", array(), ARRAY_A);
        
        return $this->array_to_entities($rows);
    }

    public function get_entity_by_column($column_name, $column_value){
        return $this->array_to_entity($this->get_row_by_column($column_name, $column_value));
    }
    
    public function get_entity_by_columns($columns, $operator = 'AND'){
        foreach($this->get_entities_by_columns($columns, $operator) as $entity){
            return $entity;
        }

        return null;
    }

    public function get_entities_by_columns($columns, $operator = 'AND'){
        return $this->array_to_entities($this->get_rows_by_columns($columns, $operator));
    }
    
    public function get_rows_by_columns($columns, $operator = 'AND'){
        $column_query       = array();
        $column_tokens      = array();
        $operator           = " {$operator} ";

        foreach($columns as $column_name => $column_value){
            $column_query[]                 = "{$column_name} = :{$column_name}";
            $column_tokens[$column_name]    = $column_value;
        }

        return $this->get_query_rows("SELECT * FROM {$this->table} "
        . "WHERE " . implode($operator, $column_query), $column_tokens, ARRAY_A);
    }

    public function update_column($entity, $column, $value, $id_field = 'id'){
        $this->query("UPDATE {$this->table} SET {$column} = :value WHERE {$id_field} = :id", array(
            'id'            => $entity->get('id'),
            'value'         => $value
        ));
    }

    /**
     * Delete a entity by "id" column
     * 
     * @param integer $id Record ID
     */
    public function delete_entity($entity, $id_field = 'id'){
        $this->delete($this->table, array($this->id_column => $entity->get($id_field)));
    }
    
    /**
     * Delete a row by "id" column
     * 
     * @param integer $id Record ID
     */
    public function delete_row($id, $id_field = 'id'){
        $this->delete($this->table, array($this->id_column => $id));
    }
    
    /**
     * Delete a row by "id". It set the column 'deleted' to '1'
     * 
     * @param type $id Record ID
     * @param type $deleteField Default is 'deleted', but you can change the name
     */
    public function delete_entity_virtually($entity, $delete_field = 'deleted', $id_field = 'id'){

        do_action("yps_delete_entity_virtually", $entity);

        $this->update($this->table, array($delete_field => '1'), array($this->id_column => $entity->get($this->id_column)));
    }
    
    /**
     * Delete a row by "id". It set the column 'deleted' to '1'
     * 
     * @param type $id Record ID
     * @param type $deleteField Default is 'deleted', but you can change the name
     */
    public function delete_row_virtually($id, $delete_field = 'deleted', $id_field = 'id'){
        $this->update($this->table, array($delete_field => '1'), array($this->id_column => $id));
    }
    
    /**
     * Count records by "column_name" and "value". Both can be empty
     * 
     * @param type $column_name Column name to search in
     * @param type $value Value to search
     * @return int The resultant count
     */
    public function get_count($column_name = null, $value = null){

        $where          = "";
        $query_args     = array();

        if(!empty($column_name) && !empty($value)){
            $where      = "WHERE {$column_name} = :value";
            $query_args["value"]            = $value;
        }

        $count          = $this->get_query_row("SELECT COUNT(*) AS count FROM {$this->table} {$where}", $query_args)->count;
        
        return intval($count);
    }

    /**
     * Set the current table name (Without Wordpress Table Prefix)
     * 
     * @param string $table_name
     */
    public function set_table_name($table_name){
        $this->table    = "[prefix]{$table_name}";
    }
    
    /**
     * Set the current table name for Multisite Master DB (Without Wordpress Table Prefix)
     * 
     * @param string $table_name
     */
    public function set_main_table_name($table_name){
        $this->table    = "[main-prefix]{$table_name}";
    }
    
    public function get_save_auto_mode(){
        return $this->save_auto_mode;
    }

    /**
     * The function "save()" will detect automatically if the record must be inserted or updated
     * 
     * @param bool $save_auto_mode To enable auto_mode set true
     * @return YPS\Framework\Database\v346_950_484\Database_Helper
     */
    public function set_save_auto_mode($save_auto_mode){
        $this->save_auto_mode       = $save_auto_mode;

        return $this;
    }


    /**
     * Save a record to database.
     * Using set_save_auto_mode, it will detect automatically if the record must be inserted or updated
     *
     * @param \YPS\Framework\Core\v346_950_484\Entity $entity Entity to save
     * @param array $update_columns The data to save
     * 
     * @return int|null If inserted/updated the record ID is returned. Otherwise "null"
     */
    public function save($entity, $update_columns = array()){

        do_action("yps_save", $entity, $update_columns);

        $id           = $entity->get($this->id_column);
        $entityData   = $entity->get_data(true);

        if($this->get_save_auto_mode() === true){
            $auto_mode_entity   = $this->get_entity($id);

            if(empty($auto_mode_entity->get($this->id_column))){
                $id     = null;
            }else{
                $id     = $auto_mode_entity->get($this->id_column);
            }
        }

        if(count($update_columns) != 0){
            $data       = array_intersect_key($entityData, $update_columns);
        }else{
            $data       = $entityData;
        }

        if(empty($id)){
            return $this->insert($this->table, $data);
        }else{
            $this->update($this->table, $data, array(
                $this->id_column => $id
            ));

            return $id;
        }

        return null;
    }

    public function array_to_entities($rows){
        $entities   = array();

        foreach($rows as $key => $row){
            $entity     = new $this->entity_class($this->context, $this->params);
            $entity->set_data($row, true);

            $entities[$key] = $entity;
        }

        return $entities;
    }
    
    public function entities_to_array($entities){
        $rows       = array();
        
        foreach($entities as $entity){
            $rows[]     = $entity->get_data();
        }
        
        return $rows;
    }
    
    public function array_to_entity($row){
        $entity     = new $this->entity_class($this->context, $this->params);
        
        $entity->set_data($row, true);

        return $entity;
    }
    
    public function get_list_items_table_name($list_name){
        return "{$this->table}_list_{$list_name}";
    }
    
    public function get_list_items($list_name){
        $list_items_table_name      = $this->get_list_items_table_name($list_name);
        
        return $this->get_query_rows("SELECT * FROM {$list_items_table_name}");
    }

    public function get_list_item_label($list_name, $id){
        $list_items_table_name      = $this->get_list_items_table_name($list_name);

        return $this->get_query_row(
                "SELECT * FROM {$list_items_table_name}
                WHERE id = :id", array(
                    'id'        => $id
        ))->label;
    }
    
    public function get_table_name(){
        return $this->replace_table_prefix($this->table);
    }
    
    public function get_row_by_column($column_name, $column_value){
        return $this->get_query_row("SELECT * FROM {$this->table} WHERE {$column_name} = :column_value", array(
            'column_value'   => $column_value,
        ), ARRAY_A);
    }

    /**
     * Return a key => value array by specifying a "key" column and a "value" column
     * 
     * @param string $key_column
     * @param string $value_column
     */
    public function get_key_value_array($key_column, $value_column){

        $data       = array();

        foreach($this->get_entities() as $entity){
            $data[$entity->get($key_column)]        = $entity->get($value_column);
        }

        return $data;
    }

    public function insert_row($row = array()){
        return $this->insert($this->table, $row);
    }

    public function update_row($row = array(), $where = array()){
        return $this->update($this->table, $row, $where);
    }

    public function get_group_by_column($column){
        $values                     = array();
        $rows                       = $this->get_query_rows("SELECT $column AS group_by_field FROM {$this->table} GROUP BY {$column}");

        foreach($rows as $row){
            $values[]               = $row->group_by_field;
        }

        return $values;
    }

    public function search($query, $calculate_num_rows = false, &$num_rows = null, $return_query = false){
        global $wpdb;
        
        $search_conditions  = $query->get_search_conditions();
        $limit              = $query->get_limit();
        $start              = $query->get_start();
        $columns            = $query->get_columns();
        $ret_entities       = $query->get_return_entities();
        $order_column       = $query->get_order_column();
        $order_dir          = $query->get_order_dir();
        $having             = $query->get_having();
        $alias_name         = $query->get_table_alias();
        $group_by           = $query->get_group_by();
        $select_columns     = $query->get_select_columns();
        $join               = $query->get_join();
        $where              = $query->get_where();

        $search_params      = $query->get_params();
        $search_queries     = array();
        $search_query       = "";
        $columns_query      = "";

        
        foreach($search_conditions as $field_name => $options){
            if(!empty($options['value']) || $options['value'] === 0){

                //{$alias_name}
                $column_name                     = (isset($options['name']))?$options['name']:"{$field_name}";
                
                if(strtolower($options['condition']) == "like"){
                    $options['value']           = "%{$options['value']}%";
                }else if(strtolower($options['condition']) == "in"){
                    $options['value']           = "(" . Database_Helper::escape_in($options['value']) . ")";
                }

                if(strtolower($options['condition']) == "in"){
                    $search_queries[]                = "{$column_name} {$options['condition']} {$options['value']}";
                }else{
                    $search_params[$field_name]      = $options['value'];
                    $search_queries[]                = "{$column_name} {$options['condition']} :{$field_name}";
                }
            }
        }

        $search_params      = array_merge($search_params, array(
            'limit'     => ($limit === null)?null:intval($limit), 
            'start'     => ($start === null)?null:intval($start)
        ));

        if(count($search_queries) != 0){
            $search_query       = " AND " . implode(" AND ", $search_queries);
        }

        if(count($columns) == 0){
            
            if(empty($alias_name)){
                $columns_query      = "*";
            }else{
                $columns_query      = "{$alias_name}.*";
            }
            
        }else{
            $columns_query      = implode(", ", $columns);
        }

        if(empty($order_column)){
            $order_by       = $query->get_default_order_by_column_name();
        }else{
            $order_by       = $order_column;
        }

        if(empty($order_by)){
            $order_by       = "id";
        }
        
        if(empty($order_dir)){
            $order_by       .= " asc";
        }else{
            $order_by       .= " {$order_dir}";
        }

        if(!empty($select_columns)){
            $select_columns     .= ", " . $columns_query;
        }else{
            $select_columns     = $columns_query;
        }

        if(!empty($group_by)){
            $group_by_query     = "GROUP BY {$group_by}";
        }else{
            $group_by_query     = "";
        }
        
        if(!empty($where)){
            $where              = "WHERE {$where} ";
        }
        
        if($calculate_num_rows == true){
            $calc_found_rows    = " SQL_CALC_FOUND_ROWS ";
        }else{
            $calc_found_rows    = "";
        }
        
        if(empty($limit)){
            $limit_query        = "";
        }else{
            $limit_query        = "LIMIT :limit";
        }
        
        if(empty($start)){
            $start_query        = "";
        }else{
            $start_query        = "OFFSET :start";
        }
        
        if(empty($having)){
            $having_query       = "HAVING 1=1 {$search_query}";
        }else{
            $having_query       = "HAVING {$having} {$search_query}";
        }
        
        if(empty($alias_name)){
            $from_query         = "FROM {$this->table}";
        }else{
            $from_query         = "FROM {$this->table} AS {$alias_name}";
        }
            
        
        $final_query        = "SELECT {$calc_found_rows}  
            {$select_columns} 
            {$from_query} 
            {$join} 
            {$where}
            {$group_by_query} 
            {$having_query} 
            ORDER BY " . sanitize_sql_orderby($order_by) . "  
            {$limit_query} 
            {$start_query}
            ";
            
            Wordpress_Helper::write_log($this->context, $final_query);

        if($return_query == true){
            return $this->prepare_query($final_query, $search_params);
        }
        
        $rows               = $this->get_query_rows($final_query, $search_params, ARRAY_A, true);

        if($calculate_num_rows == true){
            $num_rows       = $this->get_query_row("SELECT FOUND_ROWS() AS 'rows'")->rows;
        }
        
        if($ret_entities == false){
            return $rows;
        }

        return $this->array_to_entities($rows);
    }
    
    public static function array_to_in_string($arr){
        $new_arr        = implode("','", $arr);

        return "'{$new_arr}'";
    }
}

