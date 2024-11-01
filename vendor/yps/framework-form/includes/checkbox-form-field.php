<?php

namespace YPS\Framework\Form\v346_950_484;

class Checkbox_Form_Field extends Form_Field {

    protected $checked;
    
    public function __construct($context) {

        $this->set_type("checkbox");

        parent::__construct($context);
    }


    

	/**
	 * Get the value of checked
	 *
	 * @return mixed
	 */
	public function get_checked(){
		return $this->checked;
	}

	/**
	 * Set the value of checked
	 *
	 * @param   mixed  $checked  
	 *
	 * @return  self
	 */
	public function set_checked($checked){
		$this->checked = $checked;

		return $this;
	}
}
