<?php

namespace YPS\Framework\Woocommerce\v346_950_484;

use YPS\Framework\Core\v346_950_484\Base;
use YPS\Framework\Core\v346_950_484\Helper;

use YPS\Framework\Woocommerce\v346_950_484\Woocommerce_Product_Helper;

class Woocommerce_Ajax_Query_Helper extends Base {

    public function __construct($context, $params = array()) {

        parent::__construct($context, $params);
        
        add_action("wp_ajax_yps_query_ajax_callback", array($this, 'ajax_callback'));
        add_action("wp_ajax_nopriv_yps_query_ajax_callback", array($this, 'ajax_callback'));
    }

    public function ajax_callback(){

        $query      = Helper::get_request("query");

        if($query == 'products'){

            $s          = Helper::get_request("s");
            $post__in   = Helper::get_request("post__in"); //Must be an array

            if(empty($s) && empty($post__in)){
                die(json_encode(array()));
            }

            $products   = Woocommerce_Product_Helper::get_array_of_products(array(
                's'         => $s,
                'post__in'  => $post__in
            ));

            die(json_encode($products));
        }

        die("Nothing to do!");
    }

    



}
