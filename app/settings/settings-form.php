<?php

namespace YPS\WC_Invoice_Plane;

use YPS\Framework\Woocommerce\v346_950_484\Woocommerce_Tax_Helper;
use YPS\WC_Invoice_Plane\Framework\Woocommerce\Woocommerce_Payment_Gateways;
use YPS\WC_Invoice_Plane\Framework\Form\Form;
use YPS\WC_Invoice_Plane\Framework\Form\Form_Group;
use YPS\WC_Invoice_Plane\Framework\Form\Label_Form_Field;
use YPS\WC_Invoice_Plane\Framework\Form\Select_Form_Field;
use YPS\WC_Invoice_Plane\Framework\Form\Text_Form_Field;
use YPS\WC_Invoice_Plane\Framework\Form\Password_Form_Field;
use YPS\WC_Invoice_Plane\Framework\Woocommerce\Woocommerce_Product_Helper;

class Settings_Form extends Form {
    
    public function __construct($context, $params = array()) {
        parent::__construct($context, $params);
        
        $invoiceplane_helper                = new Invoice_Plane_Helper($context, $params);

        $connection_group                   = new Form_Group($this->context);

        $connection_group
            ->set_name('connection')
            ->set_label(__('Connection Settings', 'yps-wc-ip'))
            ->set_wrapper_classes('mb-5');

        $url                      = new Text_Form_Field($context, $params);
        $username                 = new Text_Form_Field($context, $params);
        $password                 = new Password_Form_Field($context, $params);

        $this->add_group($connection_group);

        $url
            ->set_name("url")
            ->set_label(__('URL', 'yps-wc-ip'))
            ->set_allow_empty(false)
            ->set_group_name('connection')
            ->set_group_row_number(0)
            ->set_wrapper_classes('col-sm-12 pt-3')
            ->set_attributes(array('class' => array('form-control')));

        $username
            ->set_name("username")
            ->set_label(__('Username', 'yps-wc-ip'))
            ->set_allow_empty(false)
            ->set_group_name('connection')
            ->set_group_row_number(0)
            ->set_wrapper_classes('col-sm-6 pt-3')
            ->set_attributes(array('class' => array('form-control')));

        $password
            ->set_name("password")
            ->set_label(__('Password', 'yps-wc-ip'))
            ->set_allow_empty(false)
            ->set_group_name('connection')
            ->set_group_row_number(0)
            ->set_wrapper_classes('col-sm-6 pt-3')
            ->set_attributes(array('class' => array('form-control')));

        $this->add_field($url);
        $this->add_field($username);
        $this->add_field($password);

        if($invoiceplane_helper->get_connection_settings_status() == true){

            $ip_webservice          = new IP_Webservice($context, $params);

            //echo "TEST";
            //print_r($ip_webservice->get_upsert_tax_rate("TEST", 23));

            $enabled_payment_gateways           = Woocommerce_Payment_Gateways::get_enabled_payment_gateways();

            $payment_method_mapping_group       = new Form_Group($this->context);
            $invoice_group                      = new Form_Group($this->context);

            $payment_method_mapping_group
                ->set_name('payment-method-mapping')
                ->set_label(__('Payment Method Mapping', 'yps-wc-ip'))
                ->set_wrapper_classes('mb-5');
    
            $invoice_group
            ->set_name('invoice')
            ->set_label(__('Invoice Settings', 'yps-wc-ip'))
            ->set_wrapper_classes('mb-5');
    
            if(count($enabled_payment_gateways) != 0){
                $this->add_group($payment_method_mapping_group);
            }

            $this->add_group($invoice_group);

            $invoice_group_id         = new Select_Form_Field($context, $params);
    
            $send_invoice             = new Select_Form_Field($this->context, $this->params);

            $send_invoice
                ->set_name("send_invoice")
                ->set_label(__('Send Invoice to InvoicePlane', 'yps-wc-ip'))
                ->set_allow_empty(true)
                ->set_group_name('invoice')
                ->set_group_row_number(0)
                ->set_wrapper_classes('col-sm-6 pt-3')
                ->set_attributes(array('class' => array('form-control')))
                ->set_options(array(0 => 'No', 1 => 'Yes'));

            $row_index      = 0;
            $col_index      = 0;
            foreach($enabled_payment_gateways as $payment_method){
    
                $payment_method_title       = $payment_method->get_title();
                $payment_method_id          = $payment_method->id;
    
                $payment_method_field       = new Select_Form_Field($context);
    
                $payment_method_field
                    ->set_label($payment_method->get_title() . " [" . $payment_method->method_title . "]")
                    ->set_name("payment_method_{$payment_method_id}")
                    ->set_allow_empty(false)
                    ->set_group_name('payment-method-mapping')
                    ->set_group_row_number($row_index)
                    ->set_wrapper_classes('col-sm-6 pt-3')
                    ->set_attributes(array('class' => array('form-control')))
                    ->set_options(array(null => '-- Select --') + $invoiceplane_helper->get_payment_methods_array());
    
                if($col_index > 1){
                    $row_index++;
                    $col_index  = 0;
                }else{
                    $col_index++;
                }
                
    
                $this->add_field($payment_method_field);
            }

            $invoice_group_id
            ->set_label(__('InvoicePlane Group', 'yps-wc-ip'))
            ->set_name("invoice_group_id")
            ->set_allow_empty(false)
            ->set_group_name('invoice')
            ->set_group_row_number(0)
            ->set_wrapper_classes('col-sm-6 pt-3')
            ->set_attributes(array('class' => array('form-control')))
            ->set_options(array(null => '-- Select --') + $invoiceplane_helper->get_invoice_groups_array());
        
            $this->add_field($invoice_group_id);
            $this->add_field($send_invoice);
        }

    
        apply_filters('yps_wc_ip/settings_form', $this);
    }

    public function validate($data, $optional_parameters = array())
    {
        $invoiceplane_helper        = new Invoice_Plane_Helper($this->context, $this->params);

        if($invoiceplane_helper->get_connection_status($data['url'], $data['username'], $data['password']) !== true){
            $this->add_alert(Form::ALERT_TYPE_ERROR, "connection_error", "Unable to connect to InvoicePlane, please check connection settings");
        }

        return parent::validate($data, $optional_parameters);
    }

    

}
