<?php

namespace YPS\Framework\Core\v346_950_484;

use YPS\Framework\Core\v346_950_484\Base;

class View_Helper extends Base {

    public function __construct($context, $params = array()) {

        parent::__construct($context, $params);
    }

    public static function html_json_encode($value){
        echo htmlentities(json_encode($value));
    }

    public function html_isset($var, $key, $if_true, $if_false = ""){
        if(isset($var[$key])){
            echo $if_true;
        }else{
            echo $if_false;
        }
    }
    
    public function html_print_if_set($var, $key, $if_not_set = ""){
        if(isset($var[$key])){
            echo $var[$key];
        }else{
            echo $if_not_set;
        }
    }
    
    public function html_if($value, $if_true, $if_false = ""){
        
        if($value == true){
            echo $if_true;
        }else{
            echo $if_false;
        }
    }
    
    public function html_if_checked($value){
        if($value == true){
            echo 'checked="checked"';
        }
    }
    
    public function html_selected($cmp1, $cmp2, $default_cmp = null){
        
        if(is_array($cmp2)){
            if(in_array($cmp1, $cmp2)){
                echo 'selected="selected"';
            }
        }else if(is_array($cmp1)){
            if(in_array($cmp2, $cmp1)){
                echo 'selected="selected"';
            }
        }else{
            if($cmp1 == $cmp2){
                echo 'selected="selected"';
            }
        }
    }
    
    public function html_checked($cmp1, $cmp2){
        
        if(is_array($cmp2)){
            if(isset($cmp2[$cmp1])){
                echo 'checked="checked"';
            }
        }else if(is_array($cmp1)){
            if(isset($cmp1[$cmp2])){
                echo 'checked="checked"';
            }
        }else{
            if($cmp1 == $cmp2){
                echo 'checked="checked"';
            }
        }
    }

}
