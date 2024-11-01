<?php

namespace YPS\Framework\Form\v346_950_484;

class WP_Editor_Form_Field extends Form_Field {

    protected $textarea_rows;


    public function __construct($context) {

        $this->set_type("wp-editor");

        parent::__construct($context);
    }
    
	/**
	 * Get the value of textarea_rows
	 *
	 * @return mixed
	 */
	public function get_textarea_rows(){
		return $this->textarea_rows;
	}

	/**
	 * Set the value of textarea_rows
	 *
	 * @param   mixed  $textarea_rows  
	 *
	 * @return  self
	 */
	public function set_textarea_rows($textarea_rows){
		$this->textarea_rows = $textarea_rows;

		return $this;
    }
    
}
