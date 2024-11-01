<?php

namespace YPS\Framework\Invoice_Plane\v346_950_484;

use YPS\Framework\Core\v346_950_484\Base;
use YPS\Framework\Net\v346_950_484\Curl;

class Webservice extends Base{

    protected $base_url;
    protected $username;
    protected $password;
    
    protected $webservice_url;
    protected $curl;

    public function set_webservice_data($base_url, $username, $password){
        $this->base_url         = "{$base_url}/index.php";
        $this->username         = $username;
        $this->password         = $password;
        
        $this->webservice_url   = "webservice";

        $this->curl             = new Curl($this->context);
        
        $this->curl->set_base_url($this->base_url);
    }
    
    public function check_connection(){
        return $this->curl->get_data($this->webservice_url, array(
            'username'      => $this->username,
            'password'      => $this->password,
            'action'        => 'check_connection',
        ));
    }

    public function generate_invoice($client_data = array(), $invoice_data = array(), $other_data = array()){

        $curl_data  = array(
            'username'      => $this->username,
            'password'      => $this->password,
            'action'        => 'generate_invoice',
            'client'        => $client_data,
            'invoice'       => $invoice_data,

            'other_data'    => $other_data,    
        );

        $curl_data    = apply_filters('yps/framework/invoice_plane/generate_invoice', $curl_data);

        return $this->curl->get_data($this->webservice_url, $curl_data);
    }
    
    public function generate_quote($client_data = array(), $quote_data = array(), $other_data = array()){

        $curl_data  = array(
            'username'      => $this->username,
            'password'      => $this->password,
            'action'        => 'generate_quote',

            'client'        => $client_data,
            'quote'         => $quote_data,

            'other_data'    => $other_data,         
        );

        $curl_data    = apply_filters('yps/framework/invoice_plane/generate_quote', $curl_data);

        return $this->curl->get_data($this->webservice_url, $curl_data);
    }

    public function get_invoice($invoice_id){

        return $this->curl->get_data($this->webservice_url, array(
            'username'      => $this->username,
            'password'      => $this->password,
            'action'        => 'get_invoice',
            
            'invoice_id'    => $invoice_id
        ));
    }
    
    public function get_quote($quote_id){

        return $this->curl->get_data($this->webservice_url, array(
            'username'      => $this->username,
            'password'      => $this->password,
            'action'        => 'get_quote',
            
            'quote_id'      => $quote_id
        ));
    }

    public function get_invoice_pdf($invoice_id){

        return $this->curl->get_data($this->webservice_url, array(
            'username'      => $this->username,
            'password'      => $this->password,
            'action'        => 'get_invoice_pdf',
            
            'invoice_id'    => $invoice_id
        ));
    }

    public function get_quote_pdf($quote_id){

        return $this->curl->get_data($this->webservice_url, array(
            'username'      => $this->username,
            'password'      => $this->password,
            'action'        => 'get_quote_pdf',
            
            'quote_id'    => $quote_id
        ));
    }

    public function get_invoice_groups(){
        return $this->curl->get_data($this->webservice_url, array(
            'username'      => $this->username,
            'password'      => $this->password,
            'action'        => 'get_invoice_groups'
        ));
    }

    public function get_payment_methods(){
        return $this->curl->get_data($this->webservice_url, array(
            'username'      => $this->username,
            'password'      => $this->password,
            'action'        => 'get_payment_methods'
        ));
    }

    public function get_upsert_tax_rate($tax_rate_name, $tax_rate){
        return $this->curl->get_data($this->webservice_url, array(
            'username'      => $this->username,
            'password'      => $this->password,
            'action'        => 'get_upsert_tax_rate',

            'tax_rate_name' => $tax_rate_name,
            'tax_rate'      => $tax_rate
        ));
    }
}
    