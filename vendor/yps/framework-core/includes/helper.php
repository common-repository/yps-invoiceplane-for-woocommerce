<?php

namespace YPS\Framework\Core\v346_950_484;

use YPS\Framework\Wordpress\v346_950_484\Wordpress_Helper;
use YPS\Framework\String\v346_950_484\String_Helper;

use MatthiasMullie\Minify;

/**
 * Generic useful functions
 */
class Helper extends Base {

    /**
     * Is it a POST request or not?
     *
     * @return bool Return if the method is post or not
     */
    public static function is_post(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            return true;
        }

        return false;
    }

    /**
     * Get a value from the request
     *
     *
     * @param string $name It is the variable to get
     * @param string $type type of the request (GET, POST or REQUEST).
     * @param string $default Default value if variable it not set.
     *
     * @return bool|string|array
     */
    public static function get_request($name = null, $default = null, $type = "REQUEST"){

        if($type == null){
            $type   = "REQUEST";
        }

        if(empty($name)){
            if($type == "REQUEST"){
                return $_REQUEST;
            }else if($type == "GET"){
                return $_GET;
            }else if($type == "POST"){
                return $_POST;
            }
        }

        if($type == "REQUEST"){
            if(isset($_REQUEST[$name])){
                return self::stripslashes_all($_REQUEST[$name]);
            }
        }else if($type == "GET"){
            if(isset($_GET[$name])){
                return self::stripslashes_all($_GET[$name]);
            }
        }else if($type == "POST"){
            if(isset($_POST[$name])){
                return self::stripslashes_all($_POST[$name]);
            }
        }

        return $default;
    }
    
    /**
     * Try to slipslashes all the object
     * 
     * @param string|array $object
     * @return string|array
     */
    public static function stripslashes_all($object){

        if(is_string($object)){
            return stripslashes($object);
        }

        if(is_array($object)){
            return self::stripslashes_array($object);
        }

        return $object;
    }

    /**
     * Try to slipslashes an array
     * 
     * @param array $object
     * @return array
     */
    public static function stripslashes_array($value){
        $value = is_array($value) ?
                    array_map('\YPS\Framework\Core\v346_950_484\Helper::stripslashes_array', $value) :
                    stripslashes($value);

        return $value;
    }

    /**
     * Enqueue javascript files
     *
     * @param string $name
     * @param string $url
     * @param array $deps
     * @param bool $version
     * @return void
     */
    public static function enqueue_remote_script($name, $url, $deps = array(), $version = false, $footer = false){
        
        if($version == false){
            Wordpress_Helper::write_log("Warning: No version has been set for {$url}");
        }
        
        wp_enqueue_script($name, $url, $deps, $version, $footer); 
    }
    
    /**
     * Enqueue javascript files
     *
     * @param string $name
     * @param string $plugin_code
     * @param string $url
     * @param array $deps
     * @param bool $version
     * @return void
     */
    public static function enqueue_script($name, $plugin_code, $url, $deps = array(), $version = false, $footer = false){

        if(strpos($url, ".min.js") === false && self::get_constant_value("YPS_TEST") !== true){
            $path           = self::get_plugin_path($plugin_code, $url);

            $url            = String_Helper::right_replace_first(".js", ".min.js", $url);
            $new_path       = String_Helper::right_replace_first(".js", ".min.js", $path);

            if(!file_exists($new_path)){
                $minifier = new Minify\JS($path);
                $minifier->minify($new_path);
            }
        }

        self::enqueue_remote_script($name, self::get_plugin_url($plugin_code, $url), $deps, $version, $footer);
    }
    
    public static function enqueue_cdn_script($name, $relative_url, $deps = array(), $version = false, $footer = false){
        self::enqueue_remote_script($name, self::get_cdn_resources_url($relative_url), $deps, $version, $footer);
    }


    
    public static function enqueue_remote_style($name, $url, $version = false, $deps = array()){
        
        if($version == false){
            Wordpress_Helper::write_log($this->context, "Warning: No version has been set for {$url}");
        }
        
        wp_enqueue_style($name, $url, $deps, $version); 
    }
    
    /**
     * Enqueue stylesheet files
     *
     * @param string $name
     * @param string $url
     * @param bool $absolute
     * @return void
     */
    public static function enqueue_style($name, $plugin_code, $url, $version = false, $deps = array()){

        if(strpos($url, ".min.css") === false && self::get_constant_value("YPS_TEST") !== true){
            $path           = self::get_plugin_path($plugin_code, $url);

            $url            = str_replace(".css", ".min.css", $url);
            $new_path       = str_replace(".css", ".min.css", $path);

            if(!file_exists($new_path)){
                $minifier = new Minify\CSS($path);
                $minifier->minify($new_path);
            }
        }

        self::enqueue_remote_style($name, self::get_plugin_url($plugin_code, $url), $version, $deps);
    }
    
    /**
     * Enqueue stylesheet files
     *
     * @param string $name
     * @param string $url
     * @param bool $absolute
     * @return void
     */
    public static function enqueue_cdn_style($name, $relative_url, $version = false, $deps = array()){
        self::enqueue_remote_style($name, self::get_cdn_resources_url($relative_url), $version, $deps);
    }
    
    /**
     * Get the site url.
     *
     * @return string
     */
    public static function get_site_url(){

        $site_url    = site_url();

        /* Remove if the last character present is / */
        return rtrim($site_url, '/');
    }

    /**
     * Get the website root path
     * 
     * @return string
     */
    public static function get_site_path(){
        return ABSPATH;
    }

    /**
     * Get the resources url.
     *
     * @param string $readpath, the relative path
     * @return string
     */
    public static function get_resources_url($plugin_code, $readpath = ''){
        $site_url    = self::get_site_url();

        return "{$site_url}/wp-content/plugins/{$plugin_code}/resources/{$readpath}";
    }

    /**
     * Return the path of the plug-in.
     *
     * @param string $relpath relative plug-in path.
     * @return string
     */
    public static function get_plugin_path($plugin_code, $relpath){
        $site_path      = self::get_site_path();

        return "{$site_path}wp-content/plugins/{$plugin_code}/{$relpath}";                
    }

    /**
     * Return the URL of the plugin. It is possible to get the root or child url
     * 
     * @param string $plugin_code Plugin identifier
     * @param string $relpath Relative path (if needed)
     * @return string The URL
     */
    public static function get_plugin_url($plugin_code, $relpath = ''){
        $site_url    = self::get_site_url();

        return "{$site_url}/wp-content/plugins/{$plugin_code}/{$relpath}";
    }

    public static function get_framework_url($plugin_code, $lib_name, $relpath){
        return self::get_plugin_url($plugin_code, "vendor/yps/{$lib_name}/{$relpath}");
    }

    /**
     * Create javascript variables
     *
     * @param string $handle
     * @param string $name
     * @param array $data
     * @return void
     */
    public static function localize_script($handle, $name, $data){

        wp_localize_script($handle, $name, $data);
    }

    /**
     * Return the page url: "{$page}?arg1=val1&arg2=val2.
     *
     * @param string $page "{$page}"
     * @param array $params List of parameters
     * @return string
     */
    public static function get_page_url($page, $params = null){

        $url    = $page;

        if($params !== null){
                foreach($params as $key => $value){
                    if(!empty($value)){
                        $url .= '&' . $key . '=' . $value;
                    }
                }
        }
        return $url;
    }

    /**
     * Return the admin page url.
     *
     * @param string $page "page={$page}"
     * @param array $params List of parameters
     * @return string
     */
    public static function get_admin_url($page, $params = null, $is_network = false){

        $page_url   = self::get_page_url($page, $params);
        $url        = "admin.php?page={$page_url}";

        if($is_network){
            return network_admin_url($url);
        }
        
        return admin_url($url);
    }

    /**
     * Get the upload path.
     *
     * @param string $relative_path, the relative path
     * @return string
     */
    public static function get_upload_path($plugin_code, $relative_path = null){
        $upload_dir_array     = wp_upload_dir();

        if(empty($relative_path)){
            return "{$upload_dir_array['basedir']}/{$plugin_code}";
        }
        
        return "{$upload_dir_array['basedir']}/{$plugin_code}/{$relative_path}";
    }

    public static function get_upload_url($plugin_code, $relative_path){
        $upload_dir_array     = wp_upload_dir();

        return "{$upload_dir_array['baseurl']}/{$plugin_code}/{$relative_path}";
    }

    /**
     * Get the Ajax base URL
     * 
     * @return string The ajax base url
     */
    public static function get_ajax_base_url(){
        $site_url        = self::get_site_url();

        return "{$site_url}/wp-admin/admin-ajax.php";

    }

    /**
     * Get the Ajax Url by passing parameters
     * 
     * @param array $params The parameters
     * 
     * @return string The complete ajax url
     */
    public static function get_ajax_url($plugin_code, $params = array()){
        
        $params['action']    = "{$plugin_code}_ajax_callback";
        
        $string_params       = http_build_query($params);
        $base_ajax_url       = self::get_ajax_base_url();

        if(count($params) == 0){
            return $base_ajax_url;
        }

        //url: WPC_HANDLE_SCRIPT.siteurl + "/wp-admin/admin-ajax.php?action=ajax_callback&id=" + productId + "&simulatorid=" + simulatorId,
        return "{$base_ajax_url}?{$string_params}";

    }

    /**
     * If $var is set return $var, otherwise $or_value
     * 
     * @param array $var The variable to check
     * @param any $or_value The return value if $var is not set
     * @return any
     */
    public static function isset_or($var, $or_value){
        if(isset($var)){
            return $var;
        }

        return $or_value;
    }

    public static function convert_data($data, $type){
        if($type == "bool"){
            if(!empty($data) && ($data == true || $data == 'on')){
                return 1;
            }else{
                return 0;
            }
        }

        return $data;
    }
    
    public static function get_current_url(){
        $http   = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");

        return "{$http}://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
    }
    
    public static function get_cdn_resources_url($relpath){
        if (defined( 'YPS_DEV_MODE' ) && YPS_DEV_MODE){
            return "http://ydev.cdn.yourplugins.com/{$relpath}";
        }else{
            return "https://cdn.yourplugins.com/{$relpath}";
        }
        
    }
    
    public static function get_constant_value($constant_name){
        if(defined($constant_name)){
            return constant($constant_name);
        }
        
        return null;
    }
    
    public static function get_plugin_file_path(){
        $path           = __FILE__;
        $directories    = explode("/", $path);
        $ret            = array();
        
        foreach($directories as $index => $directory){
            
            $ret[]      = $directory;
            
            if($directory == 'plugins'){
                $ret[]      = $directories[$index+1];
                
                return implode("/", $ret) . "/{$directories[$index+1]}.php";
            }
        }
        
        return null;

    }
    
    public static function get_code(){
        $path           = __FILE__;
        $directories    = explode("/", $path);

        foreach($directories as $index => $directory){

            if($directory == 'plugins'){
                return $directories[$index+1];
            }
        }
        
        return null;
    }
    
    public static function get_version(){
        $plugin_data        = get_plugin_data(self::get_plugin_file_path());
        
        return $plugin_data['Version'];
    }
}

