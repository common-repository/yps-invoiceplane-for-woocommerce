<?php

namespace YPS\Framework\Record\v346_950_484;

use YPS\Framework\Form\v346_950_484\Form_Controller;
use YPS\Framework\Core\v346_950_484\Controller;
use YPS\Framework\Core\v346_950_484\Helper;
use YPS\Framework\Core\v346_950_484\View;
use YPS\Framework\Database\v346_950_484\Search_Query;

use YPS\Framework\Wordpress\v346_950_484\Wordpress_Helper;

class Record_Controller extends Form_Controller {

    protected $show_actions_new_button    = true;
    
    protected $record_singular_name;
    protected $record_plural_name;
    
    protected $record_table;
    
    protected $messages                 = array();
    
    protected $list_title;
    protected $list_show_card           = true;

    protected $edit_url                 = null;

    protected $clone                    = false;
    protected $raw                      = false;

    public function __construct($context, $params = array()) {        
        parent::__construct($context, $params);
    }
    
    public function get_form_url($message = null, $raw = false) {
        return $this->get_form_config()->get_edit_url($this->get_form_id(), $raw, $message);
    }

    public function index_action(){
        echo $this->get_framework_view('Record', 'record/list.php', array(
            'list_title'        => $this->list_title,
            'list_show_card'    => $this->list_show_card,
            'custom_buttons'    => $this->get_index_custom_buttons(),
        ));
    }
    
    public function get_list_after_buttons_view(){
        return null;
    }

    public function get_list_header_view(){
        return $this->get_framework_view('Record', 'record/list-header.php');
    }



    public function get_list_footer_view(){

    }
    
    public function get_list_title(){
        return $this->record_plural_name;
    }

    public function get_list_description(){
        return "";
    }
    
    public function get_index_custom_buttons(){
        return "";
    }
        
    public function get_edit_view_json_data($json_data){
        return $this->get_form_view_json_data($json_data);
    }

    public function get_edit_view_params($params){
        return $this->get_form_view_params($params);
    }
    
    public function edit_action(){
        return $this->form();
    }

    public function delete_action(){
        $this->get_record_model()->delete_row($this->form_id);
        
        $this->custom_after_delete();

        if($this->raw == true){

            /* Returns JSON response */
            $this->set_response(array(
                'status'        => true,
                'record_id'     => $this->form_id
            ), Controller::RESPONSE_JSON);

        }else{
            /* Redirect via HTML */
            $this->redirect($this->get_form_config()->get_list_url());
        }
        
    }
    
    /**
     * @deprecated Use "custom_after_delete" instead
     */
    public function after_delete(){
        throw new \Exception("Use 'custom_after_delete' instead");
        
    }
    
    /**
     * @deprecated Use "custom_after_delete" instead
     */
    public function edit_after_save(){
        throw new \Exception("Use 'custom_after_edit_save' instead");
    }
        
    public function list_ajax_action(){
        
        $search_query   = $this->get_list_ajax_search_query();
        
        $start          = Helper::get_request("start");
        $length         = Helper::get_request("length");
        $search_value   = Helper::get_request("search");
        $order          = Helper::get_request("order");
        
        $header         = $this->record_table->get_header();
        $header_keys    = array_keys($header);
        
        if(!empty($order)){
            $order_column   = $header_keys[$order[0]['column']];
            $order_dir      = $order[0]['dir'];
        }

        $search_query->set_search_conditions($this->get_list_ajax_search_conditions($search_value));
        
        if(empty($length)){
            $length     = 10;
        }
        
        $search_query->set_start($start);
        $search_query->set_limit($length);
        $search_query->set_order_column($order_column);
        $search_query->set_order_dir($order_dir);

        $rows           = $this->get_record_model()->search($search_query, true, $records_total);
        
        die(json_encode($this->record_table->get_table_data($rows, $records_total)));
    }
    
    public function set_edit_message($message_key, $message_content){
        $this->set_form_message($message_key, $message_content);
    }
    
    public function get_list_ajax_search_query(){
        return new Search_Query($this->context, $this->params);
    }
    
    public function get_list_ajax_search_conditions($search_value){
        return array();
    }
    
    public function get_record_config(){
        return $this->get_form_config();
    }

    public function set_record_config($record_config){
        $this->set_form_config($record_config);

        return $this;
    }

    public function get_record_entity(){
        return $this->get_form_entity();
    }

    public function set_record_entity($record_entity){
        $this->set_form_entity($record_entity);

        return $this;
    }
    
    public function get_record_table(){
        return $this->record_table;
    }

    public function set_record_table($record_table){
        $this->record_table         = $record_table;

        return $this;
    }
    
    public function get_record_model(){
        return $this->get_form_model();
    }

    public function set_record_model($record_model){
        $this->set_form_model($record_model);

        return $this;
    }
    
    public function get_record_form(){
        return $this->get_form();
    }

    public function set_record_form($record_form){
        $this->set_form($record_form);

        return $this;
    }
    
    public function set_list_title($list_title){
        $this->list_title           = $list_title;

        return $this;
    }
    
    public function set_record_singular_name($record_singular_name){
        $this->record_singular_name     = $record_singular_name;
    }
    
    public function set_record_plural_name($record_plural_name){
        $this->record_plural_name     = $record_plural_name;
    }

    /**
     * Executed after the 'delete_action'
     */
    public function custom_after_delete(){}

    /**
     * Executed after the record has been saved in edit view (edit_action)
     */
    public function custom_after_edit_save(){
        $this->custom_after_form_save();
    }
    
    /**
     * Get the value of edit_url
     */ 
    public function get_edit_url()
    {
        if($this->edit_url === null){
            return $this->get_form_config()->get_edit_url();
        }
        
        return $this->edit_url;
    }

    /**
     * Set the value of edit_url
     *
     * @return  self
     */ 
    public function set_edit_url($edit_url)
    {
        $this->edit_url = $edit_url;

        return $this;
    }

    /**
     * Get the value of clone
     */ 
    public function get_is_clone()
    {
        return $this->clone;
    }

    /**
     * Set the value of clone
     *
     * @return  self
     */ 
    public function set_is_clone($is_clone)
    {
        $this->clone = $is_clone;

        return $this;
    }
}
