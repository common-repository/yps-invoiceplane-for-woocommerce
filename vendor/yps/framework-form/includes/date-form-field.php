<?php

namespace YPS\Framework\Form\v346_950_484;

class Date_Form_Field extends DateTime_Form_Field {

    public function __construct($context) {

        parent::__construct($context);

        $this->set_type("date");

        $this->set_date_picker(true);
        $this->set_time_picker(false);

        $this->set_format("Y-m-d");

        $this->add_attribute("class", array("yps-date-form-field"));

        $this->update_picker_attributes();
    }

    public function get_framework_view_path(){
		return "form/field/datetime.php";
	}
    
}
