<?php

namespace YPS\Framework\Record\v346_950_484;

class Record_Module extends \YPS\Framework\Core\v346_950_484\Module {
    
    public function get_menu_url(){
        return $this->config->get_list_url();
    }
    
}
