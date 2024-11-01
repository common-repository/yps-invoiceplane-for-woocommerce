<?php

namespace YPS\Framework\Form\v346_950_484;

class Checkbox_List_Form_Field extends List_Form_Field {


    public function __construct($context) {

        $this->set_type("checkbox-list");

        parent::__construct($context);
    }
    
}
