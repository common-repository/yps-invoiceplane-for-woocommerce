<?php

namespace YPS\Framework\Form\v346_950_484;

class Numeric_Form_Field extends Form_Field {

    protected $decimals             = 2;
    protected $decimal_separator    = ".";
    
    public function __construct($context, $params = array()) {

        $this->set_type("numeric");

        $this->add_attribute('class', array('yps-numeric-form-field'));

        parent::__construct($context, $params);
    }
    

	/**
	 * Get the value of decimals
	 *
	 * @return mixed
	 */
	public function get_decimals(){
		return $this->decimals;
	}

	/**
	 * Set the value of decimals
	 *
	 * @param   mixed  $decimals  
	 *
	 * @return  self
	 */
	public function set_decimals($decimals){
		$this->decimals = $decimals;

		return $this;
	}

	/**
	 * Get the value of decimal_separator
	 *
	 * @return mixed
	 */
	public function get_decimal_separator(){
		return $this->decimal_separator;
	}

	/**
	 * Set the value of decimal_separator
	 *
	 * @param   mixed  $decimal_separator  
	 *
	 * @return  self
	 */
	public function set_decimal_separator($decimal_separator){
		$this->decimal_separator = $decimal_separator;

		return $this;
	}
}
