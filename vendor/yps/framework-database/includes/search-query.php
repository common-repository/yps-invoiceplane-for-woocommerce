<?php

namespace YPS\Framework\Database\v346_950_484;

use YPS\Framework\Core\v346_950_484\Base;

class Search_Query extends Base{

    protected $table;
    
    private $search_conditions      = array();
    private $start                  = 0;
    private $limit                  = 10;
    private $columns                = array();
    private $ret_entities           = true;
    private $order_column           = null;
    private $order_dir              = null;
    
    public function get_search_conditions(){
        return $this->search_conditions;
    }
    
    public function set_search_conditions($search_conditions = array()){
        $this->search_conditions        = $search_conditions;
    }
    
    public function get_start(){
        return $this->start;
    }
    
    public function set_start($start){
        $this->start        = $start;
    }
    
    public function get_limit(){
        return $this->limit;
    }
    
    public function set_limit($limit){
        $this->limit    = $limit;
    }
    
    public function get_columns(){
        return $this->columns;
    }
    
    public function set_columns($columns){
        $this->columns      = $columns;
    }
    
    public function get_return_entities(){
        return $this->ret_entities;
    }
    
    public function set_return_entities($bool){
        $this->ret_entities     = $bool;
    }
    
    public function get_order_column(){
        return $this->order_column;
    }
    
    public function set_order_column($order_column){
        $this->order_column     = $order_column;
    }
    
    public function get_order_dir(){
        return $this->order_dir;
    }
    
    public function set_order_dir($order_dir){
        $this->order_dir    = $order_dir;
    }
    

    public function get_join(){
        return "";
    }
    
    public function get_where(){
        return "";
    }
    
    public function get_having(){
        return "";
    }
    
    public function get_group_by(){
        return "";
    }
    
    public function get_table_alias(){
        return $this->table;
    }
    
    public function get_default_order_by_column_name(){
        return "";
    }
    
    public function get_select_columns(){
        return "";
    }
    
    public function get_params(){
        return array();
    }
}



