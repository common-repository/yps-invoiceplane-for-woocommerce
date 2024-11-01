<?php

namespace YPS\Framework\Form\v346_950_484;

class DateTime_Form_Field extends Form_Field {

    protected $format;
    
    protected $time_picker;
    protected $date_picker;

    public function __construct($context) {

        parent::__construct($context);

        $this->set_type("datetime");

        $this->set_date_picker(true);
        $this->set_time_picker(true);

        $this->set_format("Y-m-d H:i:s");

        $this->add_attribute("class", array("yps-datetime-form-field"));

        $this->update_picker_attributes();
    }
    
    public function update_picker_attributes(){
        $this->add_attribute("data-date-picker", array($this->get_date_picker()), true);
        $this->add_attribute("data-time-picker", array($this->get_time_picker()), true);
    }

	/**
	 * Get the value of format
	 *
	 * @return mixed
	 */
	public function get_format(){
		return $this->format;
	}

	/**
	 * Set the value of format
	 *
	 * @param   mixed  $format  
	 *
	 * @return  self
	 */
	public function set_format($format){
		$this->format = $format;

        $this->add_attribute("data-format", array($format), true);

		return $this;
	}


	/**
	 * Get the value of time_picker
	 *
	 * @return mixed
	 */
	public function get_time_picker(){
		return $this->time_picker;
	}

	/**
	 * Set the value of time_picker
	 *
	 * @param   mixed  $time_picker  
	 *
	 * @return  self
	 */
	public function set_time_picker($time_picker){
		$this->time_picker = $time_picker;

		return $this;
	}

	/**
	 * Get the value of date_picker
	 *
	 * @return mixed
	 */
	public function get_date_picker(){
		return $this->date_picker;
	}

	/**
	 * Set the value of date_picker
	 *
	 * @param   mixed  $date_picker  
	 *
	 * @return  self
	 */
	public function set_date_picker($date_picker){
		$this->date_picker = $date_picker;

		return $this;
	}

}
