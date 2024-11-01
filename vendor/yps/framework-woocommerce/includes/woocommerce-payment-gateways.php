<?php

namespace YPS\Framework\Woocommerce\v346_950_484;

use YPS\Framework\Core\v346_950_484\Base;

class Woocommerce_Payment_Gateways extends Base {

    public static function get_enabled_payment_gateways(){

        $gateways           = \WC()->payment_gateways->payment_gateways();
        $enabled_gateways   = [];
        
        if($gateways){
            foreach($gateways as $gateway) {
        
                if($gateway->enabled == 'yes') {
                    $enabled_gateways[] = $gateway;
                }
            }
        }

        return $enabled_gateways;
    }

}
