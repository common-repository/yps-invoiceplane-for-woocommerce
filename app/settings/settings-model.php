<?php

namespace YPS\WC_Invoice_Plane;

use YPS\WC_Invoice_Plane\Framework\Settings\Settings_Model as Framework_Settings_Model;

class Settings_Model extends Framework_Settings_Model {
    
    public function __construct($context, $params = array()) {
        
        $this->set_entity_class("\\YPS\\WC_Invoice_Plane\\Settings_Entity");
        $this->set_table_name("yps_wc_invoice_plane_settings");
        
        parent::__construct($context, $params);
    }

    public function get_ip_payment_method($wc_payment_method){
        return $this->get_value("payment_method_{$wc_payment_method}");
    }
    

}