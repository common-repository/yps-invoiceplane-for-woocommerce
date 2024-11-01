<?php

namespace YPS\Framework\Woocommerce\v346_950_484;

use YPS\Framework\Core\v346_950_484\Base;

class Woocommerce_Shipping_Method_Helper extends Base {

    public function __construct($context, $params = array()){
        parent::__construct($context, $params);
    }

    /**
     * Example: flat_rate:2 becomes flat_rate
     * 
     * @param string $shipping_method_name For example flat_rate:2
     * @return For example flat_rate
     */
    public static function get_shipping_method_name_without_id($shipping_method_name){
        $ret = explode(":", $shipping_method_name);

        return $ret[0];
    }

}
