<?php

namespace YPS\Framework\Woocommerce\v346_950_484;

use YPS\Framework\Core\v346_950_484\Base;

class Woocommerce_Variable_Product_Helper extends Base {

    /**
     * Get all the product variations
     * 
     * @param WC_Product $product WooCommerce Product
     * @return array Array of WooCommerce Product variations. (0 items if none)
     */
    public static function get_product_variations($product){
        $variation_ids        = $product->get_children();
        $variations           = array();

        foreach($variation_ids as $variation_id){
            $variations[$variation_id]      = \wc_get_product($variation_id);
        }

        return $variations;
    }

}
    
