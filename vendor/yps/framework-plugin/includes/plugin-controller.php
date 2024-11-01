<?php

namespace YPS\Framework\Plugin\v346_950_484;

use YPS\Framework\Core\v346_950_484\Controller;

class Plugin_Controller extends Controller {
    
    public function __construct($context, $params = array()){

        $this->set_controller_page("yourplugins");
        $this->set_always_execute_index_action(true);
        
        parent::__construct($context, $params);
    }

    public function index_action(){

    }
}

