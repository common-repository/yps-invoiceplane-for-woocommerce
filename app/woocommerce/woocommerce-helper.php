<?php

namespace YPS\WC_Invoice_Plane;

use YPS\WC_Invoice_Plane\Framework\Core\Base;
use YPS\WC_Invoice_Plane\Framework\Core\Helper;
use YPS\WC_Invoice_Plane\Framework\Wordpress\Wordpress_Helper;

use YPS\WC_Invoice_Plane\Framework\Invoice_Plane\Webservice;

class WooCommerce_Helper extends Base {

    protected $ip_webservice;
    protected $settings_model;

    public function __construct($context, $params = array()) {

        parent::__construct($context, $params);

        $this->ip_webservice    = new IP_Webservice($context, $params);
        $this->settings_model   = new Settings_Model($context, $params);
        
        if($this->settings_model->get_value('send_invoice') == true){
            add_action('woocommerce_order_status_completed', array($this, 'woocommerce_order_status_completed'), 1);
        }
        
    }
    
    public static function get_client_data_from_order($order){

        if(empty($order->get_billing_company())){
            $name       = $order->get_billing_first_name();
            $surname    = $order->get_billing_last_name();
        }else{
            $name       = $order->get_billing_company();
            $surname    = "";
        }
        
        return array(
            'client_name'           => $name,
            'client_surname'        => $surname,
            'client_address_1'      => $order->get_billing_address_1(),
            'client_address_2'      => $order->get_billing_address_2(),
            'client_city'           => $order->get_billing_city(),
            'client_state'          => $order->get_billing_state(),
            'client_zip'            => $order->get_billing_postcode(),
            'client_country'        => $order->get_billing_country()
        );
    }

    public function woocommerce_order_status_completed($order_id) {

        $order                          = \wc_get_order($order_id);
        $user                           = $order->get_user();
        $payment_method                 = $order->get_payment_method();
        $site_url                       = Helper::get_site_url();
        
        Wordpress_Helper::write_log($this->context, "PAYMENT_METHOD: " . $order->get_payment_method());

        $items_data         = array();
        
        foreach($order->get_items() as $item_id => $order_item){
            
			$item 				= $order_item->get_data();
            $product            = $order_item->get_product();

            $item_quantity      = $order_item->get_quantity();
            $item_total         = $order_item->get_total();

            $item_data          = array(
                'name'              => apply_filters('yps_wc_invoiceplane_item_name', $product->get_name(), $order, $product),
                'description'       => apply_filters('yps_wc_invoiceplane_item_description', "", $order_item, $order, $product),
                'quantity'          => $item_quantity,
                'price'             => $item_total / $item_quantity,
                'discount_amount'   => "",
            );

            $items_data[]       = apply_filters('yps_wc_invoiceplane/item_data', $item_data, $order, $order_item);
        }
        
        $invoice_data       = array(
            'group_id'              => $this->settings_model->get_value('invoice_group_id'),
            'payment_method_id'     => $this->settings_model->get_ip_payment_method($payment_method),
            'discount_amount'       => 0.0,
            'discount_percent'      => 0.0,
            'terms'                 => '',
            'items'                 => $items_data,
            'payment_total_amount'  => $order->get_total(),
            'payment_notes'         => "Transaction Reference: {$order->get_transaction_id()} Payment Provider: {$payment_method} from {$site_url}"
        );
		
        $other_data         = array(
            'wc_order_id'           => $order_id
        );

        $client_data    = apply_filters('yps_wc_invoiceplane/client_data', self::get_client_data_from_order($order), $order);
        $invoice_data   = apply_filters('yps_wc_invoiceplane/invoice_data', $invoice_data, $order);

        $result         = $this->ip_webservice->generate_invoice($client_data, $invoice_data, $other_data);

        update_post_meta($order->get_id(), '_yps_invoiceplane_data', $result);
        
		Wordpress_Helper::write_log($this->context, "RESULT:");
        Wordpress_Helper::write_log($this->context, $result);

        if($result['result'] === false){
            Wordpress_Helper::send_email_to_admin("InvoicePlane Error", "Sorry but during code execution an error has been detected: {$result['message']}");
        }
    }
        
}