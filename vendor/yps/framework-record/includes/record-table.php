<?php

namespace YPS\Framework\Record\v346_950_484;

use YPS\Framework\Core\v346_950_484\Base;

use YPS\Framework\Core\v346_950_484\Controller;
use YPS\Framework\Core\v346_950_484\Helper;
use YPS\Spreadsheet_Calculator\Framework\Core\View;

class Record_Table extends Base {

    public $id_column                   = 'id';
    
    public $show_actions_column         = false;
    public $show_actions_delete_button  = false;
    public $show_actions_edit_button    = false;
    
    public $action_column_label         = null;
    public $action_column_style         = '';

    private $header;

    private $start;
    private $length;
    private $search_value;

    /**
     * @var \YPS\Framework\Record\v346_950_484\Record_Form
     */
    public $record_form;
	public $record_config;

    public $table_wrapper_class     = "";
    public $table_width             = "100%";
    
    public $table_class             = "";
    
    public $filters_selector        = "";
    
    public $default_order_dir       = "desc";
    public $default_order_by        = 0;
    
	public function __construct($context, $params = array()) {
        
        $this->start                = Helper::get_request("start");
        $this->length               = Helper::get_request("length");
        $this->search_value         = Helper::get_request("search");

        $this->action_column_label  = __('Actions', 'yps-framework-core');

        parent::__construct($context, $params);
    }
           
    public function set_record_config($record_config){
        $this->record_config    = $record_config;
    }
    
    public function set_record_form($record_form){
        $this->record_form      = $record_form;
    }
    
    public function set_id_column($id_column){
        $this->id_column        = $id_column;
    }

    public function get_actions($actions, $row, $header_key){
        return $actions;
    }
    
    public function get_field_table_property($field_name, $property){
        throw new Exception("Function is deprecated");
    }
    
    public function get_header_item_data($header_key, $header_item_data){
        return $header_item_data;
    }

    public function get_header(){
        
        foreach($this->record_form->get_fields() as $field_name => $field){
            if($field->get_is_table_header() == true){
                $this->header[$field_name]      = $this->get_header_item_data($field_name, array(
                    'label'         => $field->get_label(),
                    'style'         => $field->get_table_style(),
                    'header_class'  => "{$field_name}_header",
                    'row_class'     => $field_name,
                    'field_name'    => $field_name,
                    'field'         => $field,
                    'display_field' => false,
                ), $this->record_form);
            }
        }

        if($this->show_actions_column == true){
            $this->header['actions']      = array(
                'label'         => $this->action_column_label,
                'style'         => $this->action_column_style,
                'header_class'  => "actions_header",
                'row_class'     => "actions",
            );
        }

        return $this->header;
    }
        
    public function get_table_data($rows, $records_total = null){
        $data_table['data'] = array();
        
        foreach($rows as $row){

            $id                     = $row->get($this->id_column);
            $data_table_row         = array();
            
            foreach($this->header as $header_key => $header_label){
                if($header_key == 'actions' && $this->show_actions_column == true){
                    
                    $actions       = array();

                    if($this->show_actions_edit_button == true){
                        $actions['edit']  =  (new View($this->context))
                                                    ->set_framework_template("Record", "list-actions/edit.php")
                                                    ->set_view_params(array(
                                                        'edit_url' => $this->record_config->get_edit_url($id)
                                                    ))->get_output();
                    }
                    
                    if($this->show_actions_delete_button == true){
                        $actions['delete'] = (new View($this->context))
                                                ->set_framework_template("Record", "list-actions/delete.php")
                                                ->set_view_params(array(
                                                    'delete_url' => $this->record_config->get_delete_url($id)
                                                ))->get_output();
                    }

                    /* Azioni custom */
                    $actions            = $this->get_actions($actions, $row, $header_key);
                    $data_table_row[]   = '<div class="btn-group" role="group" aria-label="">' . implode("", $actions) . '</div>';
                    
                }else{
                    if($this->record_form->get_field_property($header_key, 'is_table_edit_url') == true){
                        $data_table_row[]       = "<a href='{$this->record_config->get_edit_url($id)}'>{$this->get_row_data($row, $header_key)}</a>";
                    }else{
                        $data_table_row[]       = $this->get_row_data($row, $header_key);
                    }
                }
            }
                
            
            $data_table['data'][]   = $data_table_row;
        }
        
        $data_table['recordsFiltered']      = count($data_table['data']);
        $data_table['recordsTotal']         = $records_total;
        $data_table['iTotalDisplayRecords'] = $data_table['recordsTotal'];
        
        return $data_table;
    }

    /**
	 * Get the value of table_class
	 *
	 * @return mixed
	 */
	public function get_table_class(){
		return $this->table_class;
	}

	/**
	 * Set the value of table_class
	 *
	 * @param   mixed  $table_class  
	 *
	 * @return  self
	 */
	public function set_table_class($table_class){
		$this->table_class = $table_class;

		return $this;
	}

	/**
	 * Get the value of table_wrapper_class
	 *
	 * @return mixed
	 */
	public function get_table_wrapper_class(){
		return $this->table_wrapper_class;
	}

	/**
	 * Set the value of table_wrapper_class
	 *
	 * @param   mixed  $table_wrapper_class  
	 *
	 * @return  self
	 */
	public function set_table_wrapper_class($table_wrapper_class){
		$this->table_wrapper_class = $table_wrapper_class;

		return $this;
	}

	/**
	 * Get the value of table_width
	 *
	 * @return mixed
	 */
	public function get_table_width(){
		return $this->table_width;
	}

	/**
	 * Set the value of table_width
	 *
	 * @param   mixed  $table_width  
	 *
	 * @return  self
	 */
	public function set_table_width($table_width){
		$this->table_width = $table_width;

		return $this;
	}

    /**
	 * Get the value of filters_selector
	 *
	 * @return mixed
	 */
	public function get_filters_selector(){
		return $this->filters_selector;
	}

	/**
	 * Set the value of filters_selector
	 *
	 * @param   mixed  $filters_selector  
	 *
	 * @return  self
	 */
	public function set_filters_selector($filters_selector){
		$this->filters_selector = $filters_selector;

		return $this;
	}

    public function get_row_data_text($row, $key){
        return $row->get($key);
    }

    public function get_row_data_form_field($row, $key){
        $controller         = new Controller($this->context, $this->params);

        return $this->header[$key]['field']->get_controller_view($this->get_row_data_form_field_params($row, $key));
    }

    public function get_row_data_form_field_params($row, $key){
        return array();
    }

    public function get_row_data($row, $key){
        if($this->header[$key]['display_field'] == false){
            return $this->get_row_data_text($row, $key);
        }else{
            return $this->get_row_data_form_field($row, $key);
        }

    }


	/**
	 * Get the value of default_order_dir
	 *
	 * @return mixed
	 */
	public function get_default_order_dir(){
		return $this->default_order_dir;
	}

	/**
	 * Set the value of default_order_dir
	 *
	 * @param   mixed  $default_order_dir  
	 *
	 * @return  self
	 */
	public function set_default_order_dir($default_order_dir){
		$this->default_order_dir = $default_order_dir;

		return $this;
	}

	/**
	 * Get the value of default_order_by
	 *
	 * @return mixed
	 */
	public function get_default_order_by(){
		return $this->default_order_by;
	}

	/**
	 * Set the value of default_order_by
	 *
	 * @param   mixed  $default_order_by  
	 *
	 * @return  self
	 */
	public function set_default_order_by($default_order_by){
		$header					= $this->get_header();

		$this->default_order_by = array_search($default_order_by, array_keys($header));

		return $this;
	}
}