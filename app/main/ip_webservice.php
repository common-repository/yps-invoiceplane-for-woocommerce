<?php

namespace YPS\WC_Invoice_Plane;

use YPS\WC_Invoice_Plane\Framework\Invoice_Plane\Webservice;

class IP_Webservice extends Webservice {

    public function __construct($context, $params = array()){
        parent::__construct($context, $params);

        $settings       = new Settings_Model($context, $params);

        $base_url       = $settings->get_value('url');
        $username       = $settings->get_value('username');
        $password       = $settings->get_value('password');

        $this->set_webservice_data($base_url, $username, $password);
    }

}
    