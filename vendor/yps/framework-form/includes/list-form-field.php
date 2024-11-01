<?php

namespace YPS\Framework\Form\v346_950_484;

class List_Form_Field extends Form_Field {

    	/**
	 * Select options
	 * 
	 * @var array $options key => value
	 */
	protected $options;

	/**
	 * Add an empty option to the "options"
	 * 
	 * @var bool $add_empty_option
	 */
	protected $add_empty_option;
    
    /**
	 * Get $options key => value
	 *
	 * @return array
	 */
	public function get_options(){

		$ret_options			= array();

		if($this->get_add_empty_option() == true){
			$ret_options[]		= __('-- Select --', 'yps-framework-core');
		}

		$ret_options			= $ret_options + $this->options;

		return $ret_options;
	}

	/**
	 * Set $options key => value
	 *
	 * @param   array  $options  $options key => value
	 *
	 * @return  self
	 */
	public function set_options($options){
		$this->options = $options;

		return $this;
	}

	/**
	 * Get $add_empty_option
	 *
	 * @return bool
	 */
	public function get_add_empty_option(){
		return $this->add_empty_option;
	}

	/**
	 * Set $add_empty_option
	 *
	 * @param   bool  $add_empty_button
	 *
	 * @return  self
	 */
	public function set_add_empty_option(bool $add_empty_option){
		$this->add_empty_option = $add_empty_option;

		return $this;
	}
}
