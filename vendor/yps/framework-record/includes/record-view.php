<?php

namespace YPS\Framework\Record\v346_950_484;

use YPS\Framework\Core\v346_950_484\Helper;
use YPS\Framework\Core\v346_950_484\View;

class Record_View extends View {
    
    protected $record_singular_name;
    
    public function __construct($context, $params = array()){
        parent::__construct($context, $params);
    }
    
    public function get_edit_title(){

        if($this->get_parent_controller()->get_is_clone() == true){
            return "Clone of {$this->record_singular_name} (ID: {$this->record_id})";
        }

        if(empty($this->record_id)){
            return sprintf(__("New %s", 'yps-framework-record'), $this->record_singular_name);
        }

        return "{$this->record_singular_name} (ID: {$this->record_id})";
    }

    public function get_edit_description(){
        return "";
    }

    public function get_toolbar_view(){
        return $this->get_framework_view('Record', 'record/toolbar.php');
    }

    public function get_edit_header_view(){
        return $this->get_framework_view('Record', 'record/edit-header.php');
    }

    public function get_edit_footer_view(){
        
    }
}