<?php

namespace YPS\Framework\Form\v346_950_484;

class WP_Media_Form_Field extends Form_Field {

    public function __construct($context) {

        $this->set_type("wp-media");

        parent::__construct($context);
    }
}
