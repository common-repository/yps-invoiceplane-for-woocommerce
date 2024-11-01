<?php
namespace YPS\Framework\Net\v346_950_484;

use YPS\Framework\Core\v346_950_484\Base;

class Url_Helper extends Base {

    /**
     * Get only the domain from URL, for example:
     * https://www.website.com/url?name=value
     * 
     * Output will be:
     * 
     * website.com
     * 
     * @param string
     */
    public static function get_domain($url){
        return strtolower(trim(str_ireplace('www.', '', parse_url($url, PHP_URL_HOST))));
    }
    
    /**
     * Get only the URL query (arguments), for example, if input is:
     * 'http://website.com/wp-admin/admin.php?page=yps-support&controller=project'
     * Output will be:
     * 'page=yps-support&controller=project'
     * 
     * @param string $url Url string
     * @param bool $remove_first_key If true, the first key will be remove (Ex: 'yps-support&controller=project')
     * 
     * @return string Query string
     */
    public static function parse_query_url($url, $remove_first_key = false){
        $parts  = parse_url($url);
        
        parse_str($parts['query'], $query);
        
        if($remove_first_key == true){
            reset($query);
            $first_key      = key($query);
            $first_value    = $query[$first_key];
            
            unset($query[$first_key]);
            
            $ret            = http_build_query($query);

            if(empty($ret)){
                return $first_value;
            }
            
            return "{$first_value}&{$ret}";
        }
        
        return http_build_query($query);
    }


}
