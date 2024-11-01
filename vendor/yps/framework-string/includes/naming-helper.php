<?php

namespace YPS\Framework\String\v346_950_484;

class Naming_Helper {

    public function __construct($context) {

    }
    
    public static function compose($firstname = '', $lastname = '', $company = ''){
        $ret    = "";
        
        if(!empty($firstname)){
            $ret    .= " {$firstname}";
        }
        
        if(!empty($lastname)){
            $ret    .= " {$lastname}";
        }
        
        if(!empty($company)){
            $ret    .= " ({$company})";
        }
        
        return $ret;
    }

    public static function ajust_case($string){
        $ret        = strtolower($string);
        $sep_chars  = array("-", " ", "'");
        
        foreach($sep_chars as $sep_char){
            $split      = explode($sep_char, $ret);

            foreach($split as &$value){
                if(strlen($value) != 1){
                    $value  = ucfirst($value);
                }
            }

            $ret     = implode($sep_char, $split);
        }
        
        return $ret;
    }
}


