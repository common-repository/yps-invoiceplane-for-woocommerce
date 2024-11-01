<?php

namespace YPS\Framework\Form\v346_950_484;

class Time_Form_Field extends DateTime_Form_Field {

    public function __construct($context) {

        parent::__construct($context);

        $this->set_type("time");

        $this->set_date_picker(false);
        $this->set_time_picker(true);

        $this->set_format("H:i:s");

        $this->add_attribute("class", array("yps-time-form-field"));

        $this->update_picker_attributes();
    }

    public function get_framework_view_path(){
		return "form/field/datetime.php";
	}
    
}
