<?php

namespace YPS\Framework\Woocommerce\v346_950_484;

use YPS\Framework\Core\v346_950_484\Base;

class Woocommerce_Helper extends Base {

    public static function is_woocommerce_activated(){
        if(!function_exists('WC')){
            return false;
        }

        return true;
    }

}
