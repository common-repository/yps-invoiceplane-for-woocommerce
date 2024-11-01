<?php

namespace YPS\Framework\Core\v346_950_484;

class Controller extends Base {

    const RESPONSE_JSON                     = "json";
    const RESPONSE_HTML                     = "html";
    const RESPONSE_JSON_FRAMEWORK_VIEW      = "json_view";

    public static $log_file             = 'controller';
    
    protected $default_controller_name;
    protected $is_default_controller            = false;
    protected $always_execute_index_action      = false;

    protected $is_ajax_only                     = false;

    protected $view_helper                      = null;

    protected $controller_page                  = null;

    public function __construct($context, $params = array()) {

        parent::__construct($context, $params);

        if(isset($params['module'])){
            $this->module   = $params['module'];
        }

        if(empty($this->controller_page)){
            $this->controller_page = $this->context->get_plugin_code();
        }

    }

    public function request_display(){

        $raw_request        = Helper::get_request("raw");
        $can_exec_action    = $this->can_exec_action($this->controller_page);
        
        if($can_exec_action !== false){

            if(method_exists($this, 'custom_load')){
                $this->custom_load();
            }

            if($this->is_ajax_only == true && $raw_request == false){
                return;
            }
            
            $view   = $this->{$can_exec_action}();

            if(!empty($view)){
                $view->set_parent_controller($this);
                $view->display();
            }

            if($raw_request == true){
                die();
            }
        }

    }

    public function get_kebab_current_controller_name(){
        $controller_name        = $this->get_current_controller_name();
        
        $controller_name        = str_replace("_Controller", "", $controller_name);
        $controller_name        = str_replace("_", "-", $controller_name);

        return strtolower($controller_name);
    }

    public function get_current_action_execution(){
        $action_name        = $this->get_request_action_name();

        return $this->{$action_name}();
    }
    
    public function get_current_controller_name(){
        return substr(strrchr(get_class($this), "\\"), 1);
    }
    
    public function can_exec_action($controller_page){

        $controller_name        = $this->get_current_controller_name();
        $plugin_request         = Helper::get_request("page", (isset($this->params['page'])?$this->params['page']:null));
        
        if(!empty($plugin_request) && strpos($controller_name, "Controller") !== false){

            $action_request         = $this->get_request_action_name();
            $controller_request     = $this->get_request_controller_name();

            if($this->always_execute_index_action == true){
                if($plugin_request == $controller_page){
                    return "index_action";
                }
            }

            if(
                method_exists($this, $action_request) && 
                $plugin_request == $controller_page &&
                $controller_request == $controller_name){

                return $action_request;
            }
        }
        
        return false;
    }

    public function get_is_ajax_only(){
        return $this->is_ajax_only;
    }

    public function set_is_ajax_only($is_ajax_only){
        $this->is_ajax_only    = $is_ajax_only;

        return $this;
    }

    /**
     * Default action to be executed (It must be overriden)
     */
    public function index_action(){
        throw new \Exception("You must override the index_action()");
    }
    
    public function get_request_controller_name(){
        $controller_request     = Helper::get_request("controller", (isset($this->params['controller'])?$this->params['controller']:null));
        $controller_request     = $this->translate_controller_name($controller_request);

        if(empty($controller_request)){
            $controller_request          = $this->default_controller_name;
        }
        
        if(empty($controller_request) && $this->is_default_controller == true){
            $controller_request     = $this->get_current_controller_name();
        }else{
            $controller_request     = "{$controller_request}_Controller";
        }
        
        return $controller_request;
    }
    
    public function get_request_action_name(){
        $action_request             = Helper::get_request("action", (isset($this->params['action'])?$this->params['action']:null));

        if(empty($action_request)){
            $action_request                 = "index";
        }
        
        $action_request             = $this->translate_action_name("{$action_request}_action");
        
        return $action_request;
    }
    
    /**
     * Translate the controller name from Kebab Case (x-y-z) to Snake Case (X_Y_Z)
     * 
     * @param string $kebab_controller_name Kebab Case Controller Name
     */
    public function translate_controller_name($kebab_controller_name){
        $translate_array      = explode("-", $kebab_controller_name);

        foreach($translate_array as &$translate_word){
            $translate_word     = ucfirst($translate_word);
        }

        return implode("_", $translate_array);
    }

