<?php

namespace YPS\Framework\Core\v346_950_484;

class View extends Base {

    const VIEW_OUTPUT_MODE_JSON                         = "json";
    const VIEW_OUTPUT_MODE_HTML                         = "html";
    const VIEW_OUTPUT_MODE_HTML_AND_JSON                = "html_and_json";

    const VIEW_LOAD_TEMPLATE_MODE_APPLICATION           = "app";
    const VIEW_LOAD_TEMPLATE_MODE_FRAMEWORK             = "framework";

    protected $parent_controller                        = null;
    protected $view_helper                              = null;

    protected $output_mode;

    protected $load_template_mode;
    protected $load_template_module_name;
    protected $load_template_rel_path;

    protected $view_params                              = array();
    protected $view_json_data                           = array();

    public function __construct($context, $params = array()) {
        parent::__construct($context, $params);

        $this->output_mode = self::VIEW_OUTPUT_MODE_HTML;
    }

    public function get_output_mode(){
        return $this->output_mode;
    }

    public function set_output_mode($output_mode){
        $this->output_mode     = $output_mode;

        return $this;
    }

    public function get_load_template_mode(){
        return $this->load_template_mode;
    }

    public function set_load_template_mode($load_template_mode){
        $this->load_template_mode           = $load_template_mode;

        return $this;
    }

    public function get_load_template_module_name(){
        return $this->load_template_module_name;
    }

    public function set_load_template_module_name($load_template_module_name){
        $this->load_template_module_name     = $load_template_module_name;

        return $this;
    }

    public function get_load_template_rel_path(){
        return $this->load_template_rel_path;
    }

    public function set_load_template_rel_path($load_template_rel_path){
        $this->load_template_rel_path    = $load_template_rel_path;

        return $this;
    }

    public function set_application_template($module, $load_template_rel_path){
        $this->load_template_mode            = self::VIEW_LOAD_TEMPLATE_MODE_APPLICATION;
        $this->load_template_module_name     = $module;
        $this->load_template_rel_path        = $load_template_rel_path;

        return $this;
    }

    public function set_framework_template($framework_name, $load_template_rel_path){
        $this->load_template_mode            = self::VIEW_LOAD_TEMPLATE_MODE_FRAMEWORK;
        $this->load_template_module_name     = $framework_name;
        $this->load_template_rel_path        = $load_template_rel_path;

        return $this;
    }

    public function get_view_params(){
        return $this->view_params;
    }

    public function set_view_params($view_params){
        $this->view_params      = $view_params;

        return $this;
    }

    public function get_view_json_data(){
        return $this->view_json_data;
    }

    public function set_view_json_data($view_json_data){
        $this->view_json_data   = $view_json_data;

        return $this;
    }

    public function get_parent_controller(){
        return $this->parent_controller;
    }

    public function set_parent_controller($parent_controller){
        $this->parent_controller    = $parent_controller;

        return $this;
    }

    public function get_view_helper(){

        if(empty($this->view_helper)){
            return new View_Helper($this->context, $this->params);
        }
        
        return $this->view_helper;
    }

    public function set_view_helper($view_helper){
        $this->view_helper  = $view_helper;

        return $this;
    }

    /**
     * Get the view .
     *
     * @param array $module Module name
     * @param array $view, the relative path
     * @param array $params View parameters that could be used inside theme files
     * 
     * @return string
     */
    function get_view($module, $view_path, $params = array()){

        $this->before_render_view();

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

    public function get_framework_view($framework_name, $view_path, $params = array()){
        
        $this->before_render_view();

        $lib_name           = $this->context->get_framework_data($framework_name, 'framework_folder');

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

    /** Custom code: Before the render view 
     * 
    **/
    public function before_render_view(){
        /* Custom code */
    }

    public function get_output(){

        if(empty($this->load_template_module_name)){
            throw new \Exception("load_template_module_name has not been set for view");
        }

        if(empty($this->load_template_rel_path)){
            throw new \Exception("load_template_rel_path has not been set for view");
            
        }

        if($this->load_template_mode == self::VIEW_LOAD_TEMPLATE_MODE_APPLICATION){
            return $this->get_view($this->load_template_module_name, $this->load_template_rel_path, $this->view_params); 
        }else if($this->load_template_mode == self::VIEW_LOAD_TEMPLATE_MODE_FRAMEWORK){
            return $this->get_framework_view($this->load_template_module_name, $this->load_template_rel_path, $this->view_params); 
        }

    }

    public function display(){

        if($this->output_mode == self::VIEW_OUTPUT_MODE_HTML){
            echo $this->get_output();
        }

        if($this->output_mode == self::VIEW_OUTPUT_MODE_JSON){
            echo json_encode($this->view_json_data);
        }

        if($this->output_mode == self::VIEW_OUTPUT_MODE_HTML_AND_JSON){
            echo \json_encode(array(
                'html'      => $this->get_output(),
                'json'      => $this->view_json_data
            ));
        }

        if($this->output_mode == self::VIEW_OUTPUT_MODE_JSON || $this->output_mode == self::VIEW_OUTPUT_MODE_HTML_AND_JSON){
            die();
        }

    }


}
