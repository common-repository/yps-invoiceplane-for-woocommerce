<?php

namespace YPS\Framework\Form\v346_950_484;

class Button_Form_Field extends Form_Field {

    const BUTTON_TYPE_PRIMARY              = "primary";
    const BUTTON_TYPE_SECONDARY            = "secondary";
    const BUTTON_TYPE_SUCCESS              = "success";
    const BUTTON_TYPE_DANGER               = "danger";
    const BUTTON_TYPE_WARNING              = "warning";
    const BUTTON_TYPE_INFO                 = "info";
    const BUTTON_TYPE_LIGHT                = "light";
    const BUTTON_TYPE_DARK                 = "dark";
    const BUTTON_TYPE_LINK                 = "link";

    protected $button_text          = "Button";
    protected $button_type          = self::BUTTON_TYPE_PRIMARY;
    protected $button_icon          = "";
    
    public function __construct($context, $params = array()) {

        $this->set_type("button");

        parent::__construct($context, $params);
    }


    /**
     * Get the value of button_text
     */ 
    public function get_button_text()
    {
        return $this->button_text;
    }

    /**
     * Set the value of button_text
     *
     * @return \YPS\Framework\Form\v346_950_484\Button_Form_Field
     */ 
    public function set_button_text($button_text)
    {
        $this->button_text = $button_text;

        return $this;
    }

    /**
     * Get the value of button_type
     */ 
    public function get_button_type()
    {
        return $this->button_type;
    }

    /**
     * Set the value of button_type
     *
     * @return  self
     */ 
    public function set_button_type($button_type)
    {
        $this->button_type = $button_type;

        return $this;
    }

    /**
     * Get the value of button_icon
     */ 
    public function get_button_icon()
    {
        return $this->button_icon;
    }

    /**
     * Set the value of button_icon
     *
     * @return  self
     */ 
    public function set_button_icon($button_icon)
    {
        $this->button_icon = $button_icon;

        return $this;
    }
}
