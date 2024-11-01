<?php

namespace YPS\Framework\Woocommerce\v346_950_484;

use YPS\Framework\Core\v346_950_484\Base;

class Woocommerce_Tax_Helper extends Base {

    public static function get_all_tax_rates(){
        $tax_classes        = self::get_tax_classes();
        $ret_tax_rates      = array();

        foreach($tax_classes as $tax_class){
            foreach($tax_class['rates'] as $tax_rate){
                $ret_tax_rates[]    = $tax_rate->tax_rate;
            }
        }

        return array_unique($ret_tax_rates);
    }

    public static function get_tax_classes(){
        $slugs          = \WC_Tax::get_tax_class_slugs();
        $labels         = \WC_Tax::get_tax_classes();
        
        $classes        = array();
        
        $classes[]              = array(
            'class_name'    => 'Standard',
            'rates'         => \WC_Tax::get_rates_for_tax_class("standard")
        );
        
        foreach($slugs as $index => $slug){
            $classes[$slug]     = array(
                'class_name'    => $labels[$index],
                'rates'         => \WC_Tax::get_rates_for_tax_class($slug)  
                
            );
        }

        return $classes;
    }
    
    public static function get_shipping_tax_rates(){

        $tax_classes    = self::get_tax_classes();
        $shipping_rates = array();
        
        foreach($tax_classes as $tax_class){
            foreach($tax_class['rates'] as $rate){
                if($rate['shipping'] == 'yes'){
                    $shipping_rates[]       = $rate;
                }
            }
        }
        
        return $shipping_rates;
    }

}