    /**
     * Translate the action name from Kebab Case (x-y-z) to Snake Case (x_y_z)
     * 
     * @param string $kebab_action_name Kebab Case Action Name
     * @return string
     */
    public function translate_action_name($kebab_action_name){
        return str_replace("-", "_", $kebab_action_name);
    }

    /**
     * The default controller if no controller has been requested
     * 
     * @param string $controller_name The name of the controller
     */
    public function set_default_controller($controller_name){
        $this->default_controller_name    = $controller_name;
    }

    /**
     * Get the view .
     *
     * @deprecated When possible use "get_view" of View
     * 
     * @param array $module Module name
     * @param array $view, the relative path
     * @param array $params View parameters that could be used inside theme files
     * 
     * @return string
     */
    function get_view($module, $view_path, $params = array()){

        $params             = apply_filters('yps_get_view_params', $params);
        $module_view_path   = "app/{$module}/view/{$view_path}";
        $template_path      = get_stylesheet_directory();
        $template_view_path = "{$template_path}/{$this->context->get_plugin_code()}/{$module}/{$view_path}";
        
        foreach($params as $param_name => $param_value){
            $this->view[$param_name] = $param_value;
        }
        
        ob_start();
        if(file_exists($template_view_path)){
            require($template_view_path);
        }else{
            require(Helper::get_plugin_path($this->context->get_plugin_code(), $module_view_path));
        }

        $view_html   = ob_get_contents();
        ob_end_clean();

        return $view_html;

    }
    
    /**
     * @deprecated When possible use view "get_framework_view"
     */
    public function get_framework_view($framework_name, $view_path, $params = array()){
        
        $lib_name       = $this->context->get_framework_data($framework_name, 'framework_folder');

        foreach($params as $param_name => $param_value){
            $this->view[$param_name] = $param_value;
        }

        if(isset($this->params['parent_plugin_code'])){
            $view_src   = Helper::get_plugin_path($this->params['parent_plugin_code'], "vendor/yps/{$lib_name}/view/{$view_path}");
        }else{
            $view_src   = Helper::get_plugin_path($this->context->get_plugin_code(), "vendor/yps/{$lib_name}/view/{$view_path}");
        }

        ob_start();
        require($view_src);
        $view_html   = ob_get_contents();
        ob_end_clean();

        return $view_html;
        
    }

    /**
     * Get the view .
     *
     * @param array $module
     * @param array $view, the relative path
     * @param bool $admin
     * @param array $params
     * @return string
     */
    static function get_view_for_plugin($plugin_code, $module, $view_path, $params = array()){

        $params     = apply_filters('yps_get_view_params', $params);
        
        foreach($params as $param_name => $param_value){
            $view[$param_name] = $param_value;
        }

        ob_start();
        require(Helper::get_plugin_path($plugin_code, "{$module}/view/{$view_path}"));
        $view_html   = ob_get_contents();
        ob_end_clean();

        return $view_html;
    }

    function get_admin_url($params = null){
        $url = "admin.php?page={$this->context->get_plugin_code()}";

        foreach($params as $key => $value){
            if(!empty($value)){
                $url .= '&' . $key . '=' . $value;
            }
        }
        return admin_url($url);
    }

    /**
     * Return the locale in xx_XX format
     *
     * @return string
     */
    function get_locale(){
        return get_locale();
    }

    /*
        * Get the current locale file path for the system
        * 
        * @return string File path
        */
    function get_system_current_locale_file_path(){
        $default_locale      = "en_US";   
        $locale             = $this->get_locale();

        $lang_file_path       = Helper::get_plugin_path($this->context->get_plugin_code(), "{$this->module}/Language/{$locale}.php", true);

        if(empty($locale) || file_exists($lang_file_path) == false){
            $locale         = $default_locale;
            $lang_file_path   = Helper::get_plugin_path($this->context->get_plugin_code(), "{$this->module}/Language/{$locale}.php", true);
        }

        return $lang_file_path;

    }

