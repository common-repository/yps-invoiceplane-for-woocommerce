<?php

namespace YPS\WC_Invoice_Plane;

use YPS\WC_Invoice_Plane\Framework\Core\Base;

class Invoice_Plane_Helper extends Base {

    public function get_connection_settings_status(){

        $settings_model     = new Settings_Model($this->context, $this->params);

        $url                = $settings_model->get_value('url');
        $username           = $settings_model->get_value('username');
        $password           = $settings_model->get_value('password');

        if(!empty($url) && !empty($username) && !empty($password)){
            return true;
        }

        return false;
    }

    public function get_connection_status($url = null, $username = null, $password = null){

        if(empty($url) || empty($username) || empty($password)){
            return false;
        }

        $ip_webservice      = new IP_Webservice($this->context, $this->params);

        $ip_webservice->set_webservice_data($url, $username, $password);

        $status             = $ip_webservice->check_connection();

        if(isset($status['result']) && $status['result'] == true){
            return true;
        }

        return false;
    }

    public function get_payment_methods_array(){

        $ip_webservice            = new IP_Webservice($this->context, $this->params);
        $ip_payment_methods       = array();

        foreach($ip_webservice->get_payment_methods() as $ip_payment_method){
            $ip_payment_method_id       = $ip_payment_method['payment_method_id'];
            $ip_payment_method_name     = $ip_payment_method['payment_method_name'];

            $ip_payment_methods[$ip_payment_method_id]  = $ip_payment_method_name;
        }

        return $ip_payment_methods;
    }

    public function get_invoice_groups_array(){

        $ip_webservice            = new IP_Webservice($this->context, $this->params);
        $ip_invoice_groups        = array();

        foreach($ip_webservice->get_invoice_groups() as $ip_invoice_group){
            $ip_invoice_group_id        = $ip_invoice_group['invoice_group_id'];
            $ip_invoice_group_name      = $ip_invoice_group['invoice_group_name'];

            $ip_invoice_groups[$ip_invoice_group_id]    = $ip_invoice_group_name;
        }

        return $ip_invoice_groups;
    }

}