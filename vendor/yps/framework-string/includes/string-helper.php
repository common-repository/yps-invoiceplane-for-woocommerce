<?php

namespace YPS\Framework\String\v346_950_484;

use YPS\Framework\Core\v346_950_484\Base;

class String_Helper extends Base {

    public static function count_digits($string){
        return preg_match_all("/[0-9]/", $string);
    }
    
    public static function generate_random($random_string_length = 10, $characters = 'abcdefghijklmnopqrstuvwxyz0123456789'){
        $string = '';

        for($i = 0; $i < $random_string_length; $i++) {
            $string .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $string;
    }
    
    /**
     * Replace first occurence from right
     * 
     * @param type $search
     * @param type $replace
     * @param type $subject
     * @return type
     */
    public static function right_replace_first($search, $replace, $subject){
        $pos = strrpos($subject, $search);

        if($pos !== false)
        {
            $subject = substr_replace($subject, $replace, $pos, strlen($search));
        }

        return $subject;
    }

    /**
     * Search the string $search inside $string
     * 
     * @return bool
     */
    public static function contains($search, $string){
        if(strpos($string, $search) !== false){
            return true;
        } else{
            return false;
        }
    }

    public static function get_only_letters($string){
        return preg_replace("/[^A-Z]+/", "", $string);
    }

    public static function get_only_numbers($string){
        return intval(preg_replace("/[^0-9]+/", "", $string));
    }

    
    public static function bool_to_string($value){
        return $value ? 'true' : 'false';
    }

}