    /**
     * Strings in different languages.
     *
     * Return the string requested by a key in the required language.
     *
     * @param string $string
     * @param array $tokens
     * @return string
     */
    function trans($string, $tokens = array()){

        $lang_file_path   = $this->get_system_current_locale_file_path();
        $translations   = include $lang_file_path;

        if(isset($this->translator_files['system'])){
            foreach($this->translator_files['system'] as $translator_file){
                if(!empty($translator_file)){
                    $translations   = array_merge($translations, include $translator_file);
                }
            }
        }

        if(isset($translations[$string])){
            $translation    = $translations[$string];

            foreach($tokens as $key => $value){
                $translation     = str_replace("%{$key}%", $value, $translation);
            }
        }else{
            return $string;
        }

        if(empty($translation)){
            return $string;
        }

        return $translation;
    }

    public function is_requested_controller_name($controller_name){
        if($this->get_request_controller_name() == $controller_name){
            return true;
        }
        
        return false;
    }

    public function html_requested_controller_name($controller_names, $html_if_true, $html_if_false = "", $return = false){

        $html_result    = $html_if_false;

        if(is_array($controller_names)){
            if(in_array($this->get_request_controller_name(), $controller_names) == true){
                $html_result    = $html_if_true;
            }
        }else{
            if($this->is_requested_controller_name($controller_names) == true){
                $html_result    = $html_if_true;
            }
        }

        if($return === true){
            return $html_result;
        }

        echo $html_result;
    }

    /**
     * Print a response to the page
     */
    public function set_response($response, $type = self::RESPONSE_JSON, $module = null, $view_path = null, $params = null){

        if($type == self::RESPONSE_HTML){
            echo $response;
        }

        if($type == self::RESPONSE_JSON){
            header('Content-Type: application/json; charset=utf-8');
            
            echo json_encode($response);
        }

        if($type == self::RESPONSE_JSON_FRAMEWORK_VIEW){
            echo \json_encode(array(
                'html'      => $this->get_framework_view($module, $view_path, $params),
                'json'      => $response
            ));
        }

        die();
    }

    /**
     * @deprecated since version 1.0.0 (Use the Form_Field function print_field_label_html)
     */
    public function html_field_label($field){
        throw new \Exception("Deprecated: Use the Form_Field function print_field_label_html");
    }
            
    public function redirect($location, $status = 302){
        wp_redirect($location, $status);
        
        //Force redirect
        die();
    }
    
    public function get_string_from_selector($string){
        $string     = str_replace("#", "", $string);
        $string     = str_replace(".", "", $string);
        
        return $string;
    }
    
    public function html_price($number, $currency = '€', $decimals = 2, $dec_point = ",", $thousands_sep = "."){
        echo number_format($number , $decimals, $dec_point, $thousands_sep) . " " . $currency;
    }
    
    public function plugin_header(){
        echo $this->get_framework_view("Core", "plugin-header/plugin-header.php");
    }

    /**
     * @deprecated Use view
     */
    public function get_view_helper(){

        if(empty($this->view_helper)){
            return new View_Helper($this->context, $this->params);
        }

        return $this->view_helper;
    }

    /**
     * @deprecated Use view
     */
    public function set_view_helper($view_helper){
        $this->view_helper  = $view_helper;

        return $this;
    }
    

	/**
	 * Get the value of controller_page
	 *
	 * @return mixed
	 */
	public function get_controller_page(){
		return $this->controller_page;
	}

	/**
	 * Set the value of controller_page
	 *
	 * @param   mixed  $controller_page  
	 *
	 * @return  self
	 */
	public function set_controller_page($controller_page){
		$this->controller_page = $controller_page;

		return $this;
	}

	/**
	 * Get the value of always_execute_index_action
	 *
	 * @return mixed
	 */
	public function get_always_execute_index_action(){
		return $this->always_execute_index_action;
	}

	/**
	 * Set the value of always_execute_index_action
	 *
	 * @param   mixed  $always_execute_index_action  
	 *
	 * @return  self
	 */
	public function set_always_execute_index_action($always_execute_index_action){
		$this->always_execute_index_action = $always_execute_index_action;

		return $this;
	}

	/**
	 * Get the value of is_default_controller
	 *
	 * @return mixed
	 */
	public function get_is_default_controller(){
		return $this->is_default_controller;
	}

	/**
	 * Set the value of is_default_controller
	 *
	 * @param   mixed  $is_default_controller  
	 *
	 * @return  self
	 */
	public function set_is_default_controller($is_default_controller){
		$this->is_default_controller = $is_default_controller;

		return $this;
	}
}
