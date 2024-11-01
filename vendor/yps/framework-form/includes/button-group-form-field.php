<?php

namespace YPS\Framework\Form\v346_950_484;

class Button_Group_Form_Field extends Form_Field {

    protected $buttons      = array();

    public function __construct($context, $params = array()) {

        $this->set_type("button-group");

        parent::__construct($context, $params);
    }

    public function add_button($button){
        $this->buttons[]        = $button;

        return $this;
    }

    public function get_buttons(){
        return $this->buttons;
    }

}
